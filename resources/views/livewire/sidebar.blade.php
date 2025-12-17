<div style="height: 100vh;"
    class="bg-gradient-to-b from-gray-800 to-gray-900 h-full text-white transition-all duration-300 ease-in-out shadow-xl flex flex-col {{ $isOpen ? 'w-64' : 'w-20' }}"
>
    <!-- Sidebar Header -->
    <div class="p-4 border-b border-gray-700 flex items-center justify-between bg-gray-800/50">
        <!-- Logo/Title (يظهر فقط عندما يكون مفتوح) -->
        @if($isOpen)
<div class="flex flex-col items-center justify-center">
<img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10لا mb-2">
<div class="flex-1">
    <h2 class="text-xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">
        {{ Auth::user()->hasRole('admin') ? 'Admin Panel' : 'Owner Panel' }}
    </h2>
</div>
</div>
@endif

        
        <!-- Toggle Button -->
        <button 
            wire:click="toggle"
            class="p-2 rounded-lg hover:bg-gray-700 transition-all duration-200 flex-shrink-0 hover:scale-110 active:scale-95"
            title="{{ $isOpen ? 'Collapse Sidebar' : 'Expand Sidebar' }}"
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
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 p-4 overflow-y-auto custom-scrollbar" x-data="{ openDropdowns: {} }">
        <ul class="space-y-2">
            @foreach($links as $link)
                @php
                    // التحقق من أن الرابط نشط (active)
                    $isActive = request()->routeIs($link['route']) || request()->routeIs($link['route'] . '.*');
                    
                    // التحقق من أن أي رابط فرعي نشط (للـ Dropdown)
                    $hasActiveChild = false;
                    if (isset($link['type']) && $link['type'] === 'dropdown' && isset($link['children'])) {
                        foreach ($link['children'] as $child) {
                            if (request()->routeIs($child['route']) || request()->routeIs($child['route'] . '.*')) {
                                $hasActiveChild = true;
                                $isActive = true;
                                break;
                            }
                        }
                    }
                @endphp
                <li>
                    @if(isset($link['type']) && $link['type'] === 'dropdown' && isset($link['children']))
                        <!-- Dropdown Menu Item -->
                        <div x-data="{ isOpen: {{ $hasActiveChild ? 'true' : 'false' }} }">
                            <button 
                                @click="isOpen = !isOpen"
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-300 rounded-xl hover:bg-gray-700/50 hover:text-white hover:shadow-lg transition-all duration-200 group {{ $isActive ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg' : '' }}"
                                title="{{ $link['name'] }}"
                            >
                                <div class="flex items-center">
                                    <!-- Icon -->
                                    <svg 
                                        class="w-5 h-5 flex-shrink-0 transition-all duration-200 group-hover:scale-110 {{ $isActive ? 'text-white' : 'text-gray-400' }}" 
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}" />
                                    </svg>
                                    <!-- Link Text (يظهر فقط عندما يكون مفتوح) -->
                                    @if($isOpen)
                                    <span class="ml-3 font-medium">
                                        {{ $link['name'] }}
                                    </span>
                                    @endif
                                </div>
                                <!-- Dropdown Arrow (يظهر فقط عندما يكون مفتوح) -->
                                @if($isOpen)
                                <svg 
                                    class="w-4 h-4 transition-transform duration-200 {{ $isActive ? 'text-white' : 'text-gray-400' }}"
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
                                class="mt-2 space-y-1 mr-4"
                            >
                                @foreach($link['children'] as $child)
                                    @php
                                        $isChildActive = request()->routeIs($child['route']) || request()->routeIs($child['route'] . '.*');
                                    @endphp
                                    <li>
                                        <a 
                                            href="{{ route($child['route']) }}" 
                                            class="flex items-center px-4 py-2 text-sm text-gray-400 rounded-lg hover:bg-gray-700/50 hover:text-white transition-all duration-200 group {{ $isChildActive ? 'bg-gray-700/70 text-white font-medium' : '' }}"
                                            title="{{ $child['name'] }}"
                                        >
                                            <!-- Child Icon -->
                                            <svg 
                                                class="w-4 h-4 flex-shrink-0 transition-all duration-200 {{ $isChildActive ? 'text-white' : 'text-gray-500' }}" 
                                                fill="none" 
                                                stroke="currentColor" 
                                                viewBox="0 0 24 24"
                                            >
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $child['icon'] }}" />
                                            </svg>
                                            <span class="ml-3">{{ $child['name'] }}</span>
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
                            class="flex items-center px-4 py-3 text-gray-300 rounded-xl hover:bg-gray-700/50 hover:text-white hover:shadow-lg transition-all duration-200 group {{ $isActive ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg scale-105' : '' }}"
                            title="{{ $link['name'] }}"
                        >
                            <!-- Icon -->
                            <svg 
                                class="w-5 h-5 flex-shrink-0 transition-all duration-200 group-hover:scale-110 {{ $isActive ? 'text-white' : 'text-gray-400' }}" 
                                fill="none" 
                                stroke="currentColor" 
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}" />
                            </svg>
                            <!-- Link Text (يظهر فقط عندما يكون مفتوح) -->
                            @if($isOpen)
                            <span class="ml-3 font-medium">
                                {{ $link['name'] }}
                            </span>
                            @endif
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-gray-700 bg-gray-800/50">
        <div class="flex items-center justify-between mb-3">
            <!-- User Info (يظهر فقط عندما يكون مفتوح) -->
            @if($isOpen)
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-300 truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
            </div>
            @endif
            
            <!-- User Avatar (يظهر دائماً) -->
            <div class="flex-shrink-0 {{ $isOpen ? 'ml-2' : 'mx-auto' }}">
                @if(Auth::user()->profile_photo_path)
                    <img 
                        src="{{ Auth::user()->profile_photo_url }}" 
                        alt="{{ Auth::user()->name }}" 
                        class="h-10 w-10 rounded-full border-2 border-gray-600 shadow-md hover:border-blue-500 transition-colors duration-200"
                    >
                @else
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center border-2 border-gray-500 shadow-md hover:border-blue-400 transition-all duration-200 hover:scale-110">
                        <span class="text-sm font-bold text-white">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button 
                type="submit" 
                class="w-full flex items-center justify-center px-4 py-2 text-sm text-gray-300 rounded-xl hover:bg-red-600/20 hover:text-red-400 hover:border-red-500 border border-transparent transition-all duration-200 group"
                title="Logout"
            >
                <svg 
                    class="w-5 h-5 flex-shrink-0 transition-transform duration-200 group-hover:scale-110 group-hover:rotate-12" 
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                @if($isOpen)
                <span class="ml-3">Logout</span>
                @endif
            </button>
        </form>
    </div>
</div>
