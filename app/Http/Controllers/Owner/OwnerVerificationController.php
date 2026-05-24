<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\OwnerVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class OwnerVerificationController extends Controller
{
    public function index()
    {
        $verification = OwnerVerification::where('user_id', auth()->id())->first();
        return view('owner.verification', compact('verification'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ktp_image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Max 2MB
            'selfie_image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Max 2MB
            'verification_video' => ['nullable', 'file', 'mimes:mp4,mov,avi', 'max:15360'], // Max 15MB
        ]);

        $owner = auth()->user();
        $uploadPath = public_path('uploads/verifications');

        if (!File::isDirectory($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true, true);
        }

        // Upload KTP
        $ktpFile = $request->file('ktp_image');
        $ktpFilename = 'ktp_' . $owner->id . '_' . uniqid() . '.' . $ktpFile->getClientOriginalExtension();
        $ktpFile->move($uploadPath, $ktpFilename);

        // Upload Selfie
        $selfieFile = $request->file('selfie_image');
        $selfieFilename = 'selfie_' . $owner->id . '_' . uniqid() . '.' . $selfieFile->getClientOriginalExtension();
        $selfieFile->move($uploadPath, $selfieFilename);

        // Upload Video
        $videoPath = null;
        if ($request->hasFile('verification_video')) {
            $videoFile = $request->file('verification_video');
            $videoFilename = 'video_' . $owner->id . '_' . uniqid() . '.' . $videoFile->getClientOriginalExtension();
            $videoFile->move($uploadPath, $videoFilename);
            $videoPath = 'uploads/verifications/' . $videoFilename;
        }

        // Create or Update Verification Record
        OwnerVerification::updateOrCreate(
            ['user_id' => $owner->id],
            [
                'ktp_image' => 'uploads/verifications/' . $ktpFilename,
                'selfie_image' => 'uploads/verifications/' . $selfieFilename,
                'verification_video' => $videoPath,
                'status' => 'pending',
                'notes' => null,
                'verified_at' => null,
            ]
        );

        return redirect()->route('owner.verification.index')
            ->with('success', 'Dokumen verifikasi identitas Anda telah berhasil diunggah! Admin kami akan segera meninjaunya dalam 1x24 jam.');
    }

    public function updateBankAccount(Request $request)
    {
        $request->validate([
            'bank_name' => ['required', 'string', 'max:255'],
            'bank_account_number' => ['required', 'string', 'max:255'],
            'bank_account_name' => ['required', 'string', 'max:255'],
        ]);

        auth()->user()->update([
            'bank_name' => $request->input('bank_name'),
            'bank_account_number' => $request->input('bank_account_number'),
            'bank_account_name' => $request->input('bank_account_name'),
        ]);

        return redirect()->route('owner.verification.index')
            ->with('success', 'Informasi Rekening Bank Pencairan berhasil diperbarui!');
    }

    public function checkBankAccount(Request $request)
    {
        $request->validate([
            'bank_name' => ['required', 'string'],
            'bank_account_number' => ['required', 'string'],
        ]);

        $bankName = strtoupper($request->input('bank_name'));
        $accountNumber = preg_replace('/[^0-9]/', '', $request->input('bank_account_number'));

        // Check account number length standard for top Indonesian banks
        $isValidLength = false;
        $expectedLengthDesc = '';

        switch ($bankName) {
            case 'BCA':
                // BCA accounts are usually 10 digits
                $isValidLength = (strlen($accountNumber) === 10);
                $expectedLengthDesc = '10 digit';
                break;
            case 'MANDIRI':
                // Mandiri accounts are usually 13 digits
                $isValidLength = (strlen($accountNumber) === 13);
                $expectedLengthDesc = '13 digit';
                break;
            case 'BRI':
                // BRI accounts are usually 15 digits
                $isValidLength = (strlen($accountNumber) === 15);
                $expectedLengthDesc = '15 digit';
                break;
            case 'BNI':
                // BNI accounts are usually 10 digits
                $isValidLength = (strlen($accountNumber) === 10);
                $expectedLengthDesc = '10 digit';
                break;
            default:
                // Other banks (BSI, CIMB Niaga, etc.) are usually between 10 to 14 digits
                $isValidLength = (strlen($accountNumber) >= 10 && strlen($accountNumber) <= 15);
                $expectedLengthDesc = '10-15 digit';
        }

        if (!$isValidLength) {
            return response()->json([
                'success' => false,
                'message' => "Format rekening {$bankName} tidak sesuai. Rekening {$bankName} resmi harus terdiri dari {$expectedLengthDesc}."
            ], 422);
        }

        // Format is valid! Return empty account_name so the owner can type their real name from scratch
        return response()->json([
            'success' => true,
            'account_name' => ''
        ]);
    }
}
