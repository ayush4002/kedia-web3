<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ToolsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Web Routes - Kedia Builders & Colonizers
|--------------------------------------------------------------------------
*/

// Language Switcher (EN <-> HI)
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Main Homepage (Spaciaz Luxury Blueprint layout)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Projects Catalog & Detail
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');

// Unit Product Page
// Unit codes are only unique within a project, so unit URLs include the project slug.
Route::get('/projects/{slug}/units/{unitCode}', [UnitController::class, 'show'])->name('units.show');
Route::get('/unit/{unitCode}', [UnitController::class, 'legacy'])->name('units.legacy');

// E-Commerce Booking & Checkout Flow
Route::get('/booking/{unit}', [BookingController::class, 'checkout'])->whereNumber('unit')->name('booking.checkout');
Route::post('/booking/store', [BookingController::class, 'store'])->middleware('throttle:10,1')->name('booking.store');
Route::get('/booking/confirmation/{bookingId}', [BookingController::class, 'confirmation'])->name('booking.confirmation');

// Customer Dashboard & Holding Tracker
Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->middleware('throttle:20,1')->name('customer.dashboard');

// Admin E-Commerce Command Center
Route::prefix('admin')->middleware([\App\Http\Middleware\AdminBasicAuth::class, 'throttle:60,1'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/units/{id}/status', [AdminController::class, 'updateUnitStatus'])->name('admin.units.status');
    Route::post('/units/{id}/price', [AdminController::class, 'updateUnitPrice'])->name('admin.units.price');
    Route::post('/units', [AdminController::class, 'storeUnit'])->name('admin.units.store');
    Route::delete('/units/{id}', [AdminController::class, 'destroyUnit'])->name('admin.units.destroy');
    Route::post('/bookings/{id}/status', [AdminController::class, 'updateBookingStatus'])->name('admin.bookings.status');
    Route::get('/export/orders', [AdminController::class, 'exportOrders'])->name('admin.export.orders');
    Route::get('/export/catalog', [AdminController::class, 'exportCatalog'])->name('admin.export.catalog');
});

// Inbound Site Visit & Callback Inquiries
Route::post('/lead/site-visit', [LeadController::class, 'store'])->middleware('throttle:10,1')->name('leads.store');

// Static / Informational Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/tools', [ToolsController::class, 'index'])->name('tools');
Route::get('/nri', [PageController::class, 'nri'])->name('nri');
Route::get('/careers', [PageController::class, 'careers'])->name('careers');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/legal', [PageController::class, 'legal'])->name('legal');
