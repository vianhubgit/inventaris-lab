@php
    $unreadCount = auth()->user()->unreadNotifications()->count();
    $recent = auth()->user()->notifications()->latest()->take(6)->get();
@endphp

<div class="relative" data-notif data-notif-feed="{{ route('notifications.feed') }}">
    <button data-notif-toggle class="relative rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Notifikasi">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        <span data-notif-badge
              class="absolute -right-0.5 -top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white {{ $unreadCount > 0 ? '' : 'hidden' }}">
            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
        </span>
    </button>

    <div data-notif-panel
         class="absolute right-0 z-50 mt-2 hidden w-80 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3 dark:border-gray-700">
            <p class="text-sm font-semibold">Notifikasi</p>
            <form method="POST" action="{{ route('notifications.readAll') }}" data-no-loader>
                @csrf
                <button class="text-xs text-brand-600 hover:underline">Tandai dibaca</button>
            </form>
        </div>

        <div data-notif-list class="max-h-80 overflow-y-auto">
            @forelse($recent as $n)
                @php($data = $n->data)
                <a href="{{ route('notifications.read', $n->id) }}"
                   class="flex items-start gap-3 px-4 py-3 transition hover:bg-gray-50 dark:hover:bg-gray-700/40 {{ $n->read_at ? '' : 'bg-brand-50/60 dark:bg-brand-900/10' }}">
                    <span class="mt-1 h-2 w-2 shrink-0 rounded-full {{ $n->read_at ? 'bg-transparent' : 'bg-brand-500' }}"></span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium">{{ $data['title'] ?? 'Notifikasi' }}</p>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ $data['message'] ?? '' }}</p>
                        <p class="mt-0.5 text-[11px] text-gray-400">{{ $n->created_at->diffForHumans() }}</p>
                    </div>
                </a>
            @empty
                <p data-notif-empty class="px-4 py-6 text-center text-sm text-gray-400">Belum ada notifikasi.</p>
            @endforelse
        </div>

        <a href="{{ route('notifications.index') }}" class="block border-t border-gray-100 px-4 py-2.5 text-center text-sm font-medium text-brand-600 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/40">
            Lihat semua notifikasi
        </a>
    </div>
</div>
