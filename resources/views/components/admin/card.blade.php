@props([
    'title' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow']) }}>
    @if($title || isset($header))
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            @if($title)
                <h3 class="text-lg font-semibold text-gray-800">{{ $title }}</h3>
            @endif
            
            @isset($header)
                {{ $header }}
            @endisset
        </div>
    @endif
    
    <div class="{{ $padding ? 'p-6' : '' }}">
        {{ $slot }}
    </div>
    
    @isset($footer)
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
            {{ $footer }}
        </div>
    @endisset
</div>
