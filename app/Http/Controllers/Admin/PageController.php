<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title_ar', 'like', "%{$search}%")
                    ->orWhere('title_en', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_published')) {
            $query->where('is_published', $request->is_published);
        }

        $pages = $query->orderBy('title_ar')->paginate(15)->withQueryString();

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $slug = Str::slug($validated['title']);
        $isPublished = $request->boolean('is_published', true);

        // Ensure unique slug
        $originalSlug = $slug;
        $counter = 1;
        while (Page::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter++;
        }

        Page::create([
            'slug' => $slug,
            'title_ar' => $validated['title'],
            'title_en' => $validated['title'],
            'content_ar' => $validated['content'],
            'content_en' => $validated['content'],
            'seo_title_ar' => $validated['meta_title'] ?? null,
            'seo_title_en' => $validated['meta_title'] ?? null,
            'seo_description_ar' => $validated['meta_description'] ?? null,
            'seo_description_en' => $validated['meta_description'] ?? null,
            'is_published' => $isPublished,
            'published_at' => $isPublished ? now() : null,
        ]);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'تم إضافة الصفحة بنجاح');
    }

    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $slug = $page->slug;

        if ($page->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            $originalSlug = $slug;
            $counter = 1;
            while (Page::where('slug', $slug)->where('id', '!=', $page->id)->exists()) {
                $slug = $originalSlug.'-'.$counter++;
            }
        }

        $isPublished = $request->boolean('is_published', true);

        $page->update([
            'slug' => $slug,
            'title_ar' => $validated['title'],
            'title_en' => $validated['title'],
            'content_ar' => $validated['content'],
            'content_en' => $validated['content'],
            'seo_title_ar' => $validated['meta_title'] ?? null,
            'seo_title_en' => $validated['meta_title'] ?? null,
            'seo_description_ar' => $validated['meta_description'] ?? null,
            'seo_description_en' => $validated['meta_description'] ?? null,
            'is_published' => $isPublished,
            'published_at' => $isPublished ? ($page->published_at ?? now()) : null,
        ]);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('success', 'تم تحديث الصفحة بنجاح');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'تم حذف الصفحة بنجاح');
    }

    public function toggleStatus(Page $page)
    {
        $page->update(['is_published' => ! $page->is_published]);

        return back()->with('success', 'تم تحديث حالة الصفحة');
    }
}
