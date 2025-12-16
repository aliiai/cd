@extends('layouts.owner')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">حملات التحصيل</h1>
                <p class="mt-2 text-sm text-gray-600">إنشاء وإدارة حملات التحصيل للمديونين</p>
            </div>
            <button onclick="openCampaignModal()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                + إنشاء حملة تحصيل
            </button>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Campaigns Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">الحملات السابقة</h2>
                @if($campaigns->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الحملة</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عدد المستلمين</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قناة الإرسال</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">حالة الإرسال</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">وقت الإرسال</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($campaigns as $campaign)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $campaign->campaign_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $campaign->total_recipients }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $campaign->channel_text }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $campaign->status_color }}">
                                                {{ $campaign->status_text }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($campaign->send_type === 'scheduled' && $campaign->scheduled_at)
                                                {{ $campaign->scheduled_at->format('Y-m-d H:i') }}
                                            @else
                                                {{ $campaign->created_at->format('Y-m-d H:i') }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('owner.collections.show', $campaign) }}" 
                                               class="text-blue-600 hover:text-blue-900 font-medium">
                                                عرض التفاصيل
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <p class="mt-4 text-gray-500 text-lg">لا توجد حملات حالياً.</p>
                        <button onclick="openCampaignModal()" 
                                class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                            إنشاء حملة تحصيل
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Campaign Modal -->
<div id="campaignModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 hidden overflow-y-auto h-full w-full z-50 backdrop-blur-sm" style="display: none; align-items: center; justify-content: center; padding: 2rem;">
    <div class="relative w-[60%] bg-white rounded-xl shadow-2xl transform transition-all opacity-0 scale-95" style="max-height: 90vh; overflow-y: auto;">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 rounded-t-xl flex items-center justify-between z-10 shadow-md">
            <h3 class="text-xl font-bold">إنشاء حملة تحصيل جديدة</h3>
            <button onclick="closeCampaignModal()" class="text-white hover:text-gray-200 transition-colors duration-200 p-1 rounded-full hover:bg-white/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form id="campaignForm" action="{{ route('owner.collections.store') }}" method="POST" class="p-6">
            @csrf

            <!-- Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Select Clients -->
                    <div>
                        <label for="client_selection" class="block text-sm font-medium text-gray-700 mb-2">
                            اختيار المديونين <span class="text-red-500">*</span>
                        </label>
                        <select id="client_selection" 
                                onchange="handleClientSelection()"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <option value="">اختر طريقة الاختيار</option>
                            <option value="single">مدين واحد</option>
                            <option value="multiple">أكثر من مدين</option>
                            <option value="all">جميع المديونين</option>
                        </select>
                    </div>

                    <!-- Clients Multi-Select (يظهر عند اختيار multiple) -->
                    <div id="multipleClientsDiv" class="hidden">
                        <label for="client_ids" class="block text-sm font-medium text-gray-700 mb-2">
                            اختر المديونين <span class="text-red-500">*</span>
                        </label>
                        <select name="client_ids[]" 
                                id="client_ids" 
                                multiple
                                size="6"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }} - {{ $client->phone }} ({{ number_format($client->debt_amount, 2) }} ر.س)</option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-gray-500">اضغط Ctrl (أو Cmd على Mac) لاختيار أكثر من مدين</p>
                    </div>

                    <!-- Single Client Select (يظهر عند اختيار single) -->
                    <div id="singleClientDiv" class="hidden">
                        <label for="single_client_id" class="block text-sm font-medium text-gray-700 mb-2">
                            اختر المديون <span class="text-red-500">*</span>
                        </label>
                        <select name="single_client_id" 
                                id="single_client_id"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <option value="">اختر المديون</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }} - {{ $client->phone }} ({{ number_format($client->debt_amount, 2) }} ر.س)</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Channel & Template Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Channel -->
                        <div>
                            <label for="channel" class="block text-sm font-medium text-gray-700 mb-2">
                                قناة التواصل <span class="text-red-500">*</span>
                            </label>
                            <select name="channel" 
                                    id="channel" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <option value="">اختر القناة</option>
                                <option value="sms">SMS</option>
                                <option value="email">Email</option>
                            </select>
                            @error('channel')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Template -->
                        <div>
                            <label for="template" class="block text-sm font-medium text-gray-700 mb-2">
                                قالب الرسالة
                            </label>
                            <select name="template" 
                                    id="template" 
                                    onchange="loadTemplate()"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <option value="">اختر قالب (اختياري)</option>
                                <option value="reminder">تذكير بالدفع</option>
                                <option value="overdue">تذكير بالمتأخرات</option>
                                <option value="payment_link">إرسال رابط الدفع</option>
                                <option value="custom">مخصص</option>
                            </select>
                        </div>
                    </div>

                    <!-- Send Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            وقت الإرسال <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 transition-all duration-200 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" 
                                       name="send_type" 
                                       value="now"
                                       checked
                                       onchange="toggleScheduleInput()"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="mr-2 text-sm font-medium text-gray-700">إرسال فوري</span>
                            </label>
                            <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 transition-all duration-200 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" 
                                       name="send_type" 
                                       value="scheduled"
                                       onchange="toggleScheduleInput()"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="mr-2 text-sm font-medium text-gray-700">جدولة الإرسال</span>
                            </label>
                        </div>
                    </div>

                    <!-- Scheduled At (يظهر عند اختيار scheduled) -->
                    <div id="scheduledAtDiv" class="hidden">
                        <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-2">
                            تاريخ ووقت الإرسال <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" 
                               name="scheduled_at" 
                               id="scheduled_at"
                               min="{{ now()->format('Y-m-d\TH:i') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        @error('scheduled_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            نص الرسالة <span class="text-red-500">*</span>
                        </label>
                        <textarea name="message" 
                                  id="message" 
                                  rows="8"
                                  required
                                  oninput="updatePreview()"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"></textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preview -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            معاينة الرسالة
                        </label>
                        <div id="messagePreview" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gradient-to-br from-gray-50 to-gray-100 text-sm text-gray-700 whitespace-pre-wrap overflow-y-auto" style="min-height: 150px; max-height: 150px;">
                            ستظهر معاينة الرسالة هنا...
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                <button type="button" 
                        onclick="closeCampaignModal()"
                        class="px-6 py-2.5 border-2 border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                    إلغاء
                </button>
                <button type="submit" 
                        class="px-6 py-2.5 border border-transparent rounded-lg shadow-md text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 transition-all duration-200 transform hover:scale-105">
                    إرسال الحملة
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // قوالب الرسائل الجاهزة
    const templates = {
        reminder: 'مرحباً @{{name}}، نود تذكيرك بأن لديك مبلغ مستحق للدفع بقيمة @{{amount}} ر.س. يرجى تسوية المبلغ في أقرب وقت ممكن. شكراً لتعاونك.',
        overdue: 'مرحباً @{{name}}، نود إعلامك بأن مبلغ @{{amount}} ر.س قد تجاوز تاريخ الاستحقاق. يرجى التواصل معنا لتسوية المبلغ. شكراً.',
        payment_link: 'مرحباً @{{name}}، يمكنك تسوية مبلغ @{{amount}} ر.س من خلال الرابط التالي: @{{link}} شكراً.',
        custom: ''
    };

    // Open Modal
    function openCampaignModal() {
        const modal = document.getElementById('campaignModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            // إضافة animation
            setTimeout(() => {
                modal.querySelector('.relative').style.transform = 'scale(1)';
                modal.querySelector('.relative').style.opacity = '1';
            }, 10);
        }
    }

    // Close Modal
    function closeCampaignModal() {
        const modal = document.getElementById('campaignModal');
        const form = document.getElementById('campaignForm');
        const modalContent = modal?.querySelector('.relative');
        
        if (modalContent) {
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
        }
        
        setTimeout(() => {
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
            
            if (form) {
                form.reset();
                document.getElementById('client_selection').value = '';
                document.getElementById('multipleClientsDiv').classList.add('hidden');
                document.getElementById('singleClientDiv').classList.add('hidden');
                document.getElementById('scheduledAtDiv').classList.add('hidden');
                document.getElementById('messagePreview').textContent = 'ستظهر معاينة الرسالة هنا...';
                
                // إزالة أي hidden inputs
                const existingInputs = document.querySelectorAll('input[name="client_ids[]"][type="hidden"]');
                existingInputs.forEach(input => input.remove());
            }
        }, 200);
    }

    // Handle Client Selection
    function handleClientSelection() {
        const selection = document.getElementById('client_selection').value;
        const multipleDiv = document.getElementById('multipleClientsDiv');
        const singleDiv = document.getElementById('singleClientDiv');
        
        // إخفاء جميع الخيارات أولاً
        multipleDiv.classList.add('hidden');
        singleDiv.classList.add('hidden');
        
        // إزالة required من جميع الحقول
        document.getElementById('client_ids').removeAttribute('required');
        document.getElementById('single_client_id').removeAttribute('required');
        
        // إزالة أي hidden inputs سابقة
        const existingInputs = document.querySelectorAll('input[name="client_ids[]"][type="hidden"]');
        existingInputs.forEach(input => input.remove());
        
        if (selection === 'multiple') {
            multipleDiv.classList.remove('hidden');
            document.getElementById('client_ids').setAttribute('required', 'required');
        } else if (selection === 'single') {
            singleDiv.classList.remove('hidden');
            document.getElementById('single_client_id').setAttribute('required', 'required');
        } else if (selection === 'all') {
            // إضافة hidden inputs لجميع المديونين
            @if($clients->count() > 0)
                @foreach($clients as $client)
                    const input{{ $client->id }} = document.createElement('input');
                    input{{ $client->id }}.type = 'hidden';
                    input{{ $client->id }}.name = 'client_ids[]';
                    input{{ $client->id }}.value = '{{ $client->id }}';
                    document.getElementById('campaignForm').appendChild(input{{ $client->id }});
                @endforeach
            @endif
        }
    }

    // Load Template
    function loadTemplate() {
        const template = document.getElementById('template').value;
        const messageField = document.getElementById('message');
        
        if (template && templates[template]) {
            messageField.value = templates[template];
            updatePreview();
        } else if (template === 'custom') {
            messageField.value = '';
            updatePreview();
        }
    }

    // Update Preview
    function updatePreview() {
        const message = document.getElementById('message').value;
        const preview = document.getElementById('messagePreview');
        
        if (message) {
            preview.textContent = message;
        } else {
            preview.textContent = 'ستظهر معاينة الرسالة هنا...';
        }
    }

    // Toggle Schedule Input
    function toggleScheduleInput() {
        const sendType = document.querySelector('input[name="send_type"]:checked').value;
        const scheduledDiv = document.getElementById('scheduledAtDiv');
        const scheduledInput = document.getElementById('scheduled_at');
        
        if (sendType === 'scheduled') {
            scheduledDiv.classList.remove('hidden');
            scheduledInput.setAttribute('required', 'required');
        } else {
            scheduledDiv.classList.add('hidden');
            scheduledInput.removeAttribute('required');
        }
    }

    // Handle Form Submission
    document.getElementById('campaignForm').addEventListener('submit', function(e) {
        const selection = document.getElementById('client_selection').value;
        
        // التحقق من اختيار المديونين
        if (selection === 'single') {
            const singleClientId = document.getElementById('single_client_id').value;
            if (!singleClientId) {
                e.preventDefault();
                alert('يرجى اختيار المديون');
                return false;
            }
            // إضافة hidden input
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'client_ids[]';
            hiddenInput.value = singleClientId;
            this.appendChild(hiddenInput);
        } else if (selection === 'all') {
            // التحقق من وجود مديونين
            const allClientsCount = {{ $clients->count() }};
            if (allClientsCount === 0) {
                e.preventDefault();
                alert('لا يوجد مديونين متاحين');
                return false;
            }
        } else if (selection === 'multiple') {
            const selectedClients = Array.from(document.getElementById('client_ids').selectedOptions);
            if (selectedClients.length === 0) {
                e.preventDefault();
                alert('يرجى اختيار مديون واحد على الأقل');
                return false;
            }
        } else {
            e.preventDefault();
            alert('يرجى اختيار طريقة اختيار المديونين');
            return false;
        }
    });

    // Close modal when clicking outside
    document.getElementById('campaignModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCampaignModal();
        }
    });
</script>
@endsection
