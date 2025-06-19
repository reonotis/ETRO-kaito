<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Application $application;
    private string $section_name;

    /**
     * Create a new message instance.
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
        $from = $application->visit_scheduled_date_time;
        $to = $from->copy()->addMinutes(30);
        $this->section_name = $from->isoFormat('YYYY年MM月DD日（ddd）') . ' ' . $from->format('H:i') . '〜' . $to->format('H:i');
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '6⽉27⽇(⾦）ご⼊場 ETRO per Kaito Takahashi 渋⾕パルコ POP UP STORE へ当選しました',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.application',
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
            'section_name' => $this->section_name,
        ]);
    }
}
