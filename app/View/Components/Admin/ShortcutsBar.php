<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShortcutsBar extends Component
{
    public array $shortcuts;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->shortcuts = [
            ['key' => 'Ctrl+S', 'action' => 'حفظ', 'icon' => 'fas fa-save'],
            ['key' => 'Ctrl+N', 'action' => 'جديد', 'icon' => 'fas fa-plus'],
            ['key' => 'Ctrl+E', 'action' => 'تعديل', 'icon' => 'fas fa-edit'],
            ['key' => 'Ctrl+F', 'action' => 'بحث', 'icon' => 'fas fa-search'],
            ['key' => 'Ctrl+?', 'action' => 'مساعدة', 'icon' => 'fas fa-question-circle'],
            ['key' => 'Esc', 'action' => 'إلغاء', 'icon' => 'fas fa-times'],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.shortcuts-bar');
    }
}
