<?php

namespace App\Notifications;

use App\Models\ContactRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactRequestDecided extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private ContactRequest $contactRequest) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'contact_request_decided',
            'message' => "Ada pembaruan pada request kontak {$this->contactRequest->request_number}.",
            'url' => route('company.contact-requests.show', $this->contactRequest),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pembaruan Request Kontak — PSU Career')
            ->line("Ada pembaruan status pada request kontak {$this->contactRequest->request_number}.")
            ->action('Lihat Detail', route('company.contact-requests.show', $this->contactRequest));
    }
}
