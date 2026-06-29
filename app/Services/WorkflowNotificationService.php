<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\WorkflowNotification;
use Illuminate\Support\Facades\Notification;

class WorkflowNotificationService
{
    public function sendToRole(
        string $role,
        string $title,
        string $message,
        string $url,
        string $icon = 'bx-bell',
        string $color = 'primary',
    ): void {
        $recipients = User::query()
            ->where('is_active', true)
            ->whereHas('role', fn ($query) => $query->where('slug', $role))
            ->get();

        Notification::send($recipients, new WorkflowNotification($title, $message, $url, $icon, $color));
    }

    public function sendToUser(
        ?User $user,
        string $title,
        string $message,
        string $url,
        string $icon = 'bx-bell',
        string $color = 'primary',
    ): void {
        $user?->notify(new WorkflowNotification($title, $message, $url, $icon, $color));
    }
}
