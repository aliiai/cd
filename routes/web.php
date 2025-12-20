<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\LogController as AdminLogController;
use App\Http\Controllers\Admin\AiReportController as AdminAiReportController;
use App\Http\Controllers\Admin\AuditController as AdminAuditController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\AdminController as AdminAdminController;
use App\Http\Controllers\Admin\PermissionController as AdminPermissionController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\DebtorController as OwnerDebtorController;
use App\Http\Controllers\Owner\CollectionController as OwnerCollectionController;
use App\Http\Controllers\Owner\MessageController as OwnerMessageController;
use App\Http\Controllers\Owner\AiAssistanceController as OwnerAiAssistanceController;
use App\Http\Controllers\Owner\AnalyticsController as OwnerAnalyticsController;
use App\Http\Controllers\Owner\SettingsController as OwnerSettingsController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Admin\SubscriptionRequestController as AdminSubscriptionRequestController;
use App\Http\Controllers\Owner\SubscriptionController as OwnerSubscriptionController;
use App\Http\Controllers\Owner\ReportController as OwnerReportController;
use App\Http\Controllers\Owner\NotificationController as OwnerNotificationController;
use App\Http\Controllers\Owner\TicketController as OwnerTicketController;
use App\Http\Controllers\LanguageController;

Route::get('/', function () {
    return view('welcome');
});

// Language Switch Route
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/messages-data', [AdminDashboardController::class, 'getMessagesData'])->name('dashboard.messages-data');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    // Roles Management Routes
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [AdminRoleController::class, 'index'])->name('index');
        Route::get('/create', [AdminRoleController::class, 'create'])->name('create');
        Route::post('/', [AdminRoleController::class, 'store'])->name('store');
        Route::get('/{role}/edit', [AdminRoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [AdminRoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [AdminRoleController::class, 'destroy'])->name('destroy');
    });
    Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');
    Route::get('/logs', [AdminLogController::class, 'index'])->name('logs.index');
    // AI Reports Routes
    Route::prefix('ai-reports')->name('ai-reports.')->group(function () {
        Route::get('/', [AdminAiReportController::class, 'index'])->name('index');
        Route::get('/service-providers', [AdminAiReportController::class, 'serviceProviders'])->name('service-providers');
        Route::get('/campaigns', [AdminAiReportController::class, 'campaigns'])->name('campaigns');
        Route::get('/messages', [AdminAiReportController::class, 'messages'])->name('messages');
        Route::get('/subscriptions', [AdminAiReportController::class, 'subscriptions'])->name('subscriptions');
    });
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
    Route::post('/settings/profile', [AdminSettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/password', [AdminSettingsController::class, 'updatePassword'])->name('settings.password');
    Route::post('/settings/preferences', [AdminSettingsController::class, 'updatePreferences'])->name('settings.preferences');
    Route::post('/settings/logout-other-sessions', [AdminSettingsController::class, 'logoutOtherSessions'])->name('settings.logout-other-sessions');
    Route::get('/audit', [AdminAuditController::class, 'index'])->name('audit.index');
    
    // Subscriptions Management
    Route::resource('subscriptions', AdminSubscriptionController::class);
    
    // Subscription Requests Management
    Route::prefix('subscription-requests')->name('subscription-requests.')->group(function () {
        Route::get('/', [AdminSubscriptionRequestController::class, 'index'])->name('index');
        Route::get('/{request}', [AdminSubscriptionRequestController::class, 'show'])->name('show');
        Route::post('/{request}/approve', [AdminSubscriptionRequestController::class, 'approve'])->name('approve');
        Route::post('/{request}/reject', [AdminSubscriptionRequestController::class, 'reject'])->name('reject');
    });
    
    // Notifications Routes
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [AdminNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    
    // Tickets Routes
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [AdminTicketController::class, 'index'])->name('index');
        Route::get('/{ticket}', [AdminTicketController::class, 'show'])->name('show');
        Route::post('/{ticket}/reply', [AdminTicketController::class, 'reply'])->name('reply');
        Route::post('/{ticket}/update-status', [AdminTicketController::class, 'updateStatus'])->name('update-status');
    });
    
    // Admins Management Routes
    Route::prefix('admins')->name('admins.')->group(function () {
        Route::get('/', [AdminAdminController::class, 'index'])->name('index');
        Route::get('/create', [AdminAdminController::class, 'create'])->name('create');
        Route::post('/', [AdminAdminController::class, 'store'])->name('store');
        Route::get('/{admin}/edit', [AdminAdminController::class, 'edit'])->name('edit');
        Route::put('/{admin}', [AdminAdminController::class, 'update'])->name('update');
        Route::delete('/{admin}', [AdminAdminController::class, 'destroy'])->name('destroy');
        Route::post('/{admin}/toggle-status', [AdminAdminController::class, 'toggleStatus'])->name('toggle-status');
    });
    
    // Permissions Management Routes
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [AdminPermissionController::class, 'index'])->name('index');
        Route::get('/create', [AdminPermissionController::class, 'create'])->name('create');
        Route::post('/', [AdminPermissionController::class, 'store'])->name('store');
        Route::get('/{permission}/edit', [AdminPermissionController::class, 'edit'])->name('edit');
        Route::put('/{permission}', [AdminPermissionController::class, 'update'])->name('update');
        Route::delete('/{permission}', [AdminPermissionController::class, 'destroy'])->name('destroy');
    });
});

