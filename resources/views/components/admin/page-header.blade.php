@props([
    'title',
    'breadcrumbs' => [],
])

<div {{ $attributes->merge(['class' => 'mb-6']) }}>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $title }}</h1>
            
            @if(count($breadcrumbs) > 0)
                <nav class="flex items-center text-sm text-gray-600">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">
                        <i class="fas fa-home ml-1"></i>
                        الرئيسية
                    </a>
                    @foreach($breadcrumbs as $breadcrumb)
                        <i class="fas fa-chevron-left mx-2 text-xs"></i>
                        @if(isset($breadcrumb['url']))
                            <a href="{{ $breadcrumb['url'] }}" class="hover:text-indigo-600">
                                {{ $breadcrumb['title'] }}
                            </a>
                        @else
                            <span class="text-gray-800">{{ $breadcrumb['title'] }}</span>
                        @endif
                    @endforeach
                </nav>
            @endif
        </div>
        
        @isset($actions)
            <div class="flex items-center gap-2">
                {{ $actions }}
            </div>
        @endisset
    </div>
</div>
