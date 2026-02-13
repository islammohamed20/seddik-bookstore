@props([
    'headers' => [],
    'striped' => false,
    'hoverable' => true,
])

<div {{ $attributes->merge(['class' => 'overflow-x-auto']) }}>
    <table class="w-full">
        @if(count($headers) > 0)
            <thead class="bg-gray-50">
                <tr>
                    @foreach($headers as $header)
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
        @endif
        
        <tbody class="{{ $striped ? 'divide-y divide-gray-200' : '' }}">
            {{ $slot }}
        </tbody>
        
        @isset($footer)
            <tfoot class="bg-gray-50 border-t border-gray-200">
                {{ $footer }}
            </tfoot>
        @endisset
    </table>
</div>
