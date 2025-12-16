<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SendFilesMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EmailController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'file.*' => 'required|file|max:25600',
        ]);

        $attachments = [];

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file){
                $path = $file->store('temp');

                $attachment[] = [
                    'path' => storage_path('app/' . $path),
                    'name' => $file->getClientOriginalName(),
                ];
            }
        }

        Mail::to($request->email)->send(
            new SendFilesMail (
                $request->subject,
                $request->description,
                $attachments
            )
            );
        
            foreach ($attachments as $file){
                unlink($file['path']);
            }

            return back()->with('success', 'Email was sent successfully');
    }



}
