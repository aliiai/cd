import './bootstrap';
import Alpine from 'alpinejs';

// تعيين Alpine على window للوصول إليه من أي مكان
// فقط إذا لم يكن موجوداً بالفعل (لتجنب التحميل المزدوج)
if (!window.Alpine) {
    window.Alpine = Alpine;
    
    // بدء Alpine فقط إذا لم يبدأ بالفعل
    // Livewire يقوم بتحميل Alpine.js تلقائياً، لذا لا نبدأه هنا
    // إذا كنت بحاجة لبدء Alpine يدوياً، استخدم:
    // if (document.readyState === 'loading') {
    //     document.addEventListener('DOMContentLoaded', () => Alpine.start());
    // } else {
    //     Alpine.start();
    // }
}
