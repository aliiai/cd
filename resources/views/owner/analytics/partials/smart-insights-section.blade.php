{{-- 
    Component: Smart Insights Section
    Usage: @include('owner.analytics.partials.smart-insights-section', [
        'insights' => $insights
    ])
--}}
@props(['insights'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="bg-gradient-to-r from-primary-500 to-secondary-500 px-6 py-4">
        <h3 class="text-xl font-bold text-white flex items-center">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
            </svg>
            مؤشرات ذكية
        </h3>
    </div>
    <div class="p-6">
        @if(count($insights) > 0)
            <div class="space-y-3 max-h-[400px] overflow-y-auto">
                @foreach($insights as $insight)
                    <div class="bg-gradient-to-r {{ 
                        $insight['type'] === 'success' ? 'from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 border-emerald-200 dark:border-emerald-800' : 
                        ($insight['type'] === 'warning' ? 'from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border-yellow-200 dark:border-yellow-800' : 
                        'from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border-blue-200 dark:border-blue-800')
                    }} rounded-lg border-l-4 {{ 
                        $insight['type'] === 'success' ? 'border-emerald-500 dark:border-emerald-400' : 
                        ($insight['type'] === 'warning' ? 'border-yellow-500 dark:border-yellow-400' : 
                        'border-blue-500 dark:border-blue-400')
                    }} p-4 flex items-start hover:shadow-md transition-all duration-200">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-lg {{ 
                                $insight['type'] === 'success' ? 'bg-emerald-100 dark:bg-emerald-900/40' : 
                                ($insight['type'] === 'warning' ? 'bg-yellow-100 dark:bg-yellow-900/40' : 
                                'bg-blue-100 dark:bg-blue-900/40')
                            }} flex items-center justify-center">
                                <svg class="w-5 h-5 {{ 
                                    $insight['type'] === 'success' ? 'text-emerald-600 dark:text-emerald-400' : 
                                    ($insight['type'] === 'warning' ? 'text-yellow-600 dark:text-yellow-400' : 
                                    'text-blue-600 dark:text-blue-400')
                                }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $insight['icon'] }}"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-3 flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 leading-relaxed">
                                {{ $insight['message'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-gray-500 dark:text-gray-400 font-medium">لا توجد مؤشرات حالياً</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">كل شيء يعمل بشكل طبيعي</p>
            </div>
        @endif
    </div>
</div>

