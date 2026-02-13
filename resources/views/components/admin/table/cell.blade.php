@props([
    'header' => false,
])

@if($header)
    <th {{ $attributes->merge(['class' => 'px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase']) }}>
        {{ $slot }}
    </th>
@else
    <td {{ $attributes->merge(['class' => 'px-4 py-3 text-sm text-gray-800']) }}>
        {{ $slot }}
    </td>
@endif
