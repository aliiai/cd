<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>لوحة تحكم المالك - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts - Arabic Support -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased h-screen overflow-hidden bg-gray-50 dark:bg-gray-900" style="font-family: 'Cairo', sans-serif;">
    <div class="flex h-full">
        <!-- Sidebar -->
        <aside class="flex-shrink-0 transition-all duration-300 h-full fixed right-0 top-0 z-30">
            @livewire('sidebar')
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col transition-all duration-300" id="main-content">
            <!-- Header -->
            <header class="sticky top-0 z-20">
                <x-header />
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6 overflow-y-auto bg-gray-50 dark:bg-gray-900">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // تحديث margin للمحتوى الرئيسي بناءً على حالة Sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('[wire\\:click="toggle"]')?.closest('div');
            const mainContent = document.getElementById('main-content');
            
            function updateMargin() {
                const sidebarWidth = sidebar?.offsetWidth || 256;
                if (mainContent) {
                    mainContent.style.marginRight = sidebarWidth + 'px';
                }
            }
            
            // تحديث عند التحميل
            updateMargin();
            
            // تحديث عند تغيير حجم النافذة
            window.addEventListener('resize', updateMargin);
            
            // مراقبة تغييرات Livewire
            Livewire.hook('message.processed', () => {
                setTimeout(updateMargin, 100);
            });
        });
    </script>

    @livewireScripts
    @stack('scripts')
    
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
