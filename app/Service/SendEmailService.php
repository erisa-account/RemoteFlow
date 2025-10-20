<?php
namespace App\Service;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendFileMail;

class SendEmailService
{
    public function send(array $validated, $files = [])
    {
        $to = $validated['email'];
        $subject = $validated['subject'];
        $message = $validated['description'] ?? 'No description provided';

        $attachments = [];

        if ($files) {
            foreach ($files as $file) {
                $path = $file->store('uploads', 'public');
                $attachments[] = storage_path('app/public/' . $path);
            }
        }
          
        Mail::raw($message, function ($mail) use ($to, $subject, $attachments) {
            $mail->to($to)->subject($subject);

            foreach ($attachments as $filePath) {
                $mail->attach($filePath);
            }
        });


        return [
            'email' => $to,
            'subject' => $subject,
            'attachments' => $attachments,
            'description' => $message,
        ];
    }
}