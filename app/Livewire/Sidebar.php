<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

/**
 * Sidebar Component
 * 
 * هذا المكون يعرض Sidebar ديناميكي بناءً على دور المستخدم.
 * يجلب الروابط من ملف config/sidebar.php ويعرضها للمستخدم.
 */
class Sidebar extends Component
{
    /**
     * حالة Sidebar (مفتوح/مغلق)
     */
    public $isOpen = true;

    /**
     * Render the component
     * 
     * يجلب الروابط المناسبة لدور المستخدم الحالي ويعرضها
     */
    public function render()
    {
        // الحصول على المستخدم الحالي
        $user = Auth::user();
        
        // تحديد الروابط بناءً على دور المستخدم
        $links = $this->getLinksForUser($user);
        
        // تمرير الروابط إلى الـ view
        return view('livewire.sidebar', [
            'links' => $links,
        ]);
    }

    /**
     * Toggle Sidebar (فتح/إغلاق)
     */
    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
    }

    /**
     * Get sidebar links for the current user based on their role
     * 
     * @param \App\Models\User $user
     * @return array
     */
    private function getLinksForUser($user)
    {
        // إذا لم يكن المستخدم مسجل دخول، إرجاع مصفوفة فارغة
        if (!$user) {
            return [];
        }

        // جلب جميع الروابط من ملف الإعدادات
        $sidebarConfig = config('sidebar', []);

        // التحقق من دور المستخدم وإرجاع الروابط المناسبة
        if ($user->hasRole('admin')) {
            return $sidebarConfig['admin'] ?? [];
        } elseif ($user->hasRole('owner')) {
            return $sidebarConfig['owner'] ?? [];
        }

        // إذا لم يكن للمستخدم دور معروف، إرجاع مصفوفة فارغة
        return [];
    }
}
