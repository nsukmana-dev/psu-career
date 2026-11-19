<?php

namespace App\Notifications;

use App\Models\ContactRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactApproved extends Notification implements ShouldQueue
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
            'type' => 'contact_approved',
            'message' => "Kontakmu dibagikan ke {$this->contactRequest->company->name} untuk posisi {$this->contactRequest->position_title}.",
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Kontak Anda Dibagikan — PSU Career')
            ->greeting('Selamat!')
            ->line("Perusahaan {$this->contactRequest->company->name} tertarik dengan profilmu untuk posisi {$this->contactRequest->position_title}.")
            ->line('Kontakmu kini dapat dilihat oleh perusahaan tersebut.')
            ->action('Lihat Riwayat Kontak', route('student.contact-history.index'));
    }
}
