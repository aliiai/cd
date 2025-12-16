<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Client Controller for Owner
 * 
 * هذا Controller يدير صفحة Clients في لوحة تحكم Owner
 */
class ClientController extends Controller
{
    /**
     * عرض قائمة المديونين
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // جلب جميع المديونين للمالك الحالي فقط
        $clients = Client::where('owner_id', Auth::id())
            ->latest()
            ->get();
        
        return view('owner.clients.index', compact('clients'));
    }

    /**
     * حفظ مديون جديد
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'debt_amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'payment_link' => 'nullable|url|max:500',
            'notes' => 'nullable|string',
            'status' => 'required|in:new,contacted,promise_to_pay,paid,overdue,failed',
        ]);

        // إضافة owner_id تلقائياً
        $validated['owner_id'] = Auth::id();

        // إنشاء المديون
        Client::create($validated);

        return redirect()->route('owner.clients.index')
            ->with('success', 'تم إضافة المديون بنجاح.');
    }

    /**
     * تحديث بيانات مديون
     * 
     * @param Request $request
     * @param Client $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Client $client)
    {
        // التحقق من أن المديون يخص المالك الحالي
        if ($client->owner_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بتعديل هذا المديون.');
        }

        // التحقق من صحة البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'debt_amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'payment_link' => 'nullable|url|max:500',
            'notes' => 'nullable|string',
            'status' => 'required|in:new,contacted,promise_to_pay,paid,overdue,failed',
        ]);

        // تحديث بيانات المديون
        $client->update($validated);

        return redirect()->route('owner.clients.index')
            ->with('success', 'تم تحديث بيانات المديون بنجاح.');
    }

    /**
     * حذف مديون
     * 
     * @param Client $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Client $client)
    {
        // التحقق من أن المديون يخص المالك الحالي
        if ($client->owner_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بحذف هذا المديون.');
        }

        // حذف المديون
        $client->delete();

        return redirect()->route('owner.clients.index')
            ->with('success', 'تم حذف المديون بنجاح.');
    }
}

