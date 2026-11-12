<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\ToolsController;
use App\Http\Controllers\UnitController;
use App\Http\Middleware\AdminBasicAuth;

/*
|--------------------------------------------------------------------------
| Kedia Builders & Colonizers — web routes
|--------------------------------------------------------------------------
*/

Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop: projects and units
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');
// Unit codes are only unique within a project, so unit URLs include the project slug.
Route::get('/projects/{slug}/units/{unitCode}', [UnitController::class, 'show'])->name('units.show');
Route::get('/unit/{unitCode}', [UnitController::class, 'legacy'])->name('units.legacy');
Route::get('/units/{unit}/cost-sheet', [UnitController::class, 'costSheet'])->whereNumber('unit')->name('units.cost-sheet');
Route::get('/compare', [CompareController::class, 'index'])->name('compare');

// Checkout: cost sheet & details -> test-mode payment gateway -> signed voucher
Route::get('/booking/{unit}', [BookingController::class, 'checkout'])->whereNumber('unit')->name('booking.checkout');
Route::post('/booking/store', [BookingController::class, 'store'])->middleware('throttle:10,1')->name('booking.store');
Route::get('/booking/pay/{bookingId}', [BookingController::class, 'payment'])->middleware('signed')->name('booking.pay');
Route::post('/booking/pay/{bookingId}', [BookingController::class, 'processPayment'])->middleware(['signed', 'throttle:10,1'])->name('booking.pay.process');
Route::get('/booking/confirmation/{bookingId}', [BookingController::class, 'confirmation'])->middleware('signed')->name('booking.confirmation');

// Customer account (OTP login)
Route::get('/account', [AccountController::class, 'index'])->name('account');
Route::post('/account/otp', [AccountController::class, 'sendOtp'])->middleware('throttle:6,1')->name('account.otp');
Route::post('/account/verify', [AccountController::class, 'verify'])->middleware('throttle:10,1')->name('account.verify');
Route::post('/account/logout', [AccountController::class, 'logout'])->name('account.logout');
Route::redirect('/dashboard', '/account')->name('customer.dashboard');

// Admin (HTTP Basic Auth, see .env ADMIN_USER / ADMIN_PASSWORD)
Route::prefix('admin')->middleware([AdminBasicAuth::class, 'throttle:120,1'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/inventory', [AdminController::class, 'inventory'])->name('admin.inventory');
    Route::get('/units/{id}/edit', [AdminController::class, 'editUnit'])->name('admin.units.edit');
    Route::put('/units/{id}', [AdminController::class, 'updateUnit'])->name('admin.units.update');
    Route::post('/units/{id}/status', [AdminController::class, 'updateUnitStatus'])->name('admin.units.status');
    Route::post('/units/{id}/price', [AdminController::class, 'updateUnitPrice'])->name('admin.units.price');
    Route::post('/units', [AdminController::class, 'storeUnit'])->name('admin.units.store');
    Route::delete('/units/{id}', [AdminController::class, 'destroyUnit'])->name('admin.units.destroy');
    Route::get('/projects', [AdminController::class, 'projects'])->name('admin.projects');
    Route::get('/projects/create', [AdminController::class, 'createProject'])->name('admin.projects.create');
    Route::post('/projects', [AdminController::class, 'storeProject'])->name('admin.projects.store');
    Route::get('/projects/{id}/edit', [AdminController::class, 'editProject'])->name('admin.projects.edit');
    Route::put('/projects/{id}', [AdminController::class, 'updateProject'])->name('admin.projects.update');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
    Route::post('/bookings/{id}/status', [AdminController::class, 'updateBookingStatus'])->name('admin.bookings.status');
    Route::get('/leads', [AdminController::class, 'leads'])->name('admin.leads');
    Route::put('/leads/{id}', [AdminController::class, 'updateLead'])->name('admin.leads.update');
    Route::get('/export/orders', [AdminController::class, 'exportOrders'])->name('admin.export.orders');
    Route::get('/export/catalog', [AdminController::class, 'exportCatalog'])->name('admin.export.catalog');
});

// Enquiries (visit booking, contact, careers, NRI, loan enquiry)
Route::post('/lead/site-visit', [LeadController::class, 'store'])->middleware('throttle:10,1')->name('leads.store');

// Editorial & information pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/about/chairman', [PageController::class, 'chairman'])->name('about.chairman');
Route::get('/about/vision-mission', [PageController::class, 'vision'])->name('about.vision');
Route::get('/why-invest', [PageController::class, 'why'])->name('about.why');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/tools', [ToolsController::class, 'index'])->name('tools');
Route::get('/nri', [PageController::class, 'nri'])->name('nri');
Route::get('/careers', [PageController::class, 'careers'])->name('careers');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/legal', [PageController::class, 'legal'])->name('legal');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('legal.privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('legal.terms');
Route::get('/refund-booking-policy', [PageController::class, 'refund'])->name('legal.refund');

// SEO
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');

// cPanel Deployment & Diagnostics (Protected by ?key= parameter)
Route::get('/cpanel-setup', [\App\Http\Controllers\CpanelSetupController::class, 'index'])->name('cpanel.setup');
Route::post('/cpanel-setup/action', [\App\Http\Controllers\CpanelSetupController::class, 'runAction'])->name('cpanel.action');

