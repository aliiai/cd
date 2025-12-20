<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Permission Controller for Managing Permissions
 * 
 * يدير إضافة وتعديل وحذف الصلاحيات
 */
class PermissionController extends Controller
{
    /**
     * عرض قائمة جميع الصلاحيات
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && !$user->can('view permissions')) {
            abort(403, 'غير مصرح لك بعرض الصلاحيات.');
        }

        $query = Permission::query();
        
        // البحث
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        
        // الترتيب
        $permissions = $query->orderBy('name', 'asc')->paginate(20);
        
        // تجميع الصلاحيات حسب الفئة
        $groupedPermissions = Permission::all()->groupBy(function($permission) {
            $parts = explode(' ', $permission->name);
            return $parts[0]; // أول كلمة
        });
        
        return view('admin.permissions.index', compact('permissions', 'groupedPermissions'));
    }

    /**
     * عرض صفحة إنشاء صلاحية جديدة
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && !$user->can('create permissions')) {
            abort(403, 'غير مصرح لك بإنشاء صلاحية جديدة.');
        }

        return view('admin.permissions.create');
    }

    /**
     * حفظ صلاحية جديدة
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && !$user->can('create permissions')) {
            abort(403, 'غير مصرح لك بإنشاء صلاحية جديدة.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create(['name' => $validated['name']]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'تم إنشاء الصلاحية بنجاح.');
    }

    /**
     * عرض صفحة تعديل صلاحية
     * 
     * @param Permission $permission
     * @return \Illuminate\View\View
     */
    public function edit(Permission $permission)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && !$user->can('edit permissions')) {
            abort(403, 'غير مصرح لك بتعديل الصلاحيات.');
        }

        $roles = Role::all();
        $permission->load('roles');
        
        return view('admin.permissions.edit', compact('permission', 'roles'));
    }

    /**
     * تحديث صلاحية
     * 
     * @param Request $request
     * @param Permission $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Permission $permission)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && !$user->can('edit permissions')) {
            abort(403, 'غير مصرح لك بتعديل الصلاحيات.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update(['name' => $validated['name']]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'تم تحديث الصلاحية بنجاح.');
    }

    /**
     * حذف صلاحية
     * 
     * @param Permission $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Permission $permission)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && !$user->can('delete permissions')) {
            abort(403, 'غير مصرح لك بحذف الصلاحيات.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'تم حذف الصلاحية بنجاح.');
    }
}
