<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('¡Bienvenido a nuestra plataforma!')
            ->greeting('¡Hola ' . $this->user->name . '!')
            ->line('Gracias por registrarte en nuestra plataforma.')
            ->line('Tu cuenta ha sido creada exitosamente.')
            ->line('Por favor, verifica tu dirección de email haciendo clic en el botón de abajo.')
            ->action('Verificar Email', url('/api/email/verify/' . $this->user->id . '/' . sha1($this->user->email)))
            ->line('Si no creaste esta cuenta, puedes ignorar este email.')
            ->line('¡Gracias por unirte a nosotros!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'registered_at' => now()->toDateTimeString(),
        ];
    }
}
