@props([
    'route',
    'icon',
    'label',
    'active' => false,
])

@php
// Handle wildcard routes for matching
$routePattern = $route;
$actualRoute = $route;

// If route contains wildcard, use the base route for URL generation
if (str_contains($route, '*')) {
    $actualRoute = str_replace('.*', '.index', $route);
}

$isActive = $active || request()->routeIs($routePattern);
$classes = $isActive 
    ? 'bg-indigo-900 text-white' 
    : 'text-indigo-200 hover:bg-indigo-700';
@endphp

<a href="{{ route($actualRoute) }}" 
   {{ $attributes->merge(['class' => "flex items-center px-4 py-3 rounded-lg mb-1 transition-colors duration-200 {$classes}"]) }}>
    <i class="{{ $icon }} w-5"></i>
    <span class="mr-3">{{ $label }}</span>
    
    @isset($badge)
        <span class="mr-auto">
            {{ $badge }}
        </span>
    @endisset
</a>
