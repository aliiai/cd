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
        $links = [];
        if ($user->hasRole('admin')) {
            $links = $sidebarConfig['admin'] ?? [];
        } elseif ($user->hasRole('owner')) {
            $links = $sidebarConfig['owner'] ?? [];
        }

        // تطبيق الترجمة على الروابط
        return $this->translateLinks($links);
    }

    /**
     * تطبيق الترجمة على روابط الـ Sidebar
     * 
     * @param array $links
     * @return array
     */
    private function translateLinks($links)
    {
        $translatedLinks = [];
        
        foreach ($links as $link) {
            $translatedLink = $link;
            
            // ترجمة عنوان القسم إذا كان موجوداً
            if (isset($link['type']) && $link['type'] === 'section' && isset($link['title'])) {
                $translatedLink['title'] = $this->translateName($link['title']);
            }
            
            // ترجمة اسم الرابط الرئيسي
            if (isset($link['name'])) {
                $translatedLink['name'] = $this->translateName($link['name']);
            }
            
            // ترجمة الروابط الفرعية (children) إذا كانت موجودة
            if (isset($link['type']) && $link['type'] === 'dropdown' && isset($link['children'])) {
                $translatedChildren = [];
                foreach ($link['children'] as $child) {
                    $translatedChild = $child;
                    if (isset($child['name'])) {
                        $translatedChild['name'] = $this->translateName($child['name']);
                    }
                    $translatedChildren[] = $translatedChild;
                }
                $translatedLink['children'] = $translatedChildren;
            }
            
            $translatedLinks[] = $translatedLink;
        }
        
        return $translatedLinks;
    }

    /**
     * ترجمة اسم الرابط باستخدام ملف الترجمة
     * 
     * @param string $name
     * @return string
     */
    private function translateName($name)
    {
        // خريطة الأسماء العربية إلى مفاتيح الترجمة
        $nameMap = [
            // Admin Sidebar
            'لوحة التحكم' => 'dashboard',
            'المستخدمين' => 'users',
            'المشرفين' => 'admins',
            'الأدوار' => 'roles',
            'الصلاحيات' => 'permissions',
            'طلبات الاشتراكات' => 'subscription_requests',
            'الباقات الحالية' => 'current_subscriptions',
            'تقارير مقدمي الخدمات' => 'service_providers_reports',
            'تقارير الحملات' => 'campaigns_reports',
            'تقارير الرسائل' => 'messages_reports',
            'تقارير الاشتراكات' => 'subscriptions_reports',
            'الإشعارات والتنبيهات' => 'notifications',
            'محتوى الصفحات' => 'page_content',
            'سجلات التدقيق' => 'audit_logs',
            'الإعدادات' => 'settings',
            'الدعم والتذاكر' => 'support_tickets',
            'تسجيل الخروج' => 'logout',
            // Owner Sidebar
            'المديونين' => 'debtors',
            'حالة التحصيل' => 'collections',
            'التحليلات الذكية' => 'analytics',
            'تحليلات حالة التحصيل' => 'collection_status_analytics',
            'تحليلات الدخل' => 'income_analytics',
            'الاشتراكات' => 'owner_subscriptions',
            'التقارير' => 'owner_reports',
            'تقرير حالات الديون' => 'debt_status_report',
            'تقرير الرسائل' => 'messages_report',
            'أداء الحملات' => 'campaigns_performance',
            'استخدام الاشتراك' => 'subscription_usage',
            'سجل العمليات' => 'operations_log',
            // Section Titles - Admin
            'الرئيسية' => 'main_navigation',
            'إدارة المستخدمين' => 'user_management',
            'الصلاحيات والأدوار' => 'roles_permissions',
            'الاشتراكات' => 'subscription_management',
            'التقارير' => 'reporting_analytics',
            'النظام' => 'system_operations',
            // Section Titles - Owner
            'إدارة العملاء' => 'client_management',
            'التحليلات' => 'analytics_section',
            'الدعم والإعدادات' => 'support_settings',
        ];
        
        // البحث عن المفتاح في الخريطة
        $key = $nameMap[$name] ?? null;
        
        if ($key) {
            // استخدام دالة الترجمة
            return __('sidebar.' . $key);
        }
        
        // إذا لم يتم العثور على ترجمة، إرجاع الاسم الأصلي
        return $name;
    }
}
