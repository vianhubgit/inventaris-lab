@props(['class' => 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200'])

<span {{ $attributes->merge(['class' => 'badge '.$class]) }}>{{ $slot }}</span>
