<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /** Feed JSON untuk pembaruan lonceng secara realtime (polling). */
    public function feed(Request $request): JsonResponse
    {
        $user = $request->user();

        $items = $user->notifications()->latest()->take(6)->get()->map(fn ($n) => [
            'id' => $n->id,
            'title' => $n->data['title'] ?? 'Notifikasi',
            'message' => $n->data['message'] ?? '',
            'icon' => $n->data['icon'] ?? null,
            'url' => route('notifications.read', $n->id),
            'read' => (bool) $n->read_at,
            'time' => $n->created_at->diffForHumans(),
        ]);

        return response()->json([
            'count' => $user->unreadNotifications()->count(),
            'items' => $items,
        ]);
    }

    /** Daftar seluruh notifikasi milik pengguna. */
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /** Tandai satu notifikasi dibaca, lalu arahkan ke tujuannya. */
    public function read(Request $request, string $id): RedirectResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $url = $notification->data['url'] ?? route('home');

        return redirect($url);
    }

    /** Tandai semua notifikasi sebagai sudah dibaca. */
    public function readAll(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