// Owner Routes
Route::prefix('owner')->name('owner.')->middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::resource('debtors', OwnerDebtorController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/collections', [OwnerCollectionController::class, 'index'])->name('collections.index');
    Route::post('/collections', [OwnerCollectionController::class, 'store'])->name('collections.store');
    Route::get('/collections/{campaign}', [OwnerCollectionController::class, 'show'])->name('collections.show');
    Route::get('/messages/create', [OwnerMessageController::class, 'create'])->name('messages.create');
    Route::get('/ai-assistance', [OwnerAiAssistanceController::class, 'index'])->name('ai-assistance.index');
    Route::get('/settings', [OwnerSettingsController::class, 'index'])->name('settings');
    Route::post('/settings/profile', [OwnerSettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/password', [OwnerSettingsController::class, 'updatePassword'])->name('settings.password');
    Route::post('/settings/logout-other-sessions', [OwnerSettingsController::class, 'logoutOtherSessions'])->name('settings.logout-other-sessions');
    
    // Subscriptions
    Route::get('/subscriptions', [OwnerSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions', [OwnerSubscriptionController::class, 'store'])->name('subscriptions.store');
    
    // Analytics Routes
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/collection-status', [OwnerAnalyticsController::class, 'collectionStatus'])->name('collection-status');
        Route::get('/income', [OwnerAnalyticsController::class, 'income'])->name('income');
    });
    
    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/debt-status', [OwnerReportController::class, 'debtStatus'])->name('debt-status');
        Route::get('/messages', [OwnerReportController::class, 'messages'])->name('messages');
        Route::get('/campaigns', [OwnerReportController::class, 'campaigns'])->name('campaigns');
        Route::get('/subscription', [OwnerReportController::class, 'subscription'])->name('subscription');
        Route::get('/audit', [OwnerReportController::class, 'audit'])->name('audit');
    });
    
    // Notifications Routes
    Route::get('/notifications', [OwnerNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [OwnerNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [OwnerNotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    
    // Tickets Routes
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [OwnerTicketController::class, 'index'])->name('index');
        Route::get('/create', [OwnerTicketController::class, 'create'])->name('create');
        Route::post('/', [OwnerTicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [OwnerTicketController::class, 'show'])->name('show');
        Route::post('/{ticket}/reply', [OwnerTicketController::class, 'reply'])->name('reply');
        Route::post('/{ticket}/close', [OwnerTicketController::class, 'close'])->name('close');
    });
});
