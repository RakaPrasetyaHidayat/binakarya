<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('homepage');
Route::get('/tentang-kami', [AboutController::class, 'index'])->name('about');
Route::get('/layanan', [ServiceController::class, 'index'])->name('services.index');
Route::get('/layanan/{slug}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/buku', [BookController::class, 'index'])->name('books.index');
Route::get('/buku/{slug}', [BookController::class, 'show'])->name('books.show');
Route::get('/kontak', [ContactController::class, 'index'])->name('contact');
Route::get('/p/{slug}', [App\Http\Controllers\PageController::class, 'show'])->name('pages.show');
Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store')
    ->middleware('throttle:contact');

// Subscribe newsletter
Route::post('/subscribe', [SubscriberController::class, 'store'])->name('subscribe')
    ->middleware('throttle:subscribe');

// Privacy & Terms
Route::get('/kebijakan-privasi', function () {
    return view('public.privacy');
})->name('privacy');

Route::get('/syarat-ketentuan', function () {
    return view('public.terms');
})->name('terms');

// PDF Serving
Route::get('/pdf/{filename}', [PdfController::class, 'show'])->name('pdf.show')->where('filename', '.*');

// Sitemap & Robots
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap-index.xml', [SitemapController::class, 'sitemapIndex'])->name('sitemap.index');
Route::get('/sitemaps/pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemaps/books.xml', [SitemapController::class, 'books'])->name('sitemap.books');
Route::get('/sitemaps/blog.xml', [SitemapController::class, 'blog'])->name('sitemap.blog');
Route::get('/sitemaps/services.xml', [SitemapController::class, 'services'])->name('sitemap.services');
Route::get('/sitemaps/news.xml', [SitemapController::class, 'news'])->name('sitemap.news');
Route::get('/robots.txt', [RobotsController::class, 'index'])->name('robots');

// Auth routes (Breeze)
require __DIR__.'/auth.php';

// Admin routes - Rate limited to 30 requests per minute
Route::prefix('cendikiaByRidwanullah')->name('admin.')->middleware(['auth', 'throttle:30,1'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('books', Admin\BookController::class)->middleware('role:admin');
    Route::resource('posts', Admin\PostController::class)->except(['show']);
    Route::resource('services', Admin\ServiceController::class)->middleware('role:admin');
    Route::resource('categories', Admin\CategoryController::class)->except(['show', 'create', 'edit'])->middleware('role:admin');
    Route::resource('contacts', Admin\ContactController::class)->only(['index', 'show', 'destroy'])->middleware('role:admin');
    Route::resource('users', Admin\UserController::class)->except(['show', 'create', 'edit'])
        ->middleware('role:admin');
    Route::resource('menus', Admin\MenuController::class)->middleware('role:admin');
    Route::post('menus/{menu}/toggle-active', [Admin\MenuController::class, 'toggleActive'])->name('menus.toggle-active')->middleware('role:admin');
    Route::post('menus/reorder', [Admin\MenuController::class, 'reorder'])->name('menus.reorder')->middleware('role:admin');
    
    // Menu Builder (Visual Interface)
    Route::get('menu-builder', [Admin\MenuBuilderController::class, 'index'])->name('menu-builder.index')->middleware('role:admin');
    Route::get('menu-builder/tree', [Admin\MenuBuilderController::class, 'getMenuTree'])->name('menu-builder.tree')->middleware('role:admin');
    Route::post('menu-builder/update', [Admin\MenuBuilderController::class, 'updateMenuTree'])->name('menu-builder.update')->middleware('role:admin');
    
    // Page Customizer (Full Content Management)
    Route::get('page-customizer', [Admin\PageCustomizerController::class, 'index'])->name('page-customizer.index')->middleware('role:admin');
    Route::get('page-customizer/{page}', [Admin\PageCustomizerController::class, 'show'])->name('page-customizer.show')->middleware('role:admin');
    Route::put('page-customizer/{page}', [Admin\PageCustomizerController::class, 'update'])->name('page-customizer.update')->middleware('role:admin');
    
    Route::resource('pages', Admin\PageController::class)->middleware('role:admin');

    // Team Members CRUD
    Route::resource('team-members', Admin\TeamMemberController::class)->middleware('role:admin');

    // TinyMCE Settings
    Route::get('tinymce-settings', [Admin\TinymceSettingController::class, 'index'])->name('tinymce-settings.index')->middleware('role:admin');
    Route::put('tinymce-settings', [Admin\TinymceSettingController::class, 'update'])->name('tinymce-settings.update')->middleware('role:admin');

    // Mail Settings
    Route::get('mail-settings', [Admin\MailSettingController::class, 'index'])->name('mail-settings.index')->middleware('role:admin');
    Route::put('mail-settings', [Admin\MailSettingController::class, 'update'])->name('mail-settings.update')->middleware('role:admin');
    Route::post('mail-settings/test', [Admin\MailSettingController::class, 'test'])->name('mail-settings.test')->middleware('role:admin');

    // Google Scholar Settings
    Route::get('scholar-settings', [Admin\ScholarSettingController::class, 'index'])->name('scholar-settings.index')->middleware('role:admin');
    Route::put('scholar-settings', [Admin\ScholarSettingController::class, 'update'])->name('scholar-settings.update')->middleware('role:admin');
    Route::post('scholar-settings/sync', [Admin\ScholarSettingController::class, 'sync'])->name('scholar-settings.sync')->middleware('role:admin');

    // Audit Logs (Admin only)
    // Subscribers
    Route::get('subscribers', [Admin\SubscriberController::class, 'index'])->name('subscribers.index')->middleware('role:admin');
    Route::delete('subscribers/{subscriber}', [Admin\SubscriberController::class, 'destroy'])->name('subscribers.destroy')->middleware('role:admin');
    Route::post('subscribers/broadcast', [Admin\SubscriberController::class, 'broadcast'])->name('subscribers.broadcast')->middleware('role:admin');

    Route::get('audit-logs', [Admin\AuditLogController::class, 'index'])->name('audit-logs.index')->middleware('role:admin');
    Route::get('audit-logs/{auditLog}', [Admin\AuditLogController::class, 'show'])->name('audit-logs.show')->middleware('role:admin');

    Route::get('settings', [Admin\SettingController::class, 'index'])->name('settings.index')->middleware('role:admin');
    Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update')->middleware('role:admin');
});
