@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Sekretaris')
@section('breadcrumb', 'Selamat datang, ' . auth()->user()->name)

@section('content')
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card label="Total Unit Barang" :value="number_format($stats['total_barang'])" color="brand">
            <x-slot:icon><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></x-slot:icon>
        </x-stat-card>
        <x-stat-card label="Laporan Saya" :value="number_format($stats['laporan_saya'])" color="amber">
            <x-slot:icon><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg></x-slot:icon>
        </x-stat-card>
        <x-stat-card label="Pengajuan Menunggu" :value="number_format($stats['pengajuan_menunggu'])" color="gray">
            <x-slot:icon><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></x-slot:icon>
        </x-stat-card>
        <x-stat-card label="Pengajuan Disetujui" :value="number_format($stats['pengajuan_disetujui'])" color="emerald">
            <x-slot:icon><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></x-slot:icon>
        </x-stat-card>
    </div>

    {{-- Aksi cepat --}}
    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <a href="{{ route('sekretaris.reports.create', ['type' => 'rusak']) }}" class="card flex items-center gap-4 p-5 transition hover:shadow-md">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-300">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.84-2.75L13.74 4a2 2 0 00-3.5 0L3.18 16.25A2 2 0 005 19z"/></svg>
            </span>
            <div><p class="font-semibold">Lapor Barang Rusak</p><p class="text-xs text-gray-500">Buat laporan kerusakan</p></div>
        </a>
        <a href="{{ route('sekretaris.reports.create', ['type' => 'hilang']) }}" class="card flex items-center gap-4 p-5 transition hover:shadow-md">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </span>
            <div><p class="font-semibold">Lapor Barang Hilang</p><p class="text-xs text-gray-500">Buat laporan kehilangan</p></div>
        </a>
        <a href="{{ route('sekretaris.procurements.create') }}" class="card flex items-center gap-4 p-5 transition hover:shadow-md">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-100 text-brand-600 dark:bg-brand-900/40 dark:text-brand-300">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/></svg>
            </span>
            <div><p class="font-semibold">Pinjam Barang</p><p class="text-xs text-gray-500">Pengajuan penambahan</p></div>
        </a>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-2">
        <div class="card p-5">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-semibold">Laporan Terakhir Saya</h3>
                <a href="{{ route('sekretaris.reports.index') }}" class="text-sm text-brand-600 hover:underline">Semua</a>
            </div>
            @forelse($recentReports as $r)
                <div class="flex items-center justify-between gap-3 border-b border-gray-100 py-2 last:border-0 dark:border-gray-700">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium">{{ $r->item?->nama }}</p>
                        <p class="text-xs text-gray-500">{{ $r->lab?->nama }} &bull; {{ $r->reported_at?->diffForHumans() }}</p>
                    </div>
                    <x-badge :class="$r->type->badge()">{{ $r->type->label() }}</x-badge>
                </div>
            @empty
                <p class="text-sm text-gray-400">Belum ada laporan.</p>
            @endforelse
        </div>

        <div class="card p-5">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-semibold">Pengajuan Terakhir Saya</h3>
                <a href="{{ route('sekretaris.procurements.index') }}" class="text-sm text-brand-600 hover:underline">Semua</a>
            </div>
            @forelse($recentProcurements as $p)
                <div class="flex items-center justify-between gap-3 border-b border-gray-100 py-2 last:border-0 dark:border-gray-700">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium">{{ $p->nama_barang }}</p>
                        <p class="text-xs text-gray-500">{{ $p->jumlah }} unit &bull; {{ $p->requested_at?->diffForHumans() }}</p>
                    </div>
                    <x-badge :class="$p->status->badge()">{{ $p->status->label() }}</x-badge>
                </div>
            @empty
                <p class="text-sm text-gray-400">Belum ada pengajuan.</p>
            @endforelse
        </div>
    </div>
@endsection
