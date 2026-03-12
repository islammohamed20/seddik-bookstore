<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TagGroup;
use App\Models\TagOption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagGroupController extends Controller
{
    public function index()
    {
        $tagGroups = TagGroup::withCount('options')
            ->ordered()
            ->paginate(20);

        return view('admin.tags.index', compact('tagGroups'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'options' => 'nullable|array',
            'options.*.name_ar' => 'required|string|max:255',
            'options.*.name_en' => 'nullable|string|max:255',
        ]);

        $slug = Str::slug($validated['name_en'] ?: $validated['name_ar']);
        if (empty($slug)) {
            $slug = 'tag-group';
        }
        $originalSlug = $slug;
        $counter = 1;
        while (TagGroup::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $tagGroup = TagGroup::create([
            'slug' => $slug,
            'name_ar' => $validated['name_ar'],
            'name_en' => $validated['name_en'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        // إنشاء الخيارات
        if (!empty($validated['options'])) {
            foreach ($validated['options'] as $index => $optionData) {
                $optSlug = Str::slug($optionData['name_en'] ?? $optionData['name_ar']);
                if (empty($optSlug)) {
                    $optSlug = 'option';
                }
                $optOriginal = $optSlug;
                $optCounter = 1;
                while (TagOption::where('tag_group_id', $tagGroup->id)->where('slug', $optSlug)->exists()) {
                    $optSlug = $optOriginal . '-' . $optCounter++;
                }

                TagOption::create([
                    'tag_group_id' => $tagGroup->id,
                    'slug' => $optSlug,
                    'name_ar' => $optionData['name_ar'],
                    'name_en' => $optionData['name_en'] ?? null,
                    'is_active' => true,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'تم إنشاء مجموعة التاجات بنجاح');
    }

    public function edit(TagGroup $tag)
    {
        $tag->load(['options' => function ($q) {
            $q->ordered();
        }]);

        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, TagGroup $tag)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'options' => 'nullable|array',
            'options.*.id' => 'nullable|integer',
            'options.*.name_ar' => 'required|string|max:255',
            'options.*.name_en' => 'nullable|string|max:255',
        ]);

        $tag->update([
            'name_ar' => $validated['name_ar'],
            'name_en' => $validated['name_en'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        // مزامنة الخيارات
        $submittedIds = [];
        if (!empty($validated['options'])) {
            foreach ($validated['options'] as $index => $optionData) {
                if (!empty($optionData['id'])) {
                    // تحديث خيار موجود
                    $option = TagOption::find($optionData['id']);
                    if ($option && $option->tag_group_id === $tag->id) {
                        $option->update([
                            'name_ar' => $optionData['name_ar'],
                            'name_en' => $optionData['name_en'] ?? null,
                            'sort_order' => $index,
                        ]);
                        $submittedIds[] = $option->id;
                    }
                } else {
                    // إنشاء خيار جديد
                    $optSlug = Str::slug($optionData['name_en'] ?? $optionData['name_ar']);
                    if (empty($optSlug)) {
                        $optSlug = 'option';
                    }
                    $optOriginal = $optSlug;
                    $optCounter = 1;
                    while (TagOption::where('tag_group_id', $tag->id)->where('slug', $optSlug)->exists()) {
                        $optSlug = $optOriginal . '-' . $optCounter++;
                    }

                    $option = TagOption::create([
                        'tag_group_id' => $tag->id,
                        'slug' => $optSlug,
                        'name_ar' => $optionData['name_ar'],
                        'name_en' => $optionData['name_en'] ?? null,
                        'is_active' => true,
                        'sort_order' => $index,
                    ]);
                    $submittedIds[] = $option->id;
                }
            }
        }

        // حذف الخيارات التي تم إزالتها
        $tag->options()->whereNotIn('id', $submittedIds)->delete();

        return redirect()
            ->route('admin.tags.edit', $tag)
            ->with('success', 'تم تحديث مجموعة التاجات بنجاح');
    }

    public function destroy(TagGroup $tag)
    {
        $tag->delete();

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'تم حذف مجموعة التاجات بنجاح');
    }

    public function toggleStatus(TagGroup $tag)
    {
        $tag->update(['is_active' => !$tag->is_active]);

        return back()->with('success', 'تم تحديث حالة مجموعة التاجات');
    }
}
