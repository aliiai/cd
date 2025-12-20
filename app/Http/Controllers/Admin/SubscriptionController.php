<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Notifications\SubscriptionCreatedNotification;
use App\Notifications\SubscriptionUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Subscription Controller for Admin
 * 
 * يدير إدارة الباقات (Subscriptions)
 */
class SubscriptionController extends Controller
{
    /**
     * عرض قائمة جميع الباقات
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = auth()->user();
        if (!$user->hasRole('super_admin') && !$user->can('view subscriptions')) {
            abort(403, 'غير مصرح لك بعرض الاشتراكات.');
        }

        $subscriptions = Subscription::latest()->get();
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * عرض نموذج إنشاء باقة جديدة
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = auth()->user();
        if (!$user->hasRole('super_admin')) {
            if (!$user->can('view subscriptions')) {
                abort(403, 'غير مصرح لك بعرض الاشتراكات.');
            }
            if (!$user->can('create subscriptions')) {
                abort(403, 'غير مصرح لك بإنشاء الاشتراكات.');
            }
        }

        return view('admin.subscriptions.create');
    }

    /**
     * حفظ باقة جديدة
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = auth()->user();
        if (!$user->hasRole('super_admin')) {
            if (!$user->can('view subscriptions')) {
                abort(403, 'غير مصرح لك بعرض الاشتراكات.');
            }
            if (!$user->can('create subscriptions')) {
                abort(403, 'غير مصرح لك بإنشاء الاشتراكات.');
            }
        }

        try {
            // التحقق من صحة البيانات
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'duration_type' => 'required|in:month,year,lifetime',
                'max_debtors' => 'required|integer|min:0',
                'max_messages' => 'required|integer|min:0',
                'ai_enabled' => 'boolean',
                'is_active' => 'boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'يرجى تصحيح الأخطاء في النموذج'
                ], 422);
            }
            throw $e;
        }

        // معالجة checkbox - إذا لم يتم إرساله، يكون false
        $validated['ai_enabled'] = $request->has('ai_enabled') ? (bool)$request->input('ai_enabled') : false;
        $validated['is_active'] = $request->has('is_active') ? (bool)$request->input('is_active') : false;

        // إنشاء الباقة
        $subscription = Subscription::create($validated);

        // إرسال إشعار لجميع الـ Admins (باستثناء من أنشأها)
        $admins = \App\Models\User::role('admin')->where('id', '!=', Auth::id())->get();
        foreach ($admins as $admin) {
            $admin->notify(new SubscriptionCreatedNotification($subscription));
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الباقة بنجاح.'
            ]);
        }

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'تم إنشاء الباقة بنجاح.');
    }

    /**
     * عرض تفاصيل باقة معينة
     * 
     * @param Subscription $subscription
     * @return \Illuminate\View\View
     */
    public function show(Subscription $subscription)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = auth()->user();
        if (!$user->hasRole('super_admin') && !$user->can('view subscriptions')) {
            abort(403, 'غير مصرح لك بعرض الاشتراكات.');
        }

        return view('admin.subscriptions.show', compact('subscription'));
    }

    /**
     * عرض نموذج تعديل باقة
     * 
     * @param Subscription $subscription
     * @return \Illuminate\View\View
     */
    public function edit(Subscription $subscription)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = auth()->user();
        if (!$user->hasRole('super_admin')) {
            if (!$user->can('view subscriptions')) {
                abort(403, 'غير مصرح لك بعرض الاشتراكات.');
            }
            if (!$user->can('edit subscriptions')) {
                abort(403, 'غير مصرح لك بتعديل الاشتراكات.');
            }
        }

        return view('admin.subscriptions.edit', compact('subscription'));
    }

    /**
     * تحديث باقة موجودة
     * 
     * @param Request $request
     * @param Subscription $subscription
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Subscription $subscription)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = auth()->user();
        if (!$user->hasRole('super_admin')) {
            if (!$user->can('view subscriptions')) {
                abort(403, 'غير مصرح لك بعرض الاشتراكات.');
            }
            if (!$user->can('edit subscriptions')) {
                abort(403, 'غير مصرح لك بتعديل الاشتراكات.');
            }
        }

        // التحقق من صحة البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_type' => 'required|in:month,year,lifetime',
            'max_debtors' => 'required|integer|min:0',
            'max_messages' => 'required|integer|min:0',
            'ai_enabled' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // معالجة checkbox - إذا لم يتم إرساله، يكون false
        $validated['ai_enabled'] = $request->has('ai_enabled') ? (bool)$request->input('ai_enabled') : false;
        $validated['is_active'] = $request->has('is_active') ? (bool)$request->input('is_active') : false;

        // تحديث الباقة
        $subscription->update($validated);

        // إرسال إشعار لجميع الـ Admins (باستثناء من عدلها)
        $admins = \App\Models\User::role('admin')->where('id', '!=', Auth::id())->get();
        foreach ($admins as $admin) {
            $admin->notify(new SubscriptionUpdatedNotification($subscription->refresh()));
        }

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'تم تحديث الباقة بنجاح.');
    }

    /**
     * حذف باقة
     * 
     * @param Request $request
     * @param Subscription $subscription
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Subscription $subscription)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = auth()->user();
        if (!$user->hasRole('super_admin')) {
            if (!$user->can('view subscriptions')) {
                abort(403, 'غير مصرح لك بعرض الاشتراكات.');
            }
            if (!$user->can('delete subscriptions')) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'غير مصرح لك بحذف الاشتراكات.'
                    ], 403);
                }
                abort(403, 'غير مصرح لك بحذف الاشتراكات.');
            }
        }

        // التحقق من وجود طلبات مرتبطة بالباقة
        $requestsCount = $subscription->requests()->count();
        $userSubscriptionsCount = $subscription->userSubscriptions()->count();
        
        if ($requestsCount > 0 || $userSubscriptionsCount > 0) {
            $messages = [];
            if ($requestsCount > 0) {
                $messages[] = "{$requestsCount} طلب اشتراك";
            }
            if ($userSubscriptionsCount > 0) {
                $messages[] = "{$userSubscriptionsCount} اشتراك نشط";
            }
            
            $message = 'لا يمكن حذف هذه الباقة لأنها مرتبطة بـ: ' . implode(' و ', $messages) . '.';
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 400);
            }
            return back()->with('error', $message);
        }

        $subscription->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الباقة بنجاح.'
            ]);
        }

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'تم حذف الباقة بنجاح.');
    }
}
