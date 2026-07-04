@props(['title' => 'Belum ada data', 'subtitle' => null])

<div class="flex flex-col items-center justify-center px-6 py-16 text-center">
    <span class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-700">
        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
    </span>
    <p class="font-semibold text-gray-700 dark:text-gray-200">{{ $title }}</p>
    @if($subtitle)<p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>@endif
    @if($slot->isNotEmpty())<div class="mt-4">{{ $slot }}</div>@endif
</div>
