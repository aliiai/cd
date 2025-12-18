@if($admins->count() > 0)
    <div class="overflow-x-auto rounded-lg shadow-lg border border-gray-200">
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-700 to-gray-600">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">الاسم</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">البريد الإلكتروني</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">الدور</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">عدد الصلاحيات</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">الحالة</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">تاريخ الإنشاء</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="adminsTableBody">
                @foreach($admins as $admin)
                    <tr class="hover:bg-primary-50 transition-all duration-200 hover:shadow-md">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $admin->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $admin->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($admin->hasRole('super_admin'))
                                <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm bg-secondary-100 text-secondary-800">
                                    Super Admin
                                </span>
                            @elseif($admin->hasRole('admin'))
                                <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm bg-primary-100 text-primary-800">
                                    Admin
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $admin->permissions->count() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($admin->is_active)
                                <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm bg-green-100 text-green-800">
                                    نشط
                                </span>
                            @else
                                <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm bg-red-100 text-red-800">
                                    موقوف
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $admin->created_at->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3 space-x-reverse gap-2">
                                @can('edit admins')
                                    <a href="{{ route('admin.admins.edit', $admin) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-primary-100 text-primary-700 rounded-lg hover:bg-primary-200 transition-colors duration-200 shadow-sm hover:shadow-md"
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
                                              class="inline"
                                              onsubmit="return confirm('هل أنت متأكد من {{ $admin->is_active ? 'إيقاف' : 'تفعيل' }} هذا الحساب؟');">
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
                                              class="inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا المشرف؟');">
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
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        <p class="mt-4 text-gray-500 text-lg">لا يوجد مشرفين حالياً.</p>
        @can('create admins')
            <a href="{{ route('admin.admins.create') }}" 
               class="mt-4 inline-block bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                إنشاء مشرف جديد
            </a>
        @endcan
    </div>
@endif

