@extends('layouts.owner')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- ========== Header Section ========== --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ __('tickets.title') }}</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">{{ __('tickets.description') }}</p>
            </div>
            <button onclick="openCreateTicketModal()" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('tickets.new_ticket') }}
            </button>
        </div>

        {{-- ========== Success/Error Messages ========== --}}
        @if(session('success'))
            <div class="mb-6 bg-gradient-to-r from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 border-l-4 border-emerald-500 dark:border-emerald-400 rounded-lg p-4 flex items-center shadow-md">
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-emerald-800 dark:text-emerald-300 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border-l-4 border-red-500 dark:border-red-400 rounded-lg p-4 flex items-center shadow-md">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <p class="text-red-800 dark:text-red-300 font-medium">{{ session('error') }}</p>
            </div>
        @endif

        {{-- ========== Filters and Search ========== --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8 border border-gray-200 dark:border-gray-700">
            <form id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Search --}}
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('common.search') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="{{ __('tickets.search_placeholder') }}"
                               class="w-full pl-12 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors">
                    </div>
                </div>
                
                {{-- Status Filter --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('tickets.status_filter') }}</label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors">
                        <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>{{ __('tickets.all_statuses') }}</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>{{ __('tickets.open') }}</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>{{ __('tickets.in_progress') }}</option>
                        <option value="waiting_user" {{ request('status') == 'waiting_user' ? 'selected' : '' }}>{{ __('tickets.waiting_user') }}</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>{{ __('tickets.closed') }}</option>
                    </select>
                </div>
                
                {{-- Type Filter --}}
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('tickets.type_filter') }}</label>
                    <select id="type" 
                            name="type" 
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors">
                        <option value="all" {{ request('type') == 'all' || !request('type') ? 'selected' : '' }}>{{ __('tickets.all_types') }}</option>
                        <option value="technical" {{ request('type') == 'technical' ? 'selected' : '' }}>{{ __('tickets.technical') }}</option>
                        <option value="subscription" {{ request('type') == 'subscription' ? 'selected' : '' }}>{{ __('tickets.subscription') }}</option>
                        <option value="messages" {{ request('type') == 'messages' ? 'selected' : '' }}>{{ __('tickets.messages') }}</option>
                        <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>{{ __('tickets.general') }}</option>
                        <option value="suggestion" {{ request('type') == 'suggestion' ? 'selected' : '' }}>{{ __('tickets.suggestion') }}</option>
                    </select>
                </div>
            </form>
        </div>

        {{-- ========== Tickets Table ========== --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div id="ticketsTableContainer">
                    @include('owner.tickets.partials.tickets-table', ['tickets' => $tickets])
                </div>
                
                {{-- Pagination --}}
                <div id="paginationContainer" class="mt-6">
                    {{ $tickets->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ========== Create Ticket Modal ========== --}}
<div id="createTicketModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        {{-- Background overlay --}}
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeCreateTicketModal()"></div>

        {{-- Modal panel --}}
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-primary-500 to-secondary-500 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white">{{ __('tickets.create_title') }}</h3>
                    <button onclick="closeCreateTicketModal()" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="mt-1 text-sm text-white/90">{{ __('tickets.create_description') }}</p>
            </div>

            {{-- Form --}}
            <form id="createTicketForm" enctype="multipart/form-data" class="p-6">
                @csrf
                
                {{-- Subject --}}
                <div class="mb-6">
                    <label for="modal_subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('tickets.ticket_subject') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="modal_subject" 
                           name="subject" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                    <div id="subject_error" class="mt-1 text-sm text-red-600 hidden"></div>
                </div>

                {{-- Type --}}
                <div class="mb-6">
                    <label for="modal_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('tickets.ticket_type') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="modal_type" 
                            name="type" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                        <option value="">{{ __('tickets.select_type') }}</option>
                        <option value="technical">{{ __('tickets.technical') }}</option>
                        <option value="subscription">{{ __('tickets.subscription') }}</option>
                        <option value="messages">{{ __('tickets.messages') }}</option>
                        <option value="general">{{ __('tickets.general') }}</option>
                        <option value="suggestion">{{ __('tickets.suggestion') }}</option>
                    </select>
                    <div id="type_error" class="mt-1 text-sm text-red-600 hidden"></div>
                </div>

                {{-- Description --}}
                <div class="mb-6">
                    <label for="modal_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('tickets.ticket_description') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea id="modal_description" 
                              name="description" 
                              rows="6"
                              required
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors resize-none"
                              placeholder="{{ __('tickets.description_placeholder') }}"></textarea>
                    <div id="description_error" class="mt-1 text-sm text-red-600 hidden"></div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">الحد الأدنى 10 أحرف</p>
                </div>

                {{-- Attachment --}}
                <div class="mb-6">
                    <label for="modal_attachment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('tickets.attachment') }}
                    </label>
                    <input type="file" 
                           id="modal_attachment" 
                           name="attachment" 
                           accept="image/*"
                           class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors">
                    <div id="attachment_error" class="mt-1 text-sm text-red-600 hidden"></div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('tickets.attachment_hint') }}</p>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-end space-x-3 space-x-reverse pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" 
                            onclick="closeCreateTicketModal()"
                            class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200 font-medium">
                        {{ __('common.cancel') }}
                    </button>
                    <button type="submit" 
                            id="submitTicketBtn"
                            class="px-6 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <span id="submitTicketBtnText">{{ __('tickets.send_ticket') }}</span>
                        <span id="submitTicketBtnLoader" class="hidden inline-flex items-center">
                            <svg class="animate-spin -mr-1 ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('tickets.sending') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Load tickets via AJAX
    function loadTickets() {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        
        // Add sort
        params.append('sort_by', 'created_at');
        params.append('sort_dir', 'desc');
        
        // Show loading
        document.getElementById('ticketsTableContainer').innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600 dark:border-primary-400"></div><p class="mt-4 text-gray-500 dark:text-gray-400">{{ __('common.loading') }}</p></div>';
        
        fetch(`{{ route('owner.tickets.index') }}?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('ticketsTableContainer').innerHTML = data.table;
            document.getElementById('paginationContainer').innerHTML = data.pagination;
            
            // Update URL
            const newUrl = window.location.pathname + '?' + params.toString();
            window.history.pushState({path: newUrl}, '', newUrl);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('ticketsTableContainer').innerHTML = '<div class="text-center py-12 text-red-500 dark:text-red-400">{{ __('common.error_loading_data') }}</div>';
        });
    }

    // Debounced load function
    const debouncedLoadTickets = debounce(loadTickets, 500);

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const statusSelect = document.getElementById('status');
        const typeSelect = document.getElementById('type');
        
        if (searchInput) {
            searchInput.addEventListener('input', debouncedLoadTickets);
        }
        
        if (statusSelect) {
            statusSelect.addEventListener('change', loadTickets);
        }
        
        if (typeSelect) {
            typeSelect.addEventListener('change', loadTickets);
        }
    });

    // Handle pagination clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;
            
            document.getElementById('ticketsTableContainer').innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600 dark:border-primary-400"></div><p class="mt-4 text-gray-500 dark:text-gray-400">جاري التحميل...</p></div>';
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('ticketsTableContainer').innerHTML = data.table;
                document.getElementById('paginationContainer').innerHTML = data.pagination;
                window.history.pushState({path: url}, '', url);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('ticketsTableContainer').innerHTML = '<div class="text-center py-12 text-red-500 dark:text-red-400">{{ __('common.error_loading_data') }}</div>';
            });
        }
    });

    // ========== Create Ticket Modal Functions ==========
    function openCreateTicketModal() {
        document.getElementById('createTicketModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeCreateTicketModal() {
        document.getElementById('createTicketModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        // Reset form
        document.getElementById('createTicketForm').reset();
        // Clear errors
        document.querySelectorAll('[id$="_error"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
    }

    // Handle form submission
    document.getElementById('createTicketForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        const submitBtn = document.getElementById('submitTicketBtn');
        const submitBtnText = document.getElementById('submitTicketBtnText');
        const submitBtnLoader = document.getElementById('submitTicketBtnLoader');
        
        // Clear previous errors
        document.querySelectorAll('[id$="_error"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtnText.classList.add('hidden');
        submitBtnLoader.classList.remove('hidden');
        
        fetch('{{ route("owner.tickets.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: '{{ __('common.success_message') }}',
                    text: data.message || '{{ __('tickets.create_success') }}',
                    confirmButtonText: '{{ __('common.ok') }}',
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    // Close modal
                    closeCreateTicketModal();
                    // Reload page to show new ticket
                    window.location.reload();
                });
            } else {
                // Show validation errors
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.getElementById(field + '_error');
                        if (errorElement) {
                            errorElement.textContent = data.errors[field][0];
                            errorElement.classList.remove('hidden');
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __('common.error_message') }}',
                        text: data.message || '{{ __('tickets.create_error') }}',
                        confirmButtonText: '{{ __('common.ok') }}',
                        confirmButtonColor: '#ef4444'
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: '{{ __('common.error_message') }}',
                text: '{{ __('tickets.create_error_retry') }}',
                confirmButtonText: '{{ __('common.ok') }}',
                confirmButtonColor: '#ef4444'
            });
        })
        .finally(() => {
            // Enable submit button
            submitBtn.disabled = false;
            submitBtnText.classList.remove('hidden');
            submitBtnLoader.classList.add('hidden');
        });
    });

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateTicketModal();
        }
    });
</script>
@endpush
@endsection
