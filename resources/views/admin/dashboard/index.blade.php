@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Admin')
@section('breadcrumb', 'Ringkasan inventaris & aktivitas laboratorium')

@section('content')
    @php
        // Konfigurasi chart dibuat di sini agar atribut data-chart cukup berisi 1 variabel
        // (menghindari nested array/parenthesis di dalam direktif @json).
        $trendChart = ['type' => 'bar', 'labels' => $reportTrend['labels'], 'datasets' => $reportTrend['datasets']];
        $labChart = ['type' => 'doughnut', 'labels' => $itemsPerLab['labels'], 'data' => $itemsPerLab['data']];
        $categoryChart = ['type' => 'bar', 'label' => 'Jumlah', 'labels' => $itemsPerCategory['labels'], 'data' => $itemsPerCategory['data']];
    @endphp

    {{-- Kartu statistik --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card label="Total Unit Barang" :value="number_format($stats['total_barang'])" color="brand">
            <x-slot:icon><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></x-slot:icon>
        </x-stat-card>
        <x-stat-card label="Jenis Barang" :value="number_format($stats['total_jenis'])" color="emerald">
            <x-slot:icon><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg></x-slot:icon>
        </x-stat-card>
        <x-stat-card label="Laporan Rusak" :value="number_format($stats['laporan_rusak'])" color="red">
            <x-slot:icon><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.84-2.75L13.74 4a2 2 0 00-3.5 0L3.18 16.25A2 2 0 005 19z"/></svg></x-slot:icon>
        </x-stat-card>
        <x-stat-card label="Pengajuan Menunggu" :value="number_format($stats['pengajuan_menunggu'])" color="amber">
            <x-slot:icon><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></x-slot:icon>
        </x-stat-card>
    </div>

    {{-- Grafik --}}
    <div class="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="card p-5 lg:col-span-2">
            <h3 class="mb-4 font-semibold">Tren Laporan (6 Bulan Terakhir)</h3>
            <div class="h-72">
                <canvas data-chart='@json($trendChart)'></canvas>
            </div>
        </div>
        <div class="card p-5">
            <h3 class="mb-4 font-semibold">Barang per Lab</h3>
            <div class="h-72">
                <canvas data-chart='@json($labChart)'></canvas>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-2">
        <div class="card p-5">
            <h3 class="mb-4 font-semibold">Jumlah Barang per Kategori</h3>
            <div class="h-72">
                <canvas data-chart='@json($categoryChart)'></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            {{-- Barang terbanyak rusak --}}
            <div class="card p-5">
                <h3 class="mb-3 font-semibold text-red-600 dark:text-red-400">Terbanyak Rusak</h3>
                <ul class="space-y-2 text-sm">
                    @forelse($mostRusak as $row)
                        <li class="flex items-center justify-between">
                            <span class="truncate">{{ $row['nama'] }}</span>
                            <span class="badge bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300">{{ $row['total'] }}</span>
                        </li>
                    @empty
                        <li class="text-gray-400">Belum ada data.</li>
                    @endforelse
                </ul>
            </div>
            {{-- Barang terbanyak hilang --}}
            <div class="card p-5">
                <h3 class="mb-3 font-semibold text-gray-600 dark:text-gray-300">Terbanyak Hilang</h3>
                <ul class="space-y-2 text-sm">
                    @forelse($mostHilang as $row)
                        <li class="flex items-center justify-between">
                            <span class="truncate">{{ $row['nama'] }}</span>
                            <span class="badge bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200">{{ $row['total'] }}</span>
                        </li>
                    @empty
                        <li class="text-gray-400">Belum ada data.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- Pengajuan terbaru & log aktivitas --}}
    <div class="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-2">
        <div class="card p-5">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-semibold">Pengajuan Terbaru</h3>
                <a href="{{ route('admin.procurements.index') }}" class="text-sm text-brand-600 hover:underline">Lihat semua</a>
            </div>
            <div class="space-y-3">
                @forelse($latestProcurements as $p)
                    <div class="flex items-center justify-between gap-3 border-b border-gray-100 pb-3 last:border-0 dark:border-gray-700">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium">{{ $p->nama_barang }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $p->user?->name }} &bull; {{ $p->requested_at?->diffForHumans() }}</p>
                        </div>
                        <x-badge :class="$p->status->badge()">{{ $p->status->label() }}</x-badge>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Belum ada pengajuan.</p>
                @endforelse
            </div>
        </div>

        <div class="card p-5">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-semibold">Aktivitas Terbaru</h3>
                <a href="{{ route('admin.activities.index') }}" class="text-sm text-brand-600 hover:underline">Lihat semua</a>
            </div>
            <ul class="space-y-3">
                @forelse($latestActivities as $log)
                    <li class="flex gap-3 text-sm">
                        <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-brand-500"></span>
                        <div>
                            <p>{{ $log->description }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $log->user?->name ?? 'Sistem' }} &bull; {{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </li>
                @empty
                    <li class="text-sm text-gray-400">Belum ada aktivitas.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
