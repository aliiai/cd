@extends('layouts.owner')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-4 sm:py-6 md:py-8">
    <div class="w-full mx-auto px-2 sm:px-4 md:px-6 lg:px-8">
        {{-- Page Header --}}
        <div class="mb-4 sm:mb-6 md:mb-8">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-1 sm:mb-2">{{ __('settings.title') }}</h1>
            <p class="text-sm sm:text-base lg:text-lg text-gray-600 dark:text-gray-400">{{ __('settings.description') }}</p>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
        <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-gradient-to-r from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 border-l-4 border-emerald-500 dark:border-emerald-400 rounded-lg flex items-center shadow-md">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-emerald-600 dark:text-emerald-400 ml-2 sm:ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-xs sm:text-sm font-medium text-emerald-800 dark:text-emerald-300">{{ session('success') }}</p>
        </div>
        @endif

        {{-- Tabs Navigation --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 mb-4 sm:mb-6 overflow-hidden">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex space-x-4 sm:space-x-6 md:space-x-8 space-x-reverse px-3 sm:px-4 md:px-6 overflow-x-auto" aria-label="Tabs">
                    <button onclick="showTab('profile')" id="tab-profile" class="tab-button active py-3 sm:py-4 px-1 border-b-2 border-primary-500 font-medium text-xs sm:text-sm text-primary-600 dark:text-primary-400 whitespace-nowrap">
                        {{ __('settings.profile_tab') }}
                    </button>
                    <button onclick="showTab('password')" id="tab-password" class="tab-button py-3 sm:py-4 px-1 border-b-2 border-transparent font-medium text-xs sm:text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 whitespace-nowrap">
                        {{ __('settings.password_tab') }}
                    </button>
                    <button onclick="showTab('security')" id="tab-security" class="tab-button py-3 sm:py-4 px-1 border-b-2 border-transparent font-medium text-xs sm:text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 whitespace-nowrap">
                        {{ __('settings.security_tab') }}
                    </button>
                </nav>
            </div>

            {{-- Tab Content --}}
            <div class="p-4 sm:p-5 md:p-6">
                {{-- Profile Information Tab --}}
                <div id="tab-content-profile" class="tab-content">
                    <form method="POST" action="{{ route('owner.settings.profile') }}" enctype="multipart/form-data" x-data="{ photoPreview: '{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : '' }}' }">
                        @csrf
                        @method('POST')

                        <div class="space-y-6">
                            {{-- Profile Photo --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('settings.profile_photo') }} <span class="text-gray-400 dark:text-gray-500 text-xs">{{ __('settings.profile_photo_optional') }}</span>
                                </label>
                                <div class="flex items-center space-x-4 space-x-reverse">
                                    {{-- Current Photo --}}
                                    <div class="flex-shrink-0">
                                        <img x-show="photoPreview" :src="photoPreview" alt="Profile Photo" class="h-20 w-20 rounded-full object-cover border-2 border-primary-500 dark:border-primary-400 shadow-md">
                                        <div x-show="!photoPreview" class="h-20 w-20 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center border-2 border-dashed border-gray-300 dark:border-gray-600">
                                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    {{-- Upload Button --}}
                                    <div>
                                        <label for="photo" class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors">
                                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                            </svg>
                                            {{ __('settings.choose_photo') }}
                                        </label>
                                        <input type="file" id="photo" name="photo" accept="image/*" class="hidden" @change="photoPreview = URL.createObjectURL($event.target.files[0])">
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('settings.photo_formats') }}</p>
                                    </div>
                                </div>
                                @error('photo', 'updateProfileInformation')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Full Name --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('settings.full_name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required
                                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-400 dark:focus:border-primary-400 @error('name', 'updateProfileInformation') border-red-500 dark:border-red-400 @enderror">
                                @error('name', 'updateProfileInformation')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('settings.email') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required
                                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-400 dark:focus:border-primary-400 @error('email', 'updateProfileInformation') border-red-500 dark:border-red-400 @enderror">
                                @error('email', 'updateProfileInformation')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('settings.phone') }} <span class="text-gray-400 dark:text-gray-500 text-xs">{{ __('settings.phone_optional') }}</span>
                                </label>
                                <input type="tel" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone) }}" 
                                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-400 dark:focus:border-primary-400 @error('phone', 'updateProfileInformation') border-red-500 dark:border-red-400 @enderror"
                                       placeholder="{{ __('settings.phone_placeholder') }}">
                                @error('phone', 'updateProfileInformation')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transform hover:-translate-y-0.5">
                                    {{ __('settings.save_changes') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Password Tab --}}
                <div id="tab-content-password" class="tab-content hidden">
                    <form method="POST" action="{{ route('owner.settings.password') }}">
                        @csrf
                        @method('POST')

                        <div class="space-y-6">
                            {{-- Current Password --}}
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('settings.current_password') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       id="current_password" 
                                       name="current_password" 
                                       required
                                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-400 dark:focus:border-primary-400 @error('current_password', 'updatePassword') border-red-500 dark:border-red-400 @enderror">
                                @error('current_password', 'updatePassword')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- New Password --}}
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('settings.new_password') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       required
                                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-400 dark:focus:border-primary-400 @error('password', 'updatePassword') border-red-500 dark:border-red-400 @enderror">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('settings.password_requirements') }}</p>
                                @error('password', 'updatePassword')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('settings.confirm_new_password') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required
                                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-400 dark:focus:border-primary-400 @error('password_confirmation', 'updatePassword') border-red-500 dark:border-red-400 @enderror">
                                @error('password_confirmation', 'updatePassword')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transform hover:-translate-y-0.5">
                                    {{ __('settings.update_password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Security Tab --}}
                <div id="tab-content-security" class="tab-content hidden">
                    <div class="space-y-6">
                        {{-- Active Sessions --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('settings.active_sessions') }}</h3>
                            
                            @if($sessions->isEmpty())
                                <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg p-4 text-center">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('settings.no_active_sessions') }}</p>
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach($sessions as $session)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg {{ $session->is_current ? 'ring-2 ring-primary-500 dark:ring-primary-400' : '' }}">
                                        <div class="flex items-center space-x-3 space-x-reverse">
                                            <div class="flex-shrink-0">
                                                @if($session->is_current)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-primary-300 border border-primary-200 dark:border-primary-800">
                                                        {{ __('settings.current_session') }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                                        {{ __('settings.other_session') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $session->user_agent }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $session->ip_address }} • {{ __('settings.last_activity') }}: {{ $session->last_activity->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                {{-- Logout Other Sessions --}}
                                <div class="mt-6 p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border-l-4 border-yellow-500 dark:border-yellow-400 rounded-lg">
                                    <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-300 mb-2">{{ __('settings.logout_other_sessions') }}</h4>
                                    <p class="text-xs text-yellow-700 dark:text-yellow-400 mb-4">{{ __('settings.logout_other_sessions_description') }}</p>
                                    
                                    <form method="POST" action="{{ route('owner.settings.logout-other-sessions') }}">
                                        @csrf
                                        @method('POST')
                                        
                                        <div class="mb-4">
                                            <label for="logout_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                {{ __('settings.password_required') }} <span class="text-red-500">*</span>
                                            </label>
                                            <input type="password" 
                                                   id="logout_password" 
                                                   name="password" 
                                                   required
                                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-400 dark:focus:border-primary-400 @error('password') border-red-500 dark:border-red-400 @enderror">
                                            @error('password')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transform hover:-translate-y-0.5">
                                            {{ __('settings.logout_other_sessions') }}
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        {{-- Last Login Info --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('settings.login_info') }}</h3>
                            <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('settings.registration_date') }}</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->created_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('settings.last_account_update') }}</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->updated_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching function
        window.showTab = function(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-primary-500', 'text-primary-600', 'dark:text-primary-400');
                button.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            });
            
            // Show selected tab content
            const contentElement = document.getElementById('tab-content-' + tabName);
            if (contentElement) {
                contentElement.classList.remove('hidden');
            }
            
            // Add active class to selected tab
            const selectedTab = document.getElementById('tab-' + tabName);
            if (selectedTab) {
                selectedTab.classList.add('active', 'border-primary-500', 'text-primary-600', 'dark:text-primary-400');
                selectedTab.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            }
        };
    });
</script>
@endpush
@endsection
