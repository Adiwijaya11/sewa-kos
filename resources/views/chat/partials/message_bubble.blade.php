@php
    $isMe = auth()->id() === $chat->sender_id;
@endphp

<div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} mb-4">
    <div class="max-w-[70%]">
        <!-- Message Container -->
        <div class="p-3.5 rounded-2xl shadow-sm border {{ $isMe ? 'bg-emerald-600 border-emerald-500 text-white rounded-tr-none' : 'bg-white border-slate-100 text-slate-800 rounded-tl-none' }}">
            @if($chat->message)
                <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $chat->message }}</p>
            @endif

            @if($chat->image)
                <div class="mt-2 rounded-lg overflow-hidden border {{ $isMe ? 'border-emerald-500' : 'border-slate-100' }}">
                    <img src="{{ asset($chat->image) }}" alt="Attachment" class="max-w-full max-h-60 object-cover cursor-pointer hover:opacity-90 transition-opacity" onclick="window.open(this.src)">
                </div>
            @endif
        </div>
        
        <!-- Timestamp and Status -->
        <div class="flex items-center space-x-1.5 mt-1 px-1 {{ $isMe ? 'justify-end' : 'justify-start' }}">
            <span class="text-[10px] text-slate-400 font-medium">{{ $chat->created_at->timezone('Asia/Jakarta')->format('H:i') }}</span>
            @if($isMe)
                @if($chat->is_read)
                    <span class="text-[10px] text-emerald-500 font-bold" title="Dibaca"><i class="fa-solid fa-check-double"></i></span>
                @else
                    <span class="text-[10px] text-slate-300 font-bold" title="Terkirim"><i class="fa-solid fa-check"></i></span>
                @endif
            @endif
        </div>
    </div>
</div>
