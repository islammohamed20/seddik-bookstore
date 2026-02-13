@props([
    'type' => 'info',
    'dismissible' => true,
    'icon' => null,
])

@php
$types = [
    'success' => [
        'bg' => 'bg-green-100',
        'border' => 'border-green-400',
        'text' => 'text-green-700',
        'icon' => 'fas fa-check-circle',
    ],
    'error' => [
        'bg' => 'bg-red-100',
        'border' => 'border-red-400',
        'text' => 'text-red-700',
        'icon' => 'fas fa-exclamation-circle',
    ],
    'warning' => [
        'bg' => 'bg-yellow-100',
        'border' => 'border-yellow-400',
        'text' => 'text-yellow-700',
        'icon' => 'fas fa-exclamation-triangle',
    ],
    'info' => [
        'bg' => 'bg-blue-100',
        'border' => 'border-blue-400',
        'text' => 'text-blue-700',
        'icon' => 'fas fa-info-circle',
    ],
];

$config = $types[$type] ?? $types['info'];
$displayIcon = $icon ?? $config['icon'];
@endphp

<div {{ $attributes->merge(['class' => "{$config['bg']} border {$config['border']} {$config['text']} px-4 py-3 rounded-lg flex items-center justify-between"]) }}
     x-data="{ show: true }" 
     x-show="show"
     x-transition>
    <div class="flex items-center gap-3">
        @if($displayIcon)
            <i class="{{ $displayIcon }} text-lg"></i>
        @endif
        <div class="flex-1">
            {{ $slot }}
        </div>
    </div>
    
    @if($dismissible)
        <button @click="show = false" class="{{ $config['text'] }} hover:opacity-75 transition">
            <i class="fas fa-times"></i>
        </button>
    @endif
</div>
