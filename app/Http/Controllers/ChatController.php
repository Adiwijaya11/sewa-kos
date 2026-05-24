<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Fetch user IDs who have had a conversation with current user
        $senderIds = Chat::where('receiver_id', $userId)->pluck('sender_id')->toArray();
        $receiverIds = Chat::where('sender_id', $userId)->pluck('receiver_id')->toArray();
        $chatUserIds = array_unique(array_merge($senderIds, $receiverIds));

        // Fetch user details excluding current user
        $conversations = User::whereIn('id', $chatUserIds)
            ->where('id', '!=', $userId)
            ->get()
            ->map(function ($user) use ($userId) {
                // Get last message
                $lastMessage = Chat::where(function ($q) use ($userId, $user) {
                    $q->where('sender_id', $userId)->where('receiver_id', $user->id);
                })->orWhere(function ($q) use ($userId, $user) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $userId);
                })->latest()->first();

                // Get unread counts
                $unreadCount = Chat::where('sender_id', $user->id)
                    ->where('receiver_id', $userId)
                    ->where('is_read', false)
                    ->count();

                return [
                    'user' => $user,
                    'last_message' => $lastMessage,
                    'unread_count' => $unreadCount,
                ];
            })
            // Sort by latest chat timestamp
            ->sortByDesc(function ($convo) {
                return $convo['last_message'] ? $convo['last_message']->created_at : 0;
            });

        return view('chat.index', compact('conversations'));
    }

    public function conversation($userId)
    {
        $currentUser = auth()->user();
        $chatPartner = User::findOrFail($userId);

        // Mark incoming chats as read
        Chat::where('sender_id', $chatPartner->id)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Fetch messages history
        $messages = Chat::where(function ($q) use ($currentUser, $chatPartner) {
            $q->where('sender_id', $currentUser->id)->where('receiver_id', $chatPartner->id);
        })->orWhere(function ($q) use ($currentUser, $chatPartner) {
            $q->where('sender_id', $chatPartner->id)->where('receiver_id', $currentUser->id);
        })
        ->orderBy('created_at', 'asc')
        ->get();

        // Fetch all conversations for the sidebar
        $senderIds = Chat::where('receiver_id', $currentUser->id)->pluck('sender_id')->toArray();
        $receiverIds = Chat::where('sender_id', $currentUser->id)->pluck('receiver_id')->toArray();
        $chatUserIds = array_unique(array_merge($senderIds, $receiverIds));

        $conversations = User::whereIn('id', $chatUserIds)
            ->where('id', '!=', $currentUser->id)
            ->get()
            ->map(function ($user) use ($currentUser) {
                $lastMessage = Chat::where(function ($q) use ($currentUser, $user) {
                    $q->where('sender_id', $currentUser->id)->where('receiver_id', $user->id);
                })->orWhere(function ($q) use ($currentUser, $user) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $currentUser->id);
                })->latest()->first();

                $unreadCount = Chat::where('sender_id', $user->id)
                    ->where('receiver_id', $currentUser->id)
                    ->where('is_read', false)
                    ->count();

                return [
                    'user' => $user,
                    'last_message' => $lastMessage,
                    'unread_count' => $unreadCount,
                ];
            })
            ->sortByDesc(function ($convo) {
                return $convo['last_message'] ? $convo['last_message']->created_at : 0;
            });

        return view('chat.index', compact('conversations', 'chatPartner', 'messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'message' => ['required_without:image', 'nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $senderId = auth()->id();
        $imagePath = null;

        if ($request->hasFile('image')) {
            $uploadPath = public_path('uploads/chats');
            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true, true);
            }

            $imageFile = $request->file('image');
            $filename = 'chat_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move($uploadPath, $filename);
            $imagePath = 'uploads/chats/' . $filename;
        }

        $chat = Chat::create([
            'sender_id' => $senderId,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'image' => $imagePath,
            'is_read' => false,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'chat' => $chat,
                'html' => view('chat.partials.message_bubble', compact('chat'))->render()
            ]);
        }

        return redirect()->route('chats.conversation', $request->receiver_id);
    }
}
