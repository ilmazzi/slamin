<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class EmailVerificationNotification extends VerifyEmail
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('🎭 Verifica il tuo account Slamin')
            ->greeting('Ciao ' . $notifiable->getDisplayName() . '!')
            ->line('Benvenuto nella community Poetry Slam!')
            ->line('Per completare la registrazione e accedere a tutte le funzionalità, clicca sul pulsante qui sotto per verificare il tuo indirizzo email.')
            ->action('Verifica Email', $verificationUrl)
            ->line('Una volta verificata l\'email, potrai:')
            ->line('• Partecipare agli eventi Poetry Slam')
            ->line('• Caricare le tue poesie e video')
            ->line('• Connetterti con altri artisti')
            ->line('• Organizzare i tuoi eventi')
            ->line('Se non hai creato un account, ignora questa email.')
            ->salutation('A presto sul palco! 🎤')
            ->tag('email-verification')
            ->metadata('user_id', $notifiable->id);
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
