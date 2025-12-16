<div 
    x-data="{ 
        currentLocale: '{{ $currentLocale }}',
        direction: '{{ $direction }}'
    }"
    class="relative"
>
    <!-- Language Switcher Button -->
    <button 
        type="button"
        wire:click="switchLanguage('{{ $currentLocale === 'ar' ? 'en' : 'ar' }}')"
        class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full transition-colors duration-200 relative"
        :title="currentLocale === 'ar' ? 'Switch to English' : 'التبديل إلى العربية'"
    >
        <!-- Language Icon -->
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
        </svg>
        
        <!-- Current Language Badge -->
        <span class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
            <span x-text="currentLocale === 'ar' ? 'ع' : 'E'"></span>
        </span>
    </button>
</div>

