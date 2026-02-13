@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'right',
    'href' => null,
])

@php
$variants = [
    'primary' => 'bg-indigo-600 hover:bg-indigo-700 text-white',
    'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white',
    'success' => 'bg-green-600 hover:bg-green-700 text-white',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white',
    'warning' => 'bg-yellow-600 hover:bg-yellow-700 text-white',
    'info' => 'bg-blue-600 hover:bg-blue-700 text-white',
    'outline-primary' => 'border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-600 hover:text-white',
    'outline-danger' => 'border-2 border-red-600 text-red-600 hover:bg-red-600 hover:text-white',
    'ghost' => 'text-gray-600 hover:bg-gray-100',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-base',
    'lg' => 'px-6 py-3 text-lg',
];

$variantClass = $variants[$variant] ?? $variants['primary'];
$sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

@if($href)
    <a href="{{ $href }}"
       {{ $attributes->merge(['class' => "inline-flex items-center justify-center gap-2 rounded-lg font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 {$variantClass} {$sizeClass}"]) }}>
        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }}"></i>
        @endif

        {{ $slot }}

        @if($icon && $iconPosition === 'left')
            <i class="{{ $icon }}"></i>
        @endif
    </a>
@else
    <button type="{{ $type }}"
            {{ $attributes->merge(['class' => "inline-flex items-center justify-center gap-2 rounded-lg font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 {$variantClass} {$sizeClass}"]) }}>
        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }}"></i>
        @endif

        {{ $slot }}

        @if($icon && $iconPosition === 'left')
            <i class="{{ $icon }}"></i>
        @endif
    </button>
@endif
