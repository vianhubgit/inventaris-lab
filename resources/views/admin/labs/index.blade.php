@extends('layouts.app')

@section('title', 'Tata Letak Lab')
@section('page-title', 'Laboratorium & Tata Letak')

@section('actions')
    <a href="{{ route('admin.labs.create') }}" class="btn-primary">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Lab
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($labs as $lab)
            <div class="card p-5">
                <div class="mb-3 flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-bold">{{ $lab->nama }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Kode: {{ $lab->kode }}</p>
                    </div>
                    @if($lab->has_groups)
                        <x-badge class="bg-brand-100 text-brand-700 dark:bg-brand-900/40 dark:text-brand-300">Berkelompok</x-badge>
                    @else
                        <x-badge class="bg-gray-200 text-gray-600 dark:bg-gray-700">Inventaris Umum</x-badge>
                    @endif
                </div>
                <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">{{ $lab->keterangan ?: 'Tanpa keterangan.' }}</p>
                <div class="mb-4 flex gap-4 text-sm">
                    <span><strong>{{ $lab->groups_count }}</strong> kelompok</span>
                    <span><strong>{{ $lab->items_count }}</strong> barang</span>
                </div>
                <div class="flex gap-2">
                    @if($lab->has_groups)
                        <a href="{{ route('admin.labs.show', $lab) }}" class="btn-primary px-3 py-1.5 text-xs">Atur Tata Letak</a>
                    @endif
                    <a href="{{ route('admin.labs.edit', $lab) }}" class="btn-secondary px-3 py-1.5 text-xs">Edit</a>
                    <form method="POST" action="{{ route('admin.labs.destroy', $lab) }}" data-confirm="Hapus {{ $lab->nama }}?">
                        @csrf @method('DELETE')
                        <button class="btn-danger px-3 py-1.5 text-xs">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 lg:col-span-3"><div class="card"><x-empty title="Belum ada laboratorium" /></div></div>
        @endforelse
    </div>
@endsection
