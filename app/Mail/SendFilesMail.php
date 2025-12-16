<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendFilesMail extends Mailable
{
   
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
    
        public string $subjectText,
        public string $description,
        public array $files
    ) {}


    /**
     * Get the message envelope.
     */
    /*public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Files Mail',
        );
    }*/

    /**
     * Get the message content definition.
     */
    /*public function content(): Content
    {
        return new Content(
            markdown: 'emails.send-files',
        );
    }*/


        public function build() {

            \Log::debug('Building SendFilesMail', [
            'user_id' => auth()->id(),
            'files_count' => count($this->files)
        ]);

        
            $mail = $this 
                 ->subject($this->subjectText)
                 ->markdown('emails.send-files');

            
            foreach ($this->files as $file) {
                $mail->attach($file['path'], [
                    'as' => $file['name'],
                ]);
            }
            return $mail;
        }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    /*public function attachments(): array
    {
        return [];
    }*/
}
