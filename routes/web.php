<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');

// Admin Auth Routes
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');

// Custom Forgot Password Routes
Route::get('/admin/forgot-password', [AdminController::class, 'showForgotPassword'])->name('admin.password.request');
Route::post('/admin/forgot-password', [AdminController::class, 'sendResetCode'])->name('admin.password.email');
Route::get('/admin/verify-code', [AdminController::class, 'showVerifyCode'])->name('admin.password.verify.show');
Route::post('/admin/verify-code', [AdminController::class, 'verifyCode'])->name('admin.password.verify');
Route::get('/admin/reset-password', [AdminController::class, 'showResetPassword'])->name('admin.password.reset.show');
Route::post('/admin/reset-password', [AdminController::class, 'resetPassword'])->name('admin.password.reset');

// Protected Admin Routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    
    // Profile Management
    Route::get('/profile', [AdminController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');

    // Inquiries Listing
    Route::get('/inquiries', [AdminController::class, 'indexInquiries'])->name('inquiries.index');

    // Categories CRUD
    Route::get('/categories', [AdminController::class, 'indexCategories'])->name('categories.index');
    Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories/create', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
    Route::post('/categories/{category}/edit', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::post('/categories/{category}/delete', [AdminController::class, 'deleteCategory'])->name('categories.delete');

    // Products CRUD
    Route::get('/products', [AdminController::class, 'indexProducts'])->name('products.index');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products/create', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::post('/products/{product}/edit', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::post('/products/{product}/delete', [AdminController::class, 'deleteProduct'])->name('products.delete');

    // User Management
    Route::get('/users', [AdminController::class, 'indexUsers'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users/create', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::post('/users/{user}/edit', [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{user}/delete', [AdminController::class, 'deleteUser'])->name('users.delete');

    // System Logs
    Route::get('/logs/visitors', [AdminController::class, 'indexVisitorLogs'])->name('logs.visitors');
    Route::get('/logs/activity', [AdminController::class, 'indexActivityLogs'])->name('logs.activity');

    // System Settings Configuration
    Route::get('/settings', [AdminController::class, 'editSettings'])->name('settings.edit');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

    // Hero Slider CRUD
    Route::get('/sliders', [AdminController::class, 'indexSliders'])->name('sliders.index');
    Route::get('/sliders/create', [AdminController::class, 'createSlider'])->name('sliders.create');
    Route::post('/sliders/create', [AdminController::class, 'storeSlider'])->name('sliders.store');
    Route::get('/sliders/{slider}/edit', [AdminController::class, 'editSlider'])->name('sliders.edit');
    Route::post('/sliders/{slider}/edit', [AdminController::class, 'updateSlider'])->name('sliders.update');
    Route::post('/sliders/{slider}/delete', [AdminController::class, 'deleteSlider'])->name('sliders.delete');
});
