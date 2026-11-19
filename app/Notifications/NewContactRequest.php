<?php

namespace App\Notifications;

use App\Models\ContactRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactRequest extends Notification implements ShouldQueue
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
            'type' => 'contact_request_new',
            'message' => "Request kontak baru {$this->contactRequest->request_number} dari {$this->contactRequest->company->name}.",
            'url' => route('admin.contact-requests.show', $this->contactRequest),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Request Kontak Baru — PSU Career')
            ->line("Perusahaan {$this->contactRequest->company->name} mengajukan request kontak {$this->contactRequest->request_number}.")
            ->action('Tinjau Request', route('admin.contact-requests.show', $this->contactRequest));
    }
}
