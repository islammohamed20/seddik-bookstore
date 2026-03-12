@extends('admin.layouts.app')

@section('title', 'تقارير الزيارات')
@section('page-title', 'تقارير الزيارات')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm text-gray-600">إجمالي الزيارات</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm text-gray-600">زيارات بموقع</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['with_location'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm text-gray-600">زيارات اليوم</p>
            <p class="text-2xl font-bold text-indigo-600 mt-1">{{ $stats['today'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="text-xs text-gray-500">الدولة</label>
                <select name="country" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">الكل</option>
                    @foreach($countries as $country)
                        <option value="{{ $country }}" {{ request('country') === $country ? 'selected' : '' }}>{{ $country }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs text-gray-500">المدينة</label>
                <input type="text" name="city" value="{{ request('city') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="بحث بالمدينة">
            </div>
            <div>
                <label class="text-xs text-gray-500">من</label>
                <input type="date" name="from" value="{{ request('from') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-xs text-gray-500">إلى</label>
                <input type="date" name="to" value="{{ request('to') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg">تصفية</button>
                <a href="{{ route('admin.visit-reports.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">مسح</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">التاريخ</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">المسار</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الدولة</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">المدينة</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الإحداثيات</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">المصدر</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($visits as $visit)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $visit->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $visit->path ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $visit->country ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $visit->city ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                @if($visit->latitude && $visit->longitude)
                                    {{ $visit->latitude }}, {{ $visit->longitude }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $visit->source }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-right text-gray-500">لا توجد زيارات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($visits->hasPages())
            <div class="p-4 border-t">
                {{ $visits->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
