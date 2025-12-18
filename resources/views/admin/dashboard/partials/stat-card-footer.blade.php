{{-- 
    Component: Footer للبطاقات الإحصائية
    Usage: @include('admin.dashboard.partials.stat-card-footer', ['items' => [...]])
--}}
@props(['items'])

<div class="flex items-center gap-4 text-sm">
    @foreach($items as $item)
        <div class="flex items-center">
            <div class="w-2 h-2 bg-{{ $item['color'] }} rounded-full ml-2"></div>
            <span>{{ $item['label'] }}: {{ $item['value'] }}</span>
        </div>
    @endforeach
</div>

