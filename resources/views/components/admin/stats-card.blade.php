@props([
    'title',
    'value',
    'icon',
    'color' => 'blue',
    'subtitle' => null,
])

@php
$colorClasses = [
    'green' => 'bg-green-100 text-green-600',
    'blue' => 'bg-blue-100 text-blue-600',
    'purple' => 'bg-purple-100 text-purple-600',
    'yellow' => 'bg-yellow-100 text-yellow-600',
    'indigo' => 'bg-indigo-100 text-indigo-600',
    'pink' => 'bg-pink-100 text-pink-600',
    'teal' => 'bg-teal-100 text-teal-600',
    'red' => 'bg-red-100 text-red-600',
    'orange' => 'bg-orange-100 text-orange-600',
];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-200']) }}>
    <div class="flex items-center">
        <div class="p-3 rounded-full {{ $colorClasses[$color] ?? $colorClasses['blue'] }}">
            <i class="{{ $icon }} text-2xl"></i>
        </div>
        <div class="mr-4 flex-1">
            <p class="text-sm text-gray-500 mb-1">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-800">{{ $value }}</p>
            @if($subtitle)
                <p class="text-xs mt-1 {{ str_contains($subtitle, 'نشط') || str_contains($subtitle, 'متاح') ? 'text-green-600' : 'text-gray-500' }}">
                    {{ $subtitle }}
                </p>
            @endif
        </div>
    </div>
</div>
