<?php

/*
|--------------------------------------------------------------------------
| Route Imports
|--------------------------------------------------------------------------
|
| All controller imports are organized alphabetically for better maintainability
*/

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\DriverEarningsController;
use App\Http\Controllers\DriverProfileController;
use App\Http\Controllers\DriverStatsController;
use App\Http\Controllers\DriverVehicleController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OTPVerificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SupportChatController;
use App\Http\Controllers\UserController;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
|
| Routes that don't require authentication (landing page, auth forms)
*/

Route::get('/', fn () => view('welcome'))->name('home');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Login, registration, logout, and OTP verification routes
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::get('/verify-otp/{email}', [OTPVerificationController::class, 'showVerifyForm'])->name('otp.form');
    
    // Password Reset Routes
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password', [PasswordResetController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
});

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle.login');
Route::post('/register', [AuthController::class, 'register'])->name('register')->middleware('guest');
Route::post('/verify-otp', [OTPVerificationController::class, 'verify'])->name('otp.verify');
Route::post('/resend-otp', [OTPVerificationController::class, 'resend'])->name('otp.resend');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
|
| Routes for regular users (customers) - requires 'user' role
*/

Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    // Dashboard & Profile
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [UserController::class, 'updatePassword'])->name('password.update');
    Route::post('/dark-mode', [UserController::class, 'toggleDarkMode'])->name('dark-mode');

    // Food Browsing & Ordering
    Route::get('/foods', [FoodController::class, 'index'])->name('foods');
    Route::get('/foods/{food}', [FoodController::class, 'show'])->name('foods.show');
    Route::get('/order/{food}', [OrderController::class, 'create'])->name('order.create');
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');

    // Order Management
    Route::get('/orders', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/pdf', [OrderController::class, 'pdf'])->name('orders.pdf');
    Route::get('/orders/{order}/reorder', [OrderController::class, 'reorder'])->name('orders.reorder');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/rate', [RatingController::class, 'store'])->name('orders.rate');

    // Cart Management
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
    Route::post('/favorites', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});

/*
|--------------------------------------------------------------------------
| Driver Routes
|--------------------------------------------------------------------------
|
| Routes for delivery drivers - requires 'driver' role
*/

