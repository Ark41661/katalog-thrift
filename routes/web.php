<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\UgcController;
use App\Http\Controllers\VipSubscriberController;
use App\Http\Controllers\OutfitShareController;
use App\Http\Controllers\AdminOutfitController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminPartnerController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminReviewController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AdminArticleController;
use App\Http\Controllers\AdminUgcController;
use App\Http\Controllers\AdminLandingController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\AdminBadgeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\PublicPartnerController;
use App\Http\Controllers\Member\OutfitSaveController;
use App\Http\Controllers\Member\MemberAuthController;
use App\Http\Controllers\Member\ReviewController;
use App\Http\Controllers\Member\WishlistController;
use App\Http\Controllers\Member\ReportController;
use App\Http\Controllers\Member\FollowerController;
use App\Http\Controllers\Member\QuestionController;
use App\Http\Controllers\Member\NotificationController as MemberNotificationController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\Member\BadgeController;
use App\Http\Controllers\Partner\PartnerOutfitController;
use App\Http\Controllers\Partner\PartnerAuthController;
use App\Http\Controllers\Partner\PartnerDashboardController;
use App\Http\Controllers\Partner\PartnerProductController;
use App\Http\Controllers\Partner\PartnerProfileController;
use App\Http\Controllers\Partner\PartnerAnalyticsController;
use App\Http\Controllers\Partner\PartnerBulkController;
use App\Http\Controllers\Partner\PartnerQuestionController;
use App\Http\Controllers\WebReportController;
use App\Http\Controllers\Admin\AdminWebReportController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

// ─── PUBLIC ROUTES ────────────────────────────────────────────────────────────
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/lookbook', [CatalogController::class, 'lookbook'])->name('catalog.lookbook');
Route::get('/tentang', [CatalogController::class, 'about'])->name('catalog.about');
Route::get('/produk/{slug}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/produk/{slug}/wa-click', [CatalogController::class, 'trackWaClick'])->name('catalog.wa-click');
Route::get('/outfit/s/{token}', [OutfitShareController::class, 'show'])->name('outfit.share');

// ─── SEARCH ────────────────────────────────────────────────────────────────────
Route::get('/cari', [SearchController::class, 'index'])->name('search.index');
Route::get('/cari/ajax', [SearchController::class, 'ajax'])->name('search.ajax');

// ─── EDITORIAL / BLOG ─────────────────────────────────────────────────────────
Route::get('/editorial', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/editorial/{slug}', [ArticleController::class, 'show'])->name('articles.show');

// ─── KOMUNITAS & UGC ──────────────────────────────────────────────────────────
Route::get('/komunitas', [CommunityController::class, 'index'])->name('community.index');
Route::post('/komunitas/kirim-foto', [UgcController::class, 'submit'])->name('ugc.submit');

// ─── WEB REPORT ───────────────────────────────────────────────────────────────
Route::get('/laporan-web', [WebReportController::class, 'create'])->name('web-report.create');
Route::post('/laporan-web', [WebReportController::class, 'store'])->name('web-report.store');

// ─── VIP MEMBERSHIP ───────────────────────────────────────────────────────────
Route::post('/vip/subscribe', [VipSubscriberController::class, 'subscribe'])->name('vip.subscribe');
Route::get('/vip/unsubscribe/{token}', [VipSubscriberController::class, 'unsubscribe'])->name('vip.unsubscribe');

// ─── PUBLIC PARTNER PAGES ─────────────────────────────────────────────────────
Route::get('/toko', [PublicPartnerController::class, 'index'])->name('partners.index');
Route::get('/toko/{slug}', [PublicPartnerController::class, 'show'])->name('partners.show');
Route::get('/daftar-mitra', [PublicPartnerController::class, 'registerForm'])->name('partner.register');
Route::post('/daftar-mitra', [PublicPartnerController::class, 'register'])->name('partner.register.submit');
Route::get('/daftar-mitra/sukses', [PublicPartnerController::class, 'registerSuccess'])->name('partner.register.success');

// ─── MEMBER AUTH ──────────────────────────────────────────────────────────────
Route::get('/login', [MemberAuthController::class, 'showLogin'])->name('member.login');
Route::post('/login', [MemberAuthController::class, 'login'])->name('member.login.submit');
Route::get('/register', [MemberAuthController::class, 'showRegister'])->name('member.register');
Route::post('/register', [MemberAuthController::class, 'register'])->name('member.register.submit');
Route::post('/logout', [MemberAuthController::class, 'logout'])->name('member.logout');

// ─── FORGOT PASSWORD ──────────────────────────────────────────────────────────
Route::get('/lupa-password', [MemberAuthController::class, 'showForgotPassword'])->name('member.forgot');
Route::post('/lupa-password', [MemberAuthController::class, 'sendResetLink'])->name('member.forgot.submit');
Route::get('/reset-password/{token}', [MemberAuthController::class, 'showResetForm'])->name('member.reset');
Route::post('/reset-password', [MemberAuthController::class, 'resetPassword'])->name('member.reset.submit');

// ─── GOOGLE OAUTH ─────────────────────────────────────────────────────────────
Route::get('/auth/google/redirect', [MemberAuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [MemberAuthController::class, 'handleGoogleCallback'])->name('google.callback');

// ─── MEMBER ACTIONS (login required) ──────────────────────────────────────────
Route::middleware('member.auth')->group(function () {
    Route::post('/produk/{slug}/review', [ReviewController::class, 'store'])->name('review.store');
    Route::delete('/produk/{slug}/review', [ReviewController::class, 'destroy'])->name('review.destroy');
    Route::post('/produk/{slug}/report', [ReportController::class, 'store'])->name('report.store');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{slug}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/saved-outfits', [OutfitSaveController::class, 'index'])->name('outfit.saved');
    Route::post('/outfit/{outfit}/save', [OutfitSaveController::class, 'toggle'])->name('outfit.save.toggle');

    // Follow
    Route::post('/mitra/{partner}/follow', [FollowerController::class, 'toggle'])->name('partner.follow');
    Route::get('/following', [FollowerController::class, 'following'])->name('member.following');

    // Q&A
    Route::post('/produk/{slug}/question', [QuestionController::class, 'store'])->name('question.store');

    // Notifications
    Route::get('/notifikasi', [MemberNotificationController::class, 'index'])->name('member.notifications');
    Route::post('/notifikasi/{id}/read', [MemberNotificationController::class, 'markRead'])->name('member.notification.read');
    Route::post('/notifikasi/read-all', [MemberNotificationController::class, 'markAllRead'])->name('member.notifications.read-all');

    // Profile
    Route::get('/profil', [ProfileController::class, 'edit'])->name('member.profile');
    Route::put('/profil', [ProfileController::class, 'update'])->name('member.profile.update');

    // Badges
    Route::get('/badges', [BadgeController::class, 'index'])->name('member.badges');
});

// ─── PARTNER AUTH ─────────────────────────────────────────────────────────────
Route::prefix('mitra')->name('partner.')->group(function () {
    Route::get('/login', [PartnerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [PartnerAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [PartnerAuthController::class, 'logout'])->name('logout');

    Route::middleware('partner.auth')->group(function () {
        Route::get('/dashboard', [PartnerDashboardController::class, 'index'])->name('dashboard');

        // Produk
        Route::get('/produk', [PartnerProductController::class, 'index'])->name('products.index');
        Route::get('/produk/tambah', [PartnerProductController::class, 'create'])->name('products.create');
        Route::post('/produk', [PartnerProductController::class, 'store'])->name('products.store');
        Route::get('/produk/{product}/edit', [PartnerProductController::class, 'edit'])->name('products.edit');
        Route::put('/produk/{product}', [PartnerProductController::class, 'update'])->name('products.update');
        Route::delete('/produk/{product}', [PartnerProductController::class, 'destroy'])->name('products.destroy');

        // Bulk Operations
        Route::post('/produk/bulk-update', [PartnerBulkController::class, 'bulkUpdate'])->name('products.bulk-update');
        Route::post('/produk/bulk-delete', [PartnerBulkController::class, 'bulkDelete'])->name('products.bulk-delete');
        Route::post('/produk/export', [PartnerBulkController::class, 'export'])->name('products.export');

        // Variants
        Route::post('/produk/{product}/variants', [PartnerProductController::class, 'saveVariants'])->name('products.variants');
        Route::delete('/produk/{product}/variants/{variant}', [PartnerProductController::class, 'destroyVariant'])->name('products.variants.destroy');

        // Profil toko
        Route::get('/profil', [PartnerProfileController::class, 'edit'])->name('profile');
        Route::put('/profil', [PartnerProfileController::class, 'update'])->name('profile.update');

        // Outfit mitra
        Route::get('/outfit', [PartnerOutfitController::class, 'index'])->name('outfits.index');
        Route::get('/outfit/buat', [PartnerOutfitController::class, 'create'])->name('outfits.create');
        Route::post('/outfit', [PartnerOutfitController::class, 'store'])->name('outfits.store');
        Route::delete('/outfit/{outfit}', [PartnerOutfitController::class, 'destroy'])->name('outfits.destroy');

        // Analytics
        Route::get('/analitik', [PartnerAnalyticsController::class, 'index'])->name('analytics');
        Route::get('/analitik/data', [PartnerAnalyticsController::class, 'data'])->name('analytics.data');

        // Questions from members
        Route::get('/pertanyaan', [PartnerQuestionController::class, 'index'])->name('questions.index');
        Route::put('/pertanyaan/{question}/jawab', [PartnerQuestionController::class, 'answer'])->name('questions.answer');

        // Notifications
        Route::get('/notifikasi', [\App\Http\Controllers\Partner\PartnerNotificationController::class, 'index'])->name('notifications');
        Route::post('/notifikasi/{id}/read', [\App\Http\Controllers\Partner\PartnerNotificationController::class, 'markRead'])->name('notification.read');
        Route::post('/notifikasi/read-all', [\App\Http\Controllers\Partner\PartnerNotificationController::class, 'markAllRead'])->name('notifications.read-all');
    });
});

// ─── ADMIN AREA ───────────────────────────────────────────────────────────────
Route::prefix(config('admin.entry_path'))->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

        // Kelola Mitra
        Route::get('/mitra', [AdminPartnerController::class, 'index'])->name('admin.partners.index');
        Route::put('/mitra/{partner}/approve', [AdminPartnerController::class, 'approve'])->name('admin.partners.approve');
        Route::put('/mitra/{partner}/reject', [AdminPartnerController::class, 'reject'])->name('admin.partners.reject');
        Route::put('/mitra/{partner}/suspend', [AdminPartnerController::class, 'suspend'])->name('admin.partners.suspend');
        Route::put('/mitra/{partner}/verified', [AdminPartnerController::class, 'toggleVerified'])->name('admin.partners.verified');

        // Kelola Produk (semua mitra)
        Route::get('/produk', [AdminProductController::class, 'index'])->name('admin.products.index');
        Route::put('/produk/{product}/suspend', [AdminProductController::class, 'suspend'])->name('admin.products.suspend');
        Route::delete('/produk/{product}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');

        // Kelola Review
        Route::get('/review', [AdminReviewController::class, 'index'])->name('admin.reviews.index');
        Route::put('/review/{review}/approve', [AdminReviewController::class, 'approve'])->name('admin.reviews.approve');
        Route::put('/review/{review}/hide', [AdminReviewController::class, 'hide'])->name('admin.reviews.hide');
        Route::delete('/review/{review}', [AdminReviewController::class, 'destroy'])->name('admin.reviews.destroy');

        // Kelola Laporan
        Route::get('/laporan', [AdminReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/laporan/{report}', [AdminReportController::class, 'show'])->name('admin.reports.show');
        Route::put('/laporan/{report}/resolve', [AdminReportController::class, 'resolve'])->name('admin.reports.resolve');
        Route::put('/laporan/{report}/ignore', [AdminReportController::class, 'ignore'])->name('admin.reports.ignore');

        // Kelola Outfit Kurasi
        Route::get('/outfit', [AdminOutfitController::class, 'index'])->name('admin.outfits.index');
        Route::get('/outfit/buat', [AdminOutfitController::class, 'create'])->name('admin.outfits.create');
        Route::post('/outfit', [AdminOutfitController::class, 'store'])->name('admin.outfits.store');
        Route::get('/outfit/{outfit}/edit', [AdminOutfitController::class, 'edit'])->name('admin.outfits.edit');
        Route::put('/outfit/{outfit}', [AdminOutfitController::class, 'update'])->name('admin.outfits.update');
        Route::delete('/outfit/{outfit}', [AdminOutfitController::class, 'destroy'])->name('admin.outfits.destroy');
        Route::put('/outfit/{outfit}/toggle', [AdminOutfitController::class, 'toggleActive'])->name('admin.outfits.toggle');

        // Kelola Artikel
        Route::get('/artikel', [AdminArticleController::class, 'index'])->name('admin.articles.index');
        Route::get('/artikel/tambah', [AdminArticleController::class, 'create'])->name('admin.articles.create');
        Route::post('/artikel', [AdminArticleController::class, 'store'])->name('admin.articles.store');
        Route::get('/artikel/{article}/edit', [AdminArticleController::class, 'edit'])->name('admin.articles.edit');
        Route::put('/artikel/{article}', [AdminArticleController::class, 'update'])->name('admin.articles.update');
        Route::delete('/artikel/{article}', [AdminArticleController::class, 'destroy'])->name('admin.articles.destroy');

        // Kelola UGC
        Route::get('/ugc', [AdminUgcController::class, 'index'])->name('admin.ugc.index');
        Route::put('/ugc/{ugcPhoto}/approve', [AdminUgcController::class, 'approve'])->name('admin.ugc.approve');
        Route::put('/ugc/{ugcPhoto}/reject', [AdminUgcController::class, 'reject'])->name('admin.ugc.reject');
        Route::put('/ugc/{ugcPhoto}/featured', [AdminUgcController::class, 'toggleFeatured'])->name('admin.ugc.featured');
        Route::delete('/ugc/{ugcPhoto}', [AdminUgcController::class, 'destroy'])->name('admin.ugc.destroy');

        // Notifications
        Route::get('/notifikasi', [AdminNotificationController::class, 'index'])->name('admin.notifications');
        Route::post('/notifikasi/{id}/read', [AdminNotificationController::class, 'markRead'])->name('admin.notification.read');
        Route::post('/notifikasi/read-all', [AdminNotificationController::class, 'markAllRead'])->name('admin.notifications.read-all');

        // Badges Management
        Route::get('/badges', [AdminBadgeController::class, 'index'])->name('admin.badges');
        Route::post('/badges', [AdminBadgeController::class, 'store'])->name('admin.badges.store');
        Route::post('/badges/assign', [AdminBadgeController::class, 'assign'])->name('admin.badges.assign');
        Route::delete('/badges/{badge}', [AdminBadgeController::class, 'destroy'])->name('admin.badges.destroy');

        // Laporan Web
        Route::get('/laporan-web', [AdminWebReportController::class, 'index'])->name('admin.web-reports.index');
        Route::get('/laporan-web/{webReport}', [AdminWebReportController::class, 'show'])->name('admin.web-reports.show');
        Route::put('/laporan-web/{webReport}/resolve', [AdminWebReportController::class, 'resolve'])->name('admin.web-reports.resolve');
        Route::put('/laporan-web/{webReport}/ignore', [AdminWebReportController::class, 'ignore'])->name('admin.web-reports.ignore');

        // System Analytics
        Route::get('/analitik', [AdminDashboardController::class, 'analytics'])->name('admin.analytics');
    });
});
