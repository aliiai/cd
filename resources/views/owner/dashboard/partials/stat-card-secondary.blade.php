{{-- 
    Component: بطاقة إحصائية ثانوية (Secondary Stat Card)
    Usage: @include('owner.dashboard.partials.stat-card-secondary', [...])
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

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-r-4 {{ $borderColor }}">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">{{ $title }}</h3>
        <div class="bg-primary-100 dark:bg-primary-900/30 rounded-lg p-2">
            {!! $icon !!}
        </div>
    </div>
    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">{!! $value !!}</p>
    @if($subtitle)
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $subtitle }}</p>
    @endif
    @if($progress)
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-2">
            <div class="bg-gradient-to-r from-primary-500 to-secondary-500 h-2 rounded-full" style="width: {{ $progress }}%"></div>
        </div>
    @endif
    @if($footer)
        <div class="mt-2">
            {!! $footer !!}
        </div>
    @endif
</div>

