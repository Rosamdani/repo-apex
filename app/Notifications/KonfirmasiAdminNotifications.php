<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\UserAccessTryouts;
use Filament\Notifications\Actions\Action;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class KonfirmasiAdminNotifications extends Notification
{
    use Queueable;

    private $userAccessTryoutId;

    /**
     * Create a new notification instance.
     */
    public function __construct($userAccessTryoutId)
    {
        $this->userAccessTryoutId = $userAccessTryoutId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toDatabase(User $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Perlu konfirmasi')
            ->body('Ada permintaan akses baru, klik lihat untuk menampilkan.')
            ->actions([
                Action::make('Lihat')
                    ->url(fn() => route('filament.admin-dashboard.resources.user-access-tryouts.view', $this->userAccessTryoutId))
                    ->button()
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}
