{{-- 
    Component: بطاقة إحصائية ثانوية (Secondary Stat Card)
    Usage: @include('admin.dashboard.partials.stat-card-secondary', [...])
--}}
@props([
    'title',
    'value',
    'subtitle' => null,
    'borderColor',
    'icon',
    'footer' => null,
    'progress' => null
])

<div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-5 md:p-6 border-r-4 {{ $borderColor }}">
    <div class="flex items-center justify-between mb-3 sm:mb-4 lg:mb-4">
        <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-700 dark:text-gray-300">{{ $title }}</h3>
        <div class="bg-primary-100 dark:bg-primary-900/30 rounded-lg p-1.5 sm:p-2 lg:p-2">
            {!! str_replace('w-6 h-6', 'w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6', $icon) !!}
        </div>
    </div>
    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1 sm:mb-2 lg:mb-2">{!! $value !!}</p>
    @if($subtitle)
        <p class="text-xs sm:text-sm lg:text-sm text-gray-600 dark:text-gray-400 mb-2 sm:mb-3 lg:mb-3">{{ $subtitle }}</p>
    @endif
    @if($progress)
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 sm:h-2 lg:h-2 mb-2">
            <div class="bg-gradient-to-r from-primary-500 to-secondary-500 h-1.5 sm:h-2 lg:h-2 rounded-full" style="width: {{ $progress }}%"></div>
        </div>
    @endif
    @if($footer)
        <div class="mt-2 lg:mt-2 text-xs sm:text-sm lg:text-sm">
            {!! $footer !!}
        </div>
    @endif
</div>

