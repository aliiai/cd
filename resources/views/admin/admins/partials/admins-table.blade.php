@if($admins->count() > 0)
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gradient-to-r from-primary-600 via-primary-500 to-secondary-600">
                <tr>
                    <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">الاسم</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">البريد الإلكتروني</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">الدور</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">عدد الصلاحيات</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">الحالة</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">تاريخ الإنشاء</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="adminsTableBody">
                @foreach($admins as $admin)
                    <tr class="hover:bg-primary-50 dark:hover:bg-gray-700 transition-all duration-200 hover:shadow-md">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-semibold text-sm">
                                        {{ substr($admin->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="mr-3">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $admin->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                            {{ $admin->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $role = $admin->roles->first();
                            @endphp
                            @if($role)
                                <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-primary-300 border border-primary-200 dark:border-primary-800">
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                            @php
                                $role = $admin->roles->first();
                                $permissionsCount = $role ? $role->permissions->count() : 0;
                            @endphp
                            {{ $permissionsCount }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($admin->is_active)
                                <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800">
                                    نشط
                                </span>
                            @else
                                <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800">
                                    موقوف
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                            {{ $admin->created_at->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3 space-x-reverse gap-2">
                                @can('edit admins')
                                    <a href="{{ route('admin.admins.edit', $admin) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-lg hover:from-primary-600 hover:to-primary-700 transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
                                       title="تعديل">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span class="text-xs font-medium">تعديل</span>
                                    </a>
                                @endcan
                                
                                @can('edit admins')
                                    @if(!$admin->hasRole('super_admin') || Auth::user()->hasRole('super_admin'))
                                        <form action="{{ route('admin.admins.toggle-status', $admin) }}" 
                                              method="POST" 
                                              class="inline toggle-status-form">
                                            @csrf
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1.5 {{ $admin->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md"
                                                    title="{{ $admin->is_active ? 'إيقاف' : 'تفعيل' }}">
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if($admin->is_active)
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    @endif
                                                </svg>
                                                <span class="text-xs font-medium">{{ $admin->is_active ? 'إيقاف' : 'تفعيل' }}</span>
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                                
                                @can('delete admins')
                                    @if(!$admin->hasRole('super_admin') && $admin->id !== Auth::id())
                                        <form action="{{ route('admin.admins.destroy', $admin) }}" 
                                              method="POST" 
                                              class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200 shadow-sm hover:shadow-md"
                                                    title="حذف">
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                <span class="text-xs font-medium">حذف</span>
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-16">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">لا يوجد مشرفين</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-4">ابدأ بإنشاء مشرف جديد</p>
        @can('create admins')
            <a href="{{ route('admin.admins.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg font-semibold">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                مشرف جديد
            </a>
        @endcan
    </div>
@endif

