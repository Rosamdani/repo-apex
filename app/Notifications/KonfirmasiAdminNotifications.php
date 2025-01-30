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
use Illuminate\Support\Facades\Log;

class KonfirmasiAdminNotifications extends Notification
{
    use Queueable;

    private $userAccessRequestId;
    private $type;
    private $message;
    private $route;

    /**
     * Create a new notification instance.
     */
    public function __construct($userAccessRequestId, $type)
    {
        $this->userAccessRequestId = $userAccessRequestId;
        $this->type = $type;
    }

    public function getType()
    {
        if ($this->type == 'tryout') {
            $this->route = 'filament.admin-dashboard.resources.user-access-tryouts.view';
        } else if ($this->type == 'paket') {
            $this->route = 'filament.admin-dashboard.resources.user-access-pakets.view';
        } else {
            Log::info('Notification type not found');
            abort(404);
        }
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
        $this->getType();
        return FilamentNotification::make()
            ->title('Perlu konfirmasi')
            ->body('Ada permintaan akses baru, klik lihat untuk menampilkan.')
            ->actions([
                Action::make('Lihat')
                    ->url(fn() => route($this->route, $this->userAccessRequestId))
                    ->button()
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}