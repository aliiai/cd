<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Notifications\UserCreatedNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

/**
 * Create New User Action
 * 
 * هذا الـ Action يقوم بإنشاء مستخدم جديد عند التسجيل
 * ويتضمن: التحقق من البيانات، حفظ الصورة الشخصية، إرسال إشعار للـ Admin
 */
class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // التحقق من صحة البيانات المدخلة
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'password' => $this->passwordRules(),
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        // إنشاء المستخدم الجديد
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'] ?? null,
            'password' => Hash::make($input['password']),
            'is_active' => true, // المستخدمون الجدد مفعلون افتراضياً
        ]);

        // حفظ الصورة الشخصية إذا تم رفعها
        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        // إعطاء المستخدم الجديد دور 'owner' افتراضياً
        $user->assignRole('owner');

        // إرسال إشعار لجميع الـ Admins عن تسجيل مستخدم جديد
        try {
            $admins = User::role('admin')->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new UserCreatedNotification($user));
            }
        } catch (\Exception $e) {
            // في حالة فشل الإشعار، لا نوقف عملية التسجيل
            \Log::error('Failed to send user created notification: ' . $e->getMessage());
        }

        // تسجيل عملية التسجيل في Audit Logs (عبر Notification)
        // سيتم تسجيلها تلقائياً في Audit Logs عند عرضها

        return $user;
    }
}