Route::middleware(['auth', 'role:driver'])->prefix('driver')->name('driver.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('dashboard');

    // Order Management
    Route::post('/orders/{order}/accept', [DriverController::class, 'acceptOrder'])->name('orders.accept');
    Route::post('/orders/{order}/reject', [DriverController::class, 'rejectOrder'])->name('orders.reject');
    Route::post('/orders/{order}/delivered', [DriverController::class, 'markDelivered'])->name('orders.delivered');
    Route::post('/location', [DriverController::class, 'updateLocation'])->name('location');
    Route::post('/toggle-availability', [DriverController::class, 'toggleAvailability'])->name('toggle-availability');

    // Profile & Vehicle Settings
    Route::get('/profile', [DriverProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [DriverProfileController::class, 'update'])->name('profile.update');

    // Earnings & Wallet
    Route::get('/earnings', [DriverEarningsController::class, 'index'])->name('earnings');
    Route::get('/earnings/wallet', [DriverEarningsController::class, 'wallet'])->name('earnings.wallet');
    Route::post('/earnings/withdraw', [DriverEarningsController::class, 'withdraw'])->name('earnings.withdraw');
    Route::get('/earnings/withdrawals', [DriverEarningsController::class, 'withdrawals'])->name('earnings.withdrawals');

    // Statistics & Insights
    Route::get('/stats', [DriverStatsController::class, 'index'])->name('stats');

    // Vehicle Maintenance
    Route::get('/vehicle', [DriverVehicleController::class, 'index'])->name('vehicle.index');
    Route::post('/vehicle', [DriverVehicleController::class, 'store'])->name('vehicle.store');
    Route::get('/vehicle/{maintenance}/edit', [DriverVehicleController::class, 'edit'])->name('vehicle.edit');
    Route::put('/vehicle/{maintenance}', [DriverVehicleController::class, 'update'])->name('vehicle.update');
    Route::delete('/vehicle/{maintenance}', [DriverVehicleController::class, 'destroy'])->name('vehicle.destroy');

    // Support
    Route::get('/support', [SupportChatController::class, 'index'])->name('support');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes for administrators - requires 'admin' role and audit middleware
*/

Route::middleware(['auth', 'role:admin', 'audit'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard & Monitoring
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/monitor', [AdminController::class, 'monitor'])->name('monitor');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/suspicious', [AdminController::class, 'suspicious'])->name('suspicious');

    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::patch('/users/{user}/toggle', [AdminController::class, 'toggleActive'])->name('users.toggle');
    Route::patch('/users/{user}/update-role', [AdminController::class, 'updateRole'])->name('users.update-role');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    // Driver Management
    Route::get('/drivers', [AdminController::class, 'drivers'])->name('drivers');
    Route::get('/drivers/create', [AdminController::class, 'createDriver'])->name('drivers.create');
    Route::post('/drivers', [AdminController::class, 'storeDriver'])->name('drivers.store');
    Route::patch('/drivers/{user}/toggle', [AdminController::class, 'toggleDriver'])->name('drivers.toggle');

    // Driver Requests (outside audit middleware for approval/rejection)
    Route::post('/driver-requests/{request}/approve', [AdminController::class, 'approveDriverRequest'])->name('driver-requests.approve');
    Route::post('/driver-requests/{request}/reject', [AdminController::class, 'rejectDriverRequest'])->name('driver-requests.reject');

    // Food Management
    Route::get('/foods', [FoodController::class, 'adminIndex'])->name('foods.index');
    Route::get('/foods/create', [FoodController::class, 'create'])->name('foods.create');
    Route::post('/foods', [FoodController::class, 'store'])->name('foods.store');
    Route::get('/foods/{food}/edit', [FoodController::class, 'edit'])->name('foods.edit');
    Route::put('/foods/{food}', [FoodController::class, 'update'])->name('foods.update');
    Route::delete('/foods/{food}', [FoodController::class, 'destroy'])->name('foods.destroy');
    Route::patch('/foods/{food}/toggle', [FoodController::class, 'toggle'])->name('foods.toggle');

    // Order Management
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::post('/orders/{order}/assign', [OrderController::class, 'assignDriver'])->name('orders.assign');

    // Audit Logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs');

    // Resource Routes
    Route::resource('/promos', PromoCodeController::class)->names('promos');
    Route::resource('/announcements', AnnouncementController::class)->names('announcements');
});

/*
|--------------------------------------------------------------------------
| Shared Routes (All Authenticated Users)
|--------------------------------------------------------------------------
|
| Routes accessible to all authenticated users regardless of role
*/

Route::middleware('auth')->group(function () {
    // Preferences
    Route::post('/dark-mode', [UserController::class, 'toggleDarkMode'])->name('dark-mode');

    // Messaging
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'conversation'])->name('messages.conversation');
    Route::post('/messages', [MessageController::class, 'send'])->name('messages.send');
    Route::get('/messages/contacts', [MessageController::class, 'getContacts'])->name('messages.contacts');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/count', [NotificationController::class, 'count'])->name('notifications.count');

    // Support
    Route::get('/support', [SupportChatController::class, 'index'])->name('support');
    Route::post('/support', [SupportChatController::class, 'send'])->name('support.send');

    // Cart (shared count endpoint)
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Public API endpoints and utility routes
*/

Route::get('/orders/{order}/live', function (Order $order) {
    return response()->json([
        'status' => $order->status,
        'driver_latitude' => $order->driver_latitude,
        'driver_longitude' => $order->driver_longitude,
        'eta_seconds' => $order->countdownSeconds(),
        'driver_name' => $order->driver?->name,
        'driver_phone' => $order->driver?->phone,
    ]);
})->name('orders.live');
