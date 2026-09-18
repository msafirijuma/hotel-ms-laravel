<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\HotelSettingController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\StaffScheduleController;
use App\Http\Controllers\HousekeepingController;
use App\Http\Controllers\MaintenanceLogController;
use App\Http\Controllers\NotificationController;

// ====================== PUBLIC ROUTES ======================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// ====================== PROTECTED ROUTES ======================
Route::middleware('auth')->group(function () {

    // Dashboard (all authenticated users)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ====================== ADMIN ONLY ======================
    Route::middleware('role:admin')->group(function () {
        // Room Types
        Route::resource('room-types', RoomTypeController::class);
        Route::delete('/room-types/gallery/{id}', [RoomTypeController::class, 'destroyGalleryImage'])->name('room-types.gallery.destroy');
        Route::get('/room-types/gallery/{id}/set-primary', [RoomTypeController::class, 'setPrimaryImage'])->name('room-types.gallery.primary');

        // Users
        Route::resource('users', UserController::class);

        // Settings
        Route::get('/settings', [HotelSettingController::class, 'show'])->name('settings.show');
        Route::get('/settings/edit', [HotelSettingController::class, 'edit'])->name('settings.edit');
        Route::post('/settings/update', [HotelSettingController::class, 'update'])->name('settings.update');

        // Shifts
        Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
        Route::post('/shifts', [ShiftController::class, 'store'])->name('shifts.store');
        Route::get('/manage-shifts/{shift}/edit', [ShiftController::class, 'edit'])->name('shifts.edit');
        Route::put('/manage-shifts/{shift}', [ShiftController::class, 'update'])->name('shifts.update');
        Route::delete('/shifts/{shift}', [ShiftController::class, 'destroy'])->name('shifts.destroy');

        // Audit Logs
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    });

    // ====================== ADMIN + MANAGER ======================
    Route::middleware('role:admin,manager')->group(function () {
        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        // Staff Schedules
        Route::get('/staff-schedules', [StaffScheduleController::class, 'index'])->name('staff-schedules.index');
        Route::post('/staff-schedules', [StaffScheduleController::class, 'store'])->name('staff-schedules.store');
        Route::get('/staff-scheduling/{schedule}/edit', [StaffScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('/staff-scheduling/{schedule}', [StaffScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('/staff-scheduling/{id}', [StaffScheduleController::class, 'destroy'])->name('schedules.destroy');
    });

    // ====================== ADMIN + MANAGER + RECEPTIONIST ======================
    Route::middleware('role:admin,manager,receptionist')->group(function () {
        // Rooms
        Route::resource('rooms', RoomController::class);
        Route::patch('/rooms/{room}/update-status', [RoomController::class, 'updateStatus'])->name('rooms.update-status');

        // Guests
        Route::resource('guests', GuestController::class);

        // Bookings
        Route::resource('bookings', BookingController::class);
        Route::get('/checkin-checkout', [BookingController::class, 'checkInOut'])->name('bookings.checkin-checkout');
        Route::post('/bookings/{booking}/checkin', [BookingController::class, 'checkin'])->name('bookings.checkin');
        Route::post('/bookings/{booking}/checkout', [BookingController::class, 'checkout'])->name('bookings.checkout');
        Route::patch('/bookings/{booking}/update-status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');

        // Payments
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
        Route::get('/payments/{payment}/invoice', [PaymentController::class, 'invoice'])->name('payments.invoice');

        // Housekeeping (Assign tasks - front desk / admin)
        Route::get('/housekeeping', [HousekeepingController::class, 'index'])->name('housekeeping.index');
        Route::get('/housekeeping/assign', [HousekeepingController::class, 'assign'])->name('housekeeping.assign');
        Route::post('/housekeeping/assign/manual', [HousekeepingController::class, 'assignManual'])->name('housekeeping.assign.manual');
        Route::post('/housekeeping/assign/auto', [HousekeepingController::class, 'assignAuto'])->name('housekeeping.assign.auto');
        Route::get('/housekeeping/dirty-rooms', [HousekeepingController::class, 'dirtyRooms'])->name('housekeeping.dirty-rooms');
        Route::get('/housekeeping/history', [HousekeepingController::class, 'cleaningHistory'])->name('housekeeping.history');

        // Maintenance
        Route::get('/maintenance/logs', [MaintenanceLogController::class, 'index'])->name('maintenance-logs.index');
        Route::post('/maintenance/{task}/report-issue', [MaintenanceLogController::class, 'reportIssue'])->name('logs.report-issue');
        Route::post('/maintenance/{log}/fixed', [MaintenanceLogController::class, 'markAsFixed'])->name('maintenance.fixed');
    });

    // ====================== HOUSEKEEPER ======================
    Route::middleware('role:housekeeper')->group(function () {
        // My Tasks
        Route::get('/housekeeping/my-tasks', [HousekeepingController::class, 'myTasks'])->name('housekeeping.my-tasks');

        // My Schedule
        Route::get('/housekeeping/my-schedule', [HousekeepingController::class, 'mySchedule'])->name('housekeeping.my-schedule');

        // Dirty Rooms
        Route::get('/housekeeping/dirty-rooms', [HousekeepingController::class, 'dirtyRooms'])->name('housekeeping.dirty-rooms');

        // Task History (cleaning history)
        Route::get('/housekeeping/history', [HousekeepingController::class, 'cleaningHistory'])->name('housekeeping.history');

        // Start / Complete Task
        Route::patch('/housekeeping/tasks/{task}/start', [HousekeepingController::class, 'startCleaning'])->name('housekeeping.tasks.start');
        Route::patch('/housekeeping/tasks/{task}/complete', [HousekeepingController::class, 'completeCleaning'])->name('housekeeping.tasks.complete');
    });

    // ====================== ALL AUTHENTICATED USERS ======================
    Route::group([], function () {
        // Profile
        Route::get('/my-profile', [DashboardController::class, 'myProfile'])->name('my-profile');
        Route::get('/profile/edit', [DashboardController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update');

        // Change Password
        Route::get('/change-password', [DashboardController::class, 'changePassword'])->name('password.change');
        Route::put('/change-password', [DashboardController::class, 'updatePassword'])->name('password.update');

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    });

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});