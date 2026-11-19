<?php

namespace App\Notifications;

use App\Models\PartnershipApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PartnershipApplicationRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private PartnershipApplication $application) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengajuan Kemitraan — PSU Career')
            ->greeting('Halo '.$this->application->pic_name.',')
            ->line('Mohon maaf, pengajuan kemitraan dari '.$this->application->company_name.' belum dapat kami setujui saat ini.')
            ->line('Alasan: '.$this->application->rejection_reason)
            ->line('Anda dapat mengajukan kembali dengan melengkapi informasi yang diperlukan.');
    }
}
