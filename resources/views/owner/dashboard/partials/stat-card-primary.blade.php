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

<div class="bg-gradient-to-br {{ $gradientFrom }} {{ $gradientTo }} rounded-2xl shadow-xl p-6 text-white transform transition-transform duration-200 ">
    <div class="flex items-center justify-between mb-4">
        <div class="bg-white/20 rounded-lg p-3">
            {!! $icon !!}
        </div>
        @if($badge)
            <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">{{ $badge }}</span>
        @endif
    </div>
    <h3 class="text-3xl font-bold mb-1">{!! $value !!}</h3>
    <p class="opacity-90 text-sm mb-3">{{ $subtitle }}</p>
    @if($footer)
        <div class="mt-3">
            {!! $footer !!}
        </div>
    @endif
</div>

