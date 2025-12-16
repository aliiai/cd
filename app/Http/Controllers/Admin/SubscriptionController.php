<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

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
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'max_debtors' => 'required|integer|min:0',
            'max_messages' => 'required|integer|min:0',
            'ai_enabled' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // إنشاء الباقة
        Subscription::create($validated);

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
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'max_debtors' => 'required|integer|min:0',
            'max_messages' => 'required|integer|min:0',
            'ai_enabled' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // تحديث الباقة
        $subscription->update($validated);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'تم تحديث الباقة بنجاح.');
    }

    /**
     * حذف باقة
     * 
     * @param Subscription $subscription
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Subscription $subscription)
    {
        // التحقق من وجود طلبات مرتبطة بالباقة
        if ($subscription->requests()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف هذه الباقة لأنها مرتبطة بطلبات اشتراك.');
        }

        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'تم حذف الباقة بنجاح.');
    }
}
