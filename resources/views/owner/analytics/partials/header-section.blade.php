{{-- 
    Component: Header Section
    Usage: @include('owner.analytics.partials.header-section', [
        'title' => 'تحليلات حالة التحصيل',
        'description' => 'فهم توزيع المديونيات واتخاذ قرارات أفضل'
    ])
--}}
@props([
    'title',
    'description'
])

<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $title }}</h1>
    <p class="text-lg text-gray-600 dark:text-gray-400">{{ $description }}</p>
</div>

