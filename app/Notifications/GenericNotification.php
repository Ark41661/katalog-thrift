<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GenericNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $message,
        public string $icon = '🔔',
        public ?string $url = null,
        public array $extra = []
    ) {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return array_merge([
            'message' => $this->message,
            'icon'    => $this->icon,
            'url'     => $this->url,
        ], $this->extra);
    }
}
