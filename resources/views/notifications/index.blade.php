@extends('layouts.app')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@section('actions')
    @if(auth()->user()->unreadNotifications()->count() > 0)
        <form method="POST" action="{{ route('notifications.readAll') }}">
            @csrf
            <button class="btn-secondary">Tandai semua dibaca</button>
        </form>
    @endif
@endsection

@section('content')
    <div class="card divide-y divide-gray-100 dark:divide-gray-700">
        @forelse($notifications as $n)
            @php($data = $n->data)
            <a href="{{ route('notifications.read', $n->id) }}"
               class="flex items-start gap-4 p-4 transition hover:bg-gray-50 dark:hover:bg-gray-700/40 {{ $n->read_at ? '' : 'bg-brand-50/60 dark:bg-brand-900/10' }}">
                <span @class([
                    'mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full',
                    'bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-300' => ($data['icon'] ?? '') === 'report',
                    'bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-300' => ($data['icon'] ?? '') === 'procurement',
                    'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-300' => ($data['icon'] ?? '') === 'item',
                ])>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </span>
                <div class="min-w-0 grow">
                    <p class="font-semibold {{ $n->read_at ? '' : 'text-brand-700 dark:text-brand-300' }}">{{ $data['title'] ?? 'Notifikasi' }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $data['message'] ?? '' }}</p>
                    <p class="mt-1 text-xs text-gray-400">{{ $n->created_at->diffForHumans() }}</p>
                </div>
                @unless($n->read_at)
                    <span class="mt-2 h-2.5 w-2.5 shrink-0 rounded-full bg-brand-500"></span>
                @endunless
            </a>
        @empty
            <x-empty title="Belum ada notifikasi" subtitle="Notifikasi akan muncul di sini." />
        @endforelse
    </div>

    <div class="mt-4">{{ $notifications->links() }}</div>
@endsection
