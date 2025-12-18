<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Settings Controller for Admin
 * 
 * هذا Controller يدير صفحة Settings في لوحة تحكم Admin
 */
class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $user = auth()->user();
        
        // جلب الجلسات النشطة
        $sessions = $this->getActiveSessions($user);
        
        // جلب تفضيلات Dark Mode
        $darkModePreference = session('dark_mode_preference', 'system');
        
        return view('admin.settings', [
            'user' => $user,
            'sessions' => $sessions,
            'darkModePreference' => $darkModePreference,
        ]);
    }

    /**
     * Update profile information
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        // تحديث المعلومات الشخصية مع إضافة phone
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'phone.regex' => 'رقم الهاتف غير صحيح',
            'photo.image' => 'يجب أن تكون الصورة بصيغة صحيحة',
            'photo.mimes' => 'يجب أن تكون الصورة بصيغة jpg, jpeg, أو png',
            'photo.max' => 'حجم الصورة يجب أن يكون أقل من 2MB',
        ]);

        try {
            // تحديث الصورة الشخصية إذا تم رفعها
            if ($request->hasFile('photo')) {
                $user->updateProfilePhoto($request->file('photo'));
            }

            // تحديث البيانات
            $user->forceFill([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? $user->phone,
            ])->save();

            return redirect()->route('admin.settings')
                ->with('success', 'تم تحديث معلومات الحساب بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings')
                ->withErrors(['error' => 'حدث خطأ أثناء تحديث المعلومات. يرجى المحاولة مرة أخرى.'])
                ->withInput();
        }
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request, UpdateUserPassword $updater)
    {
        $user = auth()->user();
        
        $updater->update($user, $request->all());

        return redirect()->route('admin.settings')
            ->with('success', 'تم تحديث كلمة المرور بنجاح');
    }

    /**
     * Update preferences
     */
    public function updatePreferences(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'language' => ['nullable', 'string', 'in:ar,en'],
            'dark_mode' => ['nullable', 'string', 'in:light,dark,system'],
        ], [
            'language.in' => 'اللغة المحددة غير مدعومة',
            'dark_mode.in' => 'الوضع المحدد غير مدعوم',
        ]);

        // حفظ التفضيلات في session
        if (isset($validated['language'])) {
            session(['locale' => $validated['language']]);
        }
        
        if (isset($validated['dark_mode'])) {
            session(['dark_mode_preference' => $validated['dark_mode']]);
        }

        return redirect()->route('admin.settings')
            ->with('success', 'تم تحديث التفضيلات بنجاح');
    }

    /**
     * Logout from other sessions
     */
    public function logoutOtherSessions(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'current_password:web'],
        ], [
            'password.required' => 'كلمة المرور مطلوبة',
            'password.current_password' => 'كلمة المرور غير صحيحة',
        ]);

        // تسجيل الخروج من جميع الجلسات الأخرى
        DB::table('sessions')
            ->where('user_id', auth()->id())
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        return redirect()->route('admin.settings')
            ->with('success', 'تم تسجيل الخروج من جميع الجلسات الأخرى بنجاح');
    }

    /**
     * Get active sessions for the user
     */
    private function getActiveSessions($user)
    {
        if (config('session.driver') !== 'database') {
            return collect([]);
        }

        return DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) use ($user) {
                return (object) [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'user_agent' => $this->parseUserAgent($session->user_agent),
                    'last_activity' => \Carbon\Carbon::createFromTimestamp($session->last_activity),
                    'is_current' => $session->id === session()->getId(),
                ];
            });
    }

    /**
     * Parse user agent string
     */
    private function parseUserAgent($userAgent)
    {
        if (str_contains($userAgent, 'Windows')) {
            return 'Windows';
        } elseif (str_contains($userAgent, 'Mac')) {
            return 'Mac';
        } elseif (str_contains($userAgent, 'Linux')) {
            return 'Linux';
        } elseif (str_contains($userAgent, 'Android')) {
            return 'Android';
        } elseif (str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')) {
            return 'iOS';
        }
        
        return 'Unknown';
    }
}

