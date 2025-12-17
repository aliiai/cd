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
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\DebtorController as OwnerDebtorController;
use App\Http\Controllers\Owner\CollectionController as OwnerCollectionController;
use App\Http\Controllers\Owner\MessageController as OwnerMessageController;
use App\Http\Controllers\Owner\AiAssistanceController as OwnerAiAssistanceController;
use App\Http\Controllers\Owner\SettingsController as OwnerSettingsController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Admin\SubscriptionRequestController as AdminSubscriptionRequestController;
use App\Http\Controllers\Owner\SubscriptionController as OwnerSubscriptionController;
use App\Http\Controllers\Owner\ReportController as OwnerReportController;
use App\Http\Controllers\Owner\NotificationController as OwnerNotificationController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::get('/roles', [AdminRoleController::class, 'index'])->name('roles.index');
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
    
    // Subscriptions
    Route::get('/subscriptions', [OwnerSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions', [OwnerSubscriptionController::class, 'store'])->name('subscriptions.store');
    
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
});
