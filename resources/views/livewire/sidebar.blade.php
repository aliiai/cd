<div style="height: 100vh;"
    class="bg-white dark:bg-gray-800 h-full text-gray-800 dark:text-gray-200 transition-all duration-300 ease-in-out shadow-lg {{ app()->getLocale() === 'ar' ? 'border-l' : 'border-r' }} border-gray-200 dark:border-gray-700 flex flex-col {{ $isOpen ? 'w-72 lg:w-80' : 'w-20' }}"
>
    <!-- Sidebar Header with Logo -->
    <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800">
        <!-- Logo/Title (يظهر فقط عندما يكون مفتوح) -->
        @if($isOpen)
        <div class="flex items-center space-x-2 space-x-reverse flex-1 min-w-0">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-20 sm:w-24 h-auto">
        </div>
        @else
        <!-- Logo Icon Only (عندما يكون Sidebar مطوياً) -->
        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center mx-auto flex-shrink-0">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain p-1">
        </div>
        @endif
        
        <!-- Toggle Button (مخفي على الهاتف، يظهر على الشاشات الكبيرة) -->
        <button 
            wire:click="toggle"
            class="hidden lg:flex p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 flex-shrink-0 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100"
            title="{{ $isOpen ? 'طي القائمة الجانبية' : 'توسيع القائمة الجانبية' }}"
        >
            <svg 
                class="w-5 h-5 transition-transform duration-300 {{ $isOpen ? 'rotate-0' : 'rotate-180' }}"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>
        
        <!-- Close Button (يظهر فقط على الهاتف) -->
        <button 
            onclick="if(typeof window.closeMobileSidebar === 'function') { window.closeMobileSidebar(); } else { console.error('closeMobileSidebar function not found'); }"
            class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 flex-shrink-0 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100"
            title="إغلاق القائمة"
        >
            <svg 
                class="w-5 h-5"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 p-3 sm:p-4 overflow-y-auto" x-data="{ openDropdowns: {} }">
        <ul class="space-y-1 sm:space-y-2">
            @foreach($links as $link)
                @php
                    // التحقق من نوع العنصر (section أو link)
                    $isSection = isset($link['type']) && $link['type'] === 'section';
                    
                    // التحقق من أن الرابط نشط (active) - فقط إذا كان يحتوي على route
                    $isActive = false;
                    if (!$isSection && isset($link['route'])) {
                        $isActive = request()->routeIs($link['route']) || request()->routeIs($link['route'] . '.*');
                    }
                    
                    // التحقق من أن أي رابط فرعي نشط (للـ Dropdown)
                    $hasActiveChild = false;
                    if (!$isSection && isset($link['type']) && $link['type'] === 'dropdown' && isset($link['children'])) {
                        foreach ($link['children'] as $child) {
                            if (isset($child['route']) && (request()->routeIs($child['route']) || request()->routeIs($child['route'] . '.*'))) {
                                $hasActiveChild = true;
                                $isActive = true;
                                break;
                            }
                        }
                    }
                @endphp
                
                @if($isSection)
                    <!-- Section Title -->
                    @if($isOpen)
                    <li class="mt-4 mb-2 first:mt-0">
                        <h3 class="px-3 py-2 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ $link['title'] }}
                        </h3>
                    </li>
                    @endif
                @else
                <li>
                    @if(isset($link['type']) && $link['type'] === 'dropdown' && isset($link['children']))
                        <!-- Dropdown Menu Item -->
                        <div x-data="{ isOpen: {{ $hasActiveChild ? 'true' : 'false' }} }">
                            <button 
                                @click="isOpen = !isOpen"
                                class="w-full flex items-center justify-between px-2 sm:px-3 py-2 sm:py-2.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 group {{ $isActive ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100' }}"
                                title="{{ $link['name'] }}"
                            >
                                <div class="flex items-center">
                                    <!-- Icon -->
                                    <svg 
                                        class="w-5 h-5 flex-shrink-0 transition-all duration-200 {{ $isActive ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" 
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}" />
                                    </svg>
                                    <!-- Link Text (يظهر فقط عندما يكون مفتوح) -->
                                    @if($isOpen)
                                    <span class="mr-3 font-medium whitespace-nowrap">
                                        {{ $link['name'] }}
                                    </span>
                                    @endif
                                </div>
                                <!-- Dropdown Arrow (يظهر فقط عندما يكون مفتوح) -->
                                @if($isOpen)
                                <svg 
                                    class="w-4 h-4 transition-transform duration-200 {{ $isActive ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 dark:text-gray-500' }}"
                                    :class="{ 'rotate-90': isOpen }"
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                                @endif
                            </button>
                            
                            <!-- Dropdown Children (يظهر فقط عندما يكون Sidebar مفتوح) -->
                            @if($isOpen)
                            <ul 
                                x-show="isOpen"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform -translate-y-2"
                                class="mt-1 space-y-1 mr-6"
                            >
                                @foreach($link['children'] as $child)
                                    @php
                                        $isChildActive = request()->routeIs($child['route']) || request()->routeIs($child['route'] . '.*');
                                    @endphp
                                    <li>
                                        <a 
                                            href="{{ route($child['route']) }}" 
                                            onclick="if(window.innerWidth < 1024 && typeof window.closeMobileSidebar === 'function') { window.closeMobileSidebar(); }"
                                            class="flex items-center px-2 sm:px-3 py-1.5 sm:py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 group {{ $isChildActive ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 font-medium' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100' }}"
                                            title="{{ $child['name'] }}"
                                        >
                                            <!-- Child Icon -->
                                            <svg 
                                                class="w-4 h-4 flex-shrink-0 transition-all duration-200 {{ $isChildActive ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}" 
                                                fill="none" 
                                                stroke="currentColor" 
                                                viewBox="0 0 24 24"
                                            >
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $child['icon'] }}" />
                                            </svg>
                                            <span class="mr-2 whitespace-nowrap">{{ $child['name'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    @else
                        <!-- Regular Link Item -->
                        <a 
                            href="{{ route($link['route']) }}" 
                            onclick="if(window.innerWidth < 1024 && typeof window.closeMobileSidebar === 'function') { window.closeMobileSidebar(); }"
                            class="flex items-center px-2 sm:px-3 py-2 sm:py-2.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 group {{ $isActive ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100' }}"
                            title="{{ $link['name'] }}"
                        >
                            <!-- Icon -->
                            <svg 
                                class="w-5 h-5 flex-shrink-0 transition-all duration-200 {{ $isActive ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" 
                                fill="none" 
                                stroke="currentColor" 
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}" />
                            </svg>
                            <!-- Link Text (يظهر فقط عندما يكون مفتوح) -->
                            @if($isOpen)
                            <span class="mr-3 font-medium whitespace-nowrap">
                                {{ $link['name'] }}
                            </span>
                            @endif
                        </a>
                    @endif
                @endif
                </li>
            @endforeach
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-3 sm:p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button 
                type="submit" 
                class="w-full flex items-center justify-center px-2 sm:px-3 py-2 sm:py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 transition-all duration-200 group"
                title="{{ __('sidebar.logout') }}"
            >
                <svg 
                    class="w-5 h-5 flex-shrink-0 transition-transform duration-200" 
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                @if($isOpen)
                <span class="mr-2">{{ __('sidebar.logout') }}</span>
                @endif
            </button>
        </form>
    </div>
</div>
