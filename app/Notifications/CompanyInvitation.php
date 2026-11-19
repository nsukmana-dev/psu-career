<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompanyInvitation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private string $token) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('password.reset', ['token' => $this->token, 'email' => $notifiable->email]);

        return (new MailMessage)
            ->subject('Undangan Akun Perusahaan — PSU Career')
            ->greeting('Halo '.$notifiable->name.',')
            ->line('Pengajuan kemitraan Anda dengan PSU Career telah disetujui. Akun perusahaan Anda sudah dibuat.')
            ->line('Silakan atur password Anda untuk mulai menggunakan akun.')
            ->action('Atur Password', $url)
            ->line('Tautan ini berlaku selama 60 menit.');
    }
}
