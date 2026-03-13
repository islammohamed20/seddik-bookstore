<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('sort_order')->get();

        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => 'nullable|string|max:255',
            'title_color_ar' => ['nullable', 'string', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'title_en' => 'nullable|string|max:255',
            'subtitle_ar' => 'nullable|string|max:255',
            'subtitle_color_ar' => ['nullable', 'string', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'subtitle_en' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'mobile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'button_url' => 'nullable|url|max:255',
            'button_text_ar' => 'nullable|string|max:50',
            'button_text_en' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'open_in_new_tab' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['open_in_new_tab'] = $request->boolean('open_in_new_tab', false);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Handle images
        $validated['image'] = $request->file('image')->store('sliders', 'public');

        if ($request->hasFile('mobile_image')) {
            $validated['mobile_image'] = $request->file('mobile_image')->store('sliders', 'public');
        }

        Slider::create($validated);

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'تم إضافة السلايدر بنجاح');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $validated = $request->validate([
            'title_ar' => 'nullable|string|max:255',
            'title_color_ar' => ['nullable', 'string', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'title_en' => 'nullable|string|max:255',
            'subtitle_ar' => 'nullable|string|max:255',
            'subtitle_color_ar' => ['nullable', 'string', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'subtitle_en' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'mobile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'button_url' => 'nullable|url|max:255',
            'button_text_ar' => 'nullable|string|max:50',
            'button_text_en' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'open_in_new_tab' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['open_in_new_tab'] = $request->boolean('open_in_new_tab', false);

        // Handle images
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($slider->image);
            $validated['image'] = $request->file('image')->store('sliders', 'public');
        }

        if ($request->hasFile('mobile_image')) {
            if ($slider->mobile_image) {
                Storage::disk('public')->delete($slider->mobile_image);
            }
            $validated['mobile_image'] = $request->file('mobile_image')->store('sliders', 'public');
        }

        $slider->update($validated);

        return redirect()
            ->route('admin.sliders.edit', $slider)
            ->with('success', 'تم تحديث السلايدر بنجاح');
    }

    public function destroy(Slider $slider)
    {
        Storage::disk('public')->delete($slider->image);
        if ($slider->mobile_image) {
            Storage::disk('public')->delete($slider->mobile_image);
        }

        $slider->delete();

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'تم حذف السلايدر بنجاح');
    }

    public function toggleStatus(Slider $slider)
    {
        $slider->update(['is_active' => ! $slider->is_active]);

        return back()->with('success', 'تم تحديث حالة السلايدر');
    }
}
