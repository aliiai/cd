{{-- 
    Component: بطاقة إحصائية رئيسية (Primary Stat Card)
    Usage: @include('owner.dashboard.partials.stat-card-primary', [...])
--}}
@props([
    'title',
    'value',
    'subtitle',
    'gradientFrom',
    'gradientTo',
    'icon',
    'badge' => null,
    'footer' => null
])

<div class="bg-gradient-to-br {{ $gradientFrom }} {{ $gradientTo }} rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl p-4 sm:p-5 md:p-6 text-white transform transition-transform duration-200">
    <div class="flex items-center justify-between mb-3 sm:mb-4 lg:mb-4">
        <div class="bg-white/20 rounded-lg p-2 sm:p-3 lg:p-3">
            {!! str_replace('w-8 h-8', 'w-5 h-5 sm:w-6 sm:h-6 lg:w-8 lg:h-8', $icon) !!}
        </div>
        @if($badge)
            <span class="text-xs sm:text-sm lg:text-sm font-medium bg-white/20 px-2 py-0.5 sm:px-3 sm:py-1 lg:px-3 lg:py-1 rounded-full">{{ $badge }}</span>
        @endif
    </div>
    <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1">{!! $value !!}</h3>
    <p class="opacity-90 text-xs sm:text-sm lg:text-sm mb-2 sm:mb-3 lg:mb-3">{{ $subtitle }}</p>
    @if($footer)
        <div class="mt-2 sm:mt-3 lg:mt-3 text-xs sm:text-sm lg:text-sm">
            {!! $footer !!}
        </div>
    @endif
</div>

