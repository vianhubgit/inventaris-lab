@props(['label', 'value', 'icon' => null, 'color' => 'brand'])

@php
    $colors = [
        'brand' => 'bg-brand-100 text-brand-700 dark:bg-brand-900/40 dark:text-brand-300',
        'emerald' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
        'red' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
        'amber' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
        'gray' => 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200',
    ];
@endphp

<div class="card flex items-center gap-4 p-5">
    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $colors[$color] ?? $colors['brand'] }}">
        {{ $icon ?? '' }}
    </span>
    <div class="min-w-0">
        <p class="truncate text-sm text-gray-500 dark:text-gray-400">{{ $label }}</p>
        <p class="text-2xl font-bold">{{ $value }}</p>
    </div>
</div>
