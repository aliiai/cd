@extends('layouts.owner')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('owner.collections.index') }}" 
               class="text-blue-600 hover:text-blue-900 text-sm mb-2 inline-block">
                ← العودة إلى قائمة الحملات
            </a>
            <h1 class="text-3xl font-bold text-gray-900">تفاصيل الحملة</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Campaign Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Campaign Basic Info -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">معلومات الحملة</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">رقم الحملة</label>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $campaign->campaign_number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">قناة الإرسال</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $campaign->channel_text }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">حالة الإرسال</label>
                                <p class="mt-1">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $campaign->status_color }}">
                                        {{ $campaign->status_text }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">نوع الإرسال</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $campaign->send_type === 'now' ? 'إرسال فوري' : 'مجدول' }}
                                </p>
                            </div>
                            @if($campaign->scheduled_at)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">وقت الجدولة</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $campaign->scheduled_at->format('Y-m-d H:i') }}</p>
                                </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-500">تاريخ الإنشاء</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $campaign->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">الإحصائيات</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">إجمالي المستلمين:</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $campaign->total_recipients }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">تم الإرسال:</span>
                                <span class="text-sm font-semibold text-green-600">{{ $campaign->sent_count }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">فشل الإرسال:</span>
                                <span class="text-sm font-semibold text-red-600">{{ $campaign->failed_count }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Message & Recipients -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Message Content -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">محتوى الرسالة</h2>
                        @if($campaign->template)
                            <div class="mb-2">
                                <span class="text-xs text-gray-500">القالب المستخدم: {{ $campaign->template }}</span>
                            </div>
                        @endif
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $campaign->message }}</p>
                        </div>
                    </div>
                </div>

                <!-- Recipients List -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">قائمة المستلمين</h2>
                        @if($campaign->clients->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الهاتف</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">حالة الإرسال</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($campaign->clients as $client)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                    {{ $client->name }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-500">
                                                    {{ $client->phone }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-500">
                                                    {{ $client->email ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3 text-sm">
                                                    @php
                                                        $pivotStatus = $client->pivot->status ?? 'pending';
                                                        $statusColors = [
                                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                                            'sent' => 'bg-green-100 text-green-800',
                                                            'failed' => 'bg-red-100 text-red-800',
                                                        ];
                                                        $statusTexts = [
                                                            'pending' => 'قيد الانتظار',
                                                            'sent' => 'تم الإرسال',
                                                            'failed' => 'فشل',
                                                        ];
                                                    @endphp
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$pivotStatus] ?? 'bg-gray-100 text-gray-800' }}">
                                                        {{ $statusTexts[$pivotStatus] ?? 'غير محدد' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">لا يوجد مستلمين</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

