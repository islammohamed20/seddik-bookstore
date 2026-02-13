@props([
    'icon' => 'fas fa-inbox',
    'title' => 'لا توجد بيانات',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'text-center py-12']) }}>
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
        <i class="{{ $icon }} text-2xl"></i>
    </div>
    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $title }}</h3>
    @if($description)
        <p class="text-gray-500 mb-4">{{ $description }}</p>
    @endif
    
    @isset($action)
        <div class="mt-4">
            {{ $action }}
        </div>
    @endisset
</div>
