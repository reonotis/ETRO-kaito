<?php

namespace App\Mail\Hankyu;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WinnerNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Application $application;
    public string $sectionName;

    /**
     * Create a new message instance.
     */
    public function __construct(Application $application, string $sectionName)
    {
        $this->application = $application;
        $this->sectionName = $sectionName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'ETRO per Kaito Takahashi 阪急うめだ本店 ご当選のお知らせ',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.hankyu.winner_notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        return $this->with([
            'application' => $this->application,
            'section_name' => $this->sectionName,
        ]);
    }
}
