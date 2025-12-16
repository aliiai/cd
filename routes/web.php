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
use App\Http\Controllers\Owner\ClientController as OwnerClientController;
use App\Http\Controllers\Owner\CollectionController as OwnerCollectionController;
use App\Http\Controllers\Owner\MessageController as OwnerMessageController;
use App\Http\Controllers\Owner\AiAssistanceController as OwnerAiAssistanceController;
use App\Http\Controllers\Owner\SettingsController as OwnerSettingsController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Admin\SubscriptionRequestController as AdminSubscriptionRequestController;
use App\Http\Controllers\Owner\SubscriptionController as OwnerSubscriptionController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/roles', [AdminRoleController::class, 'index'])->name('roles.index');
    Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');
    Route::get('/logs', [AdminLogController::class, 'index'])->name('logs.index');
    Route::get('/ai-reports', [AdminAiReportController::class, 'index'])->name('ai-reports.index');
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
    Route::get('/audit', [AdminAuditController::class, 'index'])->name('audit.index');
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    
    // Subscriptions Management
    Route::resource('subscriptions', AdminSubscriptionController::class);
    
    // Subscription Requests Management
    Route::prefix('subscription-requests')->name('subscription-requests.')->group(function () {
        Route::get('/', [AdminSubscriptionRequestController::class, 'index'])->name('index');
        Route::get('/{request}', [AdminSubscriptionRequestController::class, 'show'])->name('show');
        Route::post('/{request}/approve', [AdminSubscriptionRequestController::class, 'approve'])->name('approve');
        Route::post('/{request}/reject', [AdminSubscriptionRequestController::class, 'reject'])->name('reject');
    });
});

// Owner Routes
Route::prefix('owner')->name('owner.')->middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::resource('clients', OwnerClientController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/collections', [OwnerCollectionController::class, 'index'])->name('collections.index');
    Route::get('/messages/create', [OwnerMessageController::class, 'create'])->name('messages.create');
    Route::get('/ai-assistance', [OwnerAiAssistanceController::class, 'index'])->name('ai-assistance.index');
    Route::get('/settings', [OwnerSettingsController::class, 'index'])->name('settings');
    
    // Subscriptions
    Route::get('/subscriptions', [OwnerSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions', [OwnerSubscriptionController::class, 'store'])->name('subscriptions.store');
});
