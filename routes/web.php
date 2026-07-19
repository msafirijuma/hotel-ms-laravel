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


// ====================== PUBLIC ROUTES (Anyone) ======================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// ====================== PROTECTED ROUTES (Authenticated users only) =====================
Route::middleware('auth')->group(function () {

    // Dashboard Module
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Rooms Module
    Route::resource('room-types', RoomTypeController::class)->middleware('role:admin');
    Route::resource('rooms', RoomController::class);
    Route::patch('/rooms/{room}/update-status', [RoomController::class, 'updateStatus'])->name('rooms.update-status');

    // Room Types Gallery Module
    Route::delete('/room-types/gallery/{id}', [RoomTypeController::class, 'destroyGalleryImage'])->name('room-types.gallery.destroy');
    Route::get('/room-types/gallery/{id}/set-primary', [RoomTypeController::class, 'setPrimaryImage'])->name('room-types.gallery.primary');

    // Guest Module
    Route::resource('guests', GuestController::class);

    // User Module
    Route::resource('users', UserController::class);
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/show', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

    // Bookings Module
    Route::resource('bookings', BookingController::class);
    Route::get('/checkin-checkout', [BookingController::class, 'checkInOut'])->name('bookings.checkin-checkout');
    Route::post('/bookings/{booking}/checkin', [BookingController::class, 'checkin'])->name('bookings.checkin');
    Route::post('/bookings/{booking}/checkout', [BookingController::class, 'checkout'])->name('bookings.checkout');
    Route::patch('/bookings/{booking}/update-status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');

    // Payments Module
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/bookings/{booking}/pay', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments/store', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/invoice/{id}', [PaymentController::class, 'showInvoice'])->name('payments.invoice');

    // Reports Module
    Route::middleware(['auth'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

    // Audit Logs Module
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

    // Shift Module
    Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
    Route::post('/shifts', [ShiftController::class, 'store'])->name('shifts.store');
    Route::get('/manage-shifts/{shift}/edit', [ShiftController::class, 'edit'])->name('shifts.edit');
    Route::put('/manage-shifts/{shift}', [ShiftController::class, 'update'])->name('shifts.update');
    Route::delete('/shifts/{shift}', [ShiftController::class, 'destroy'])->name('shifts.destroy');

    // Staff Schedules Module
    Route::get('/staff-schedules', [StaffScheduleController::class, 'index'])->name('staff-schedules.index');
    Route::post('/staff-schedules', [StaffScheduleController::class, 'store'])->name('staff-schedules.store');

    // Setting Module
    Route::get('/settings', [HotelSettingController::class, 'show'])->name('settings.show');
    Route::get('/settings/edit', [HotelSettingController::class, 'edit'])->name('settings.edit');
    Route::post('/settings/update', [HotelSettingController::class, 'update'])->name('settings.update');

    // Housekeeping Tasks Module
    Route::get('/housekeeping', [HousekeepingController::class, 'index'])->name('housekeeping.index');

    // Assign Form View
    Route::get('/housekeeping/assign', [HousekeepingController::class, 'assign'])->name('housekeeping.assign');

    // Assigning Tasks (Manual and Auto) & Task Status Updates
    Route::post('/housekeeping/assign/manual', [HousekeepingController::class, 'assignManual'])->name('housekeeping.assign.manual');
    Route::post('/housekeeping/assign/auto', [HousekeepingController::class, 'assignAuto'])->name('housekeeping.assign.auto');
    Route::patch('/housekeeping/tasks/{task}/start', [HousekeepingController::class, 'startCleaning'])->name('housekeeping.tasks.start');
    Route::patch('/housekeeping/tasks/{task}/complete', [HousekeepingController::class, 'completeCleaning'])->name('housekeeping.tasks.complete');

    // Housekeeping cleaning history
    Route::get('/housekeeping/history', [HousekeepingController::class, 'cleaningHistory'])->name('housekeeping.history');

    // Dirty rooms
    Route::get('/housekeeping/dirty-rooms', [HousekeepingController::class, 'dirtyRooms'])->name('housekeeping.dirty-rooms');

    // Maintenance Module
    Route::post('/housekeeping/task/{task}/report-issue', [HousekeepingController::class, 'reportIssue'])->name('housekeeping.report-issue');
    Route::get('/maintenance/logs', [HousekeepingController::class, 'maintenanceLogs'])->name('maintenance.logs');

    // Mark room fixed
    Route::post('/maintenance/{id}/fixed', [HousekeepingController::class, 'markAsFixed'])->name('maintenance.fixed');

    // My Work Schedule
    Route::get('/housekeeping/my-schedule', [HousekeepingController::class, 'mySchedule'])->name('housekeeping.my-schedule');


    // Role Protected Routes
    Route::middleware('role:admin,manager')->prefix('admin')->name('admin.')->group(function () {
        // Admin & Manager routes
    });

    Route::middleware('role:receptionist')->prefix('reception')->name('reception.')->group(function () {
        // Reception routes
    });

    Route::middleware('role:housekeeper')->prefix('housekeeping')->name('housekeeping.')->group(function () {
        // Housekeeping routes
    });

    // All authenticated users (Admin, Manager, Receptionist, Housekeeper) can access these routes
    Route::group([], function () {
        // Profile Routes
        Route::get('/my-profile', [DashboardController::class, 'myProfile'])->name('my-profile');
        Route::get('/profile/edit', [DashboardController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update');

        // Change Password
        Route::get('/change-password', [DashboardController::class, 'changePassword'])->name('password.change');
        Route::put('/change-password', [DashboardController::class, 'updatePassword'])->name('password.update');
    });

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
