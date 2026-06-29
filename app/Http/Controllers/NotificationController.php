<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('notifications.index', compact('notifications'));
    }

    public function read(Request $request, DatabaseNotification $notification): RedirectResponse
    {
        $user = $request->user();

        abort_unless($notification->notifiable_id === $request->user()->id
            && in_array($notification->notifiable_type, [$user::class, $user->getMorphClass()], true), 403);

        $notification->markAsRead();
        $url = $notification->data['url'] ?? route('dashboard');

        return redirect($this->safeUrl($url));
    }

    public function readAll(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    private function safeUrl(string $url): string
    {
        return str_starts_with($url, url('/')) ? $url : route('dashboard');
    }
}
