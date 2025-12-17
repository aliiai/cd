@if($users->count() > 0)
    <div class="overflow-x-auto rounded-lg shadow-lg border border-gray-200">
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-700 to-gray-600">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">الاسم</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">البريد الإلكتروني</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">الدور</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">حالة الحساب</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">تاريخ التسجيل</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="usersTableBody">
                @foreach($users as $user)
                    <tr class="hover:bg-blue-50 transition-all duration-200 hover:shadow-md">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($user->roles->count() > 0)
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($user->is_active)
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
                            {{ $user->created_at->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3 space-x-reverse gap-2">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200 shadow-sm hover:shadow-md"
                                   title="عرض التفاصيل">
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span class="text-xs font-medium">عرض</span>
                                </a>
                                <form action="{{ route('admin.users.toggle-status', $user) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('هل أنت متأكد من {{ $user->is_active ? 'إيقاف' : 'تفعيل' }} هذا الحساب؟');">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 {{ $user->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md"
                                            title="{{ $user->is_active ? 'إيقاف' : 'تفعيل' }}">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($user->is_active)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            @endif
                                        </svg>
                                        <span class="text-xs font-medium">{{ $user->is_active ? 'إيقاف' : 'تفعيل' }}</span>
                                    </button>
                                </form>
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
        <p class="mt-4 text-gray-500 text-lg">لا يوجد مستخدمين حالياً.</p>
    </div>
@endif

