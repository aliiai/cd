{{-- 
    Component: التنبيهات الذكية
    Usage: @include('owner.dashboard.partials.smart-alerts', [
        'alerts' => $alerts
    ])
--}}
@props(['alerts'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">التنبيهات الذكية</h3>
        <button class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" title="خيارات">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
            </svg>
        </button>
    </div>
    @if(count($alerts) > 0)
        <div class="space-y-3 max-h-[400px] overflow-y-auto">
            @foreach($alerts as $alert)
            <div class="bg-gradient-to-r {{ 
                $alert['type'] === 'danger' ? 'from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border-red-200 dark:border-red-800' : 
                ($alert['type'] === 'warning' ? 'from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border-yellow-200 dark:border-yellow-800' : 
                ($alert['type'] === 'success' ? 'from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border-green-200 dark:border-green-800' : 
                'from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border-blue-200 dark:border-blue-800'))
            }} rounded-lg border-l-4 {{ 
                $alert['type'] === 'danger' ? 'border-red-500 dark:border-red-400' : 
                ($alert['type'] === 'warning' ? 'border-yellow-500 dark:border-yellow-400' : 
                ($alert['type'] === 'success' ? 'border-green-500 dark:border-green-400' : 
                'border-blue-500 dark:border-blue-400'))
            }} p-4 flex items-start hover:shadow-md transition-all duration-200">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-lg {{ 
                        $alert['type'] === 'danger' ? 'bg-red-100 dark:bg-red-900/40' : 
                        ($alert['type'] === 'warning' ? 'bg-yellow-100 dark:bg-yellow-900/40' : 
                        ($alert['type'] === 'success' ? 'bg-green-100 dark:bg-green-900/40' : 
                        'bg-blue-100 dark:bg-blue-900/40'))
                    }} flex items-center justify-center">
                        <svg class="w-5 h-5 {{ 
                            $alert['type'] === 'danger' ? 'text-red-600 dark:text-red-400' : 
                            ($alert['type'] === 'warning' ? 'text-yellow-600 dark:text-yellow-400' : 
                            ($alert['type'] === 'success' ? 'text-green-600 dark:text-green-400' : 
                            'text-blue-600 dark:text-blue-400'))
                        }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $alert['icon'] }}"></path>
                        </svg>
                    </div>
                </div>
                <div class="mr-3 flex-1 min-w-0">
                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-1.5">{{ $alert['title'] }}</h4>
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $alert['message'] }}</p>
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
            <p class="text-gray-500 dark:text-gray-400 font-medium">لا توجد تنبيهات</p>
            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">كل شيء يعمل بشكل طبيعي</p>
        </div>
    @endif
</div>

