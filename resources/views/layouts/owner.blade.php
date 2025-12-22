<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('common.owner_panel') }} - {{ config('app.name', 'Laravel') }}</title>

    <!-- Dark Mode Script - Must be before any CSS to prevent flash -->
    <script>
        (function() {
            const stored = localStorage.getItem('dark_mode_preference');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            let shouldBeDark = false;
            
            if (stored === 'dark') {
                shouldBeDark = true;
            } else if (stored === 'light') {
                shouldBeDark = false;
            } else {
                // system preference
                shouldBeDark = prefersDark;
            }
            
            if (shouldBeDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <!-- Fonts - Arabic Support -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <!-- Sweet Alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body class="font-sans antialiased h-screen overflow-hidden bg-gray-50 dark:bg-gray-900" style="font-family: 'Cairo', sans-serif;">
    <div class="flex h-full">
        <!-- Sidebar -->
        <aside class="flex-shrink-0 transition-all duration-300 h-full fixed {{ app()->getLocale() === 'ar' ? 'right-0' : 'left-0' }} top-0 z-30 hidden lg:block">
            @livewire('sidebar')
        </aside>

        <!-- Mobile Sidebar Overlay -->
        <div id="mobile-sidebar-overlay" class="fixed inset-0 bg-black/50 z-20 lg:hidden hidden" onclick="if(typeof window.closeMobileSidebar === 'function') { window.closeMobileSidebar(); }"></div>

        <!-- Mobile Sidebar -->
        <aside class="flex-shrink-0 transition-all duration-300 h-full fixed {{ app()->getLocale() === 'ar' ? 'right-0' : 'left-0' }} top-0 z-30 lg:hidden transform {{ app()->getLocale() === 'ar' ? 'translate-x-full' : '-translate-x-full' }}" id="mobile-sidebar">
            @livewire('sidebar')
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col transition-all duration-300 w-full lg:w-auto" id="main-content">
            <!-- Header -->
            <header class="sticky top-0 z-20">
                <x-header />
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-3 sm:p-4 md:p-6 overflow-y-auto bg-gray-50 dark:bg-gray-900">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Mobile Sidebar Functions - Make them globally available immediately
        window.openMobileSidebar = function() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('mobile-sidebar-overlay');
            if (sidebar && overlay) {
                const isRTL = document.documentElement.dir === 'rtl';
                if (isRTL) {
                    sidebar.classList.remove('translate-x-full');
                } else {
                    sidebar.classList.remove('-translate-x-full');
                }
                sidebar.classList.add('translate-x-0');
                overlay.classList.remove('hidden');
                // منع التمرير في body عند فتح Sidebar
                document.body.style.overflow = 'hidden';
            }
        };

        window.closeMobileSidebar = function() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('mobile-sidebar-overlay');
            const isRTL = document.documentElement.dir === 'rtl';
            if (sidebar && overlay) {
                sidebar.classList.remove('translate-x-0');
                if (isRTL) {
                    sidebar.classList.add('translate-x-full');
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
                overlay.classList.add('hidden');
                // إعادة التمرير في body عند إغلاق Sidebar
                document.body.style.overflow = '';
            }
        };

        // تحديث margin للمحتوى الرئيسي بناءً على حالة Sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('aside.hidden.lg\\:block');
            const mainContent = document.getElementById('main-content');
            
            function updateMargin() {
                // على الشاشات الكبيرة فقط
                if (window.innerWidth >= 1024) {
                    const sidebarWidth = sidebar?.offsetWidth || 320;
                    const isRTL = document.documentElement.dir === 'rtl';
                    if (mainContent) {
                        if (isRTL) {
                            mainContent.style.marginRight = sidebarWidth + 'px';
                            mainContent.style.marginLeft = '0';
                        } else {
                            mainContent.style.marginLeft = sidebarWidth + 'px';
                            mainContent.style.marginRight = '0';
                        }
                    }
                } else {
                    // على الشاشات الصغيرة، لا margin
                    if (mainContent) {
                        mainContent.style.marginRight = '0';
                        mainContent.style.marginLeft = '0';
                    }
                }
            }
            
            // تحديث عند التحميل
            updateMargin();
            
            // تحديث عند تغيير حجم النافذة
            window.addEventListener('resize', updateMargin);
            
            // مراقبة تغييرات Livewire
            if (typeof Livewire !== 'undefined') {
                Livewire.hook('message.processed', () => {
                    setTimeout(updateMargin, 100);
                });
            }
        });

        // Functions are already exposed globally above
    </script>

    @livewireScripts
    @stack('scripts')
    
    <!-- Sweet Alert 2 Helper Functions -->
    <script>
        // ========== Sweet Alert Helper Functions ==========
        
        // دالة تأكيد مع Sweet Alert
        window.swalConfirm = function(options) {
            const isRTL = document.documentElement.dir === 'rtl';
            const defaultOptions = {
                title: '{{ __('common.are_you_sure') }}',
                text: '{{ __('common.do_you_want_to_continue') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#5C70E0',
                cancelButtonColor: '#6B7280',
                confirmButtonText: '{{ __('common.confirm_button_text') }}',
                cancelButtonText: '{{ __('common.cancel_button_text') }}',
                reverseButtons: isRTL,
                customClass: {
                    popup: isRTL ? 'rtl' : 'ltr',
                    title: isRTL ? 'text-right' : 'text-left',
                    content: isRTL ? 'text-right' : 'text-left',
                }
            };
            
            return Swal.fire(Object.assign(defaultOptions, options));
        };
        
        // دالة رسالة نجاح
        window.swalSuccess = function(message, title = '{{ __('common.success_message') }}') {
            const isRTL = document.documentElement.dir === 'rtl';
            return Swal.fire({
                title: title,
                text: message,
                icon: 'success',
                confirmButtonColor: '#10B981',
                confirmButtonText: '{{ __('common.ok') }}',
                customClass: {
                    popup: isRTL ? 'rtl' : 'ltr',
                    title: isRTL ? 'text-right' : 'text-left',
                    content: isRTL ? 'text-right' : 'text-left',
                }
            });
        };
        
        // دالة رسالة خطأ
        window.swalError = function(message, title = '{{ __('common.error_message') }}') {
            const isRTL = document.documentElement.dir === 'rtl';
            return Swal.fire({
                title: title,
                text: message,
                icon: 'error',
                confirmButtonColor: '#EF4444',
                confirmButtonText: '{{ __('common.ok') }}',
                customClass: {
                    popup: isRTL ? 'rtl' : 'ltr',
                    title: isRTL ? 'text-right' : 'text-left',
                    content: isRTL ? 'text-right' : 'text-left',
                }
            });
        };
        
        // دالة رسالة معلومات
        window.swalInfo = function(message, title = '{{ __('common.info_message') }}') {
            const isRTL = document.documentElement.dir === 'rtl';
            return Swal.fire({
                title: title,
                text: message,
                icon: 'info',
                confirmButtonColor: '#5C70E0',
                confirmButtonText: '{{ __('common.ok') }}',
                customClass: {
                    popup: isRTL ? 'rtl' : 'ltr',
                    title: isRTL ? 'text-right' : 'text-left',
                    content: isRTL ? 'text-right' : 'text-left',
                }
            });
        };
        
        // استبدال confirm() الافتراضي
        window.originalConfirm = window.confirm;
        window.confirm = function(message) {
            return swalConfirm({
                text: message
            }).then((result) => {
                return result.isConfirmed;
            });
        };
        
        // استبدال alert() الافتراضي
        window.originalAlert = window.alert;
        window.alert = function(message) {
            return swalInfo(message);
        };
        
        // ========== Form Submission Handlers ==========
        document.addEventListener('DOMContentLoaded', function() {
            // Handle delete forms
            document.addEventListener('submit', function(e) {
                const form = e.target;
                
                // Delete Forms
                if (form.classList.contains('delete-form')) {
                    e.preventDefault();
                    const action = form.action;
                    const deleteText = form.dataset.deleteText || '{{ __('common.delete') }}';
                    const itemName = form.dataset.itemName || '{{ __('common.item') }}';
                    
                    swalConfirm({
                        text: '{{ __('common.delete_item_confirmation') }}'.replace(':item', itemName),
                        confirmButtonColor: '#EF4444',
                        icon: 'warning',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const formData = new FormData(form);
                            const method = form.querySelector('input[name="_method"]')?.value || 'POST';
                            
                            fetch(action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            })
                            .then(response => {
                                if (response.ok) {
                                    return response.json().catch(() => ({ success: true }));
                                }
                                return response.json().then(data => ({ success: false, message: data.message || 'حدث خطأ' }));
                            })
                            .then(data => {
                                if (data.success) {
                                    swalSuccess(data.message || '{{ __('common.delete_success') }}').then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    swalError(data.message || '{{ __('common.something_went_wrong') }}');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                swalError('{{ __('common.something_went_wrong') }}');
                            });
                        }
                    });
                }
                
                // Close Ticket Forms
                if (form.classList.contains('close-ticket-form')) {
                    e.preventDefault();
                    const action = form.action;
                    
                    swalConfirm({
                        text: '{{ __('common.close_ticket_confirmation') }}',
                        confirmButtonColor: '#EF4444',
                        icon: 'warning',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const formData = new FormData(form);
                            fetch(action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            })
                            .then(response => {
                                if (response.ok) {
                                    return response.json().catch(() => ({ success: true }));
                                }
                                return response.json().then(data => ({ success: false, message: data.message || 'حدث خطأ' }));
                            })
                            .then(data => {
                                if (data.success) {
                                    swalSuccess(data.message || '{{ __('common.close_ticket_success') }}').then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    swalError(data.message || '{{ __('common.something_went_wrong') }}');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                swalError('{{ __('common.something_went_wrong') }}');
                            });
                        }
                    });
                }
            });
        });
    </script>
    
    <!-- Dark Mode Script -->
    <script>
        // Dark Mode Management
        (function() {
            // Get preference from session or localStorage
            function getDarkModePreference() {
                // Check if there's a preference in localStorage
                const stored = localStorage.getItem('dark_mode_preference');
                if (stored) return stored;
                
                // Check system preference
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    return 'dark';
                }
                return 'light';
            }
            
            // Apply dark mode
            function applyDarkMode(mode) {
                const html = document.documentElement;
                if (mode === 'dark' || (mode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    html.classList.add('dark');
                } else {
                    html.classList.remove('dark');
                }
            }
            
            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                // Check for server-side preference (from session)
                const serverPreference = @json(session('dark_mode_preference', null));
                let preference = serverPreference || getDarkModePreference();
                
                // If server preference is 'system', use system preference
                if (preference === 'system') {
                    preference = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                }
                
                applyDarkMode(preference);
                
                // Update toggle button icons
                updateToggleIcons();
                
                // Listen for system preference changes
                if (window.matchMedia) {
                    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                        const currentPreference = localStorage.getItem('dark_mode_preference') || 'system';
                        if (currentPreference === 'system') {
                            applyDarkMode('system');
                            updateToggleIcons();
                        }
                    });
                }
            });
            
            // Toggle dark mode
            function toggleDarkMode() {
                const html = document.documentElement;
                const isDark = html.classList.contains('dark');
                
                if (isDark) {
                    html.classList.remove('dark');
                    localStorage.setItem('dark_mode_preference', 'light');
                } else {
                    html.classList.add('dark');
                    localStorage.setItem('dark_mode_preference', 'dark');
                }
                
                updateToggleIcons();
            }
            
            // Update toggle button icons
            function updateToggleIcons() {
                const html = document.documentElement;
                const isDark = html.classList.contains('dark');
                const sunIcon = document.getElementById('sunIcon');
                const moonIcon = document.getElementById('moonIcon');
                
                if (sunIcon && moonIcon) {
                    if (isDark) {
                        sunIcon.classList.remove('hidden');
                        sunIcon.classList.add('block');
                        moonIcon.classList.remove('block');
                        moonIcon.classList.add('hidden');
                    } else {
                        sunIcon.classList.remove('block');
                        sunIcon.classList.add('hidden');
                        moonIcon.classList.remove('hidden');
                        moonIcon.classList.add('block');
                    }
                }
            }
            
            // Attach toggle button event
            document.addEventListener('DOMContentLoaded', function() {
                const toggleButton = document.getElementById('darkModeToggle');
                if (toggleButton) {
                    toggleButton.addEventListener('click', toggleDarkMode);
                }
            });
            
            // Make functions globally available
            window.toggleDarkMode = toggleDarkMode;
            window.applyDarkMode = applyDarkMode;
        })();
    </script>
</body>
</html>
