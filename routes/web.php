<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\OverlayController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ResellerController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\OverlayThemeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ===== PUBLIC ROUTES =====

// Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Public overlay route (no auth required)
Route::get('/overlay/{token}', [OverlayController::class, 'show'])->name('overlay.show');

// Public API for overlay data
Route::get('/api/overlay-data/{match}', [OverlayController::class, 'getOverlayData']);

// Special referral registration route
Route::get('/register', function () {
    $referralCode = request()->get('ref');
    return view('auth.register', compact('referralCode'));
})->name('register');

// Authentication Routes (Laravel Breeze)
require __DIR__.'/auth.php';

// ===== AUTHENTICATED ROUTES =====

Route::middleware(['auth', 'verified'])->group(function () {
    
    // ===== DASHBOARD =====
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // ===== MATCH MANAGEMENT =====
    Route::prefix('matches')->name('matches.')->group(function () {
        Route::get('/', [MatchController::class, 'index'])->name('index');
        Route::get('/create', [MatchController::class, 'create'])->name('create');
        Route::post('/', [MatchController::class, 'store'])->name('store');
        Route::get('/{match}', [MatchController::class, 'show'])->name('show');
        Route::get('/{match}/edit', [MatchController::class, 'edit'])->name('edit');
        Route::put('/{match}', [MatchController::class, 'update'])->name('update');
        Route::delete('/{match}', [MatchController::class, 'destroy'])->name('destroy');
        
        // Match Control Panel
        Route::get('/{match}/control', [MatchController::class, 'control'])->name('control');
        
        // Match Actions (AJAX)
        Route::post('/{match}/update-score', [MatchController::class, 'updateScore'])->name('update-score');
        Route::post('/{match}/update-time', [MatchController::class, 'updateTime'])->name('update-time');
        Route::post('/{match}/update-status', [MatchController::class, 'updateStatus'])->name('update-status');
        Route::post('/{match}/add-event', [MatchController::class, 'addEvent'])->name('add-event');
        Route::delete('/{match}/events/{event}', [MatchController::class, 'deleteEvent'])->name('delete-event');
        
        // Overlay Management
        Route::get('/{match}/generate-overlay-link', [MatchController::class, 'generateOverlayLink'])->name('generate-overlay-link');
        Route::post('/{match}/regenerate-overlay', [MatchController::class, 'regenerateOverlay'])->name('regenerate-overlay');
        
        // Match Analytics
        Route::get('/{match}/analytics', [MatchController::class, 'analytics'])->name('analytics');
        
        // Match Export
        Route::get('/{match}/export', [MatchController::class, 'export'])->name('export');
        Route::get('/{match}/export/pdf', [MatchController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/{match}/export/csv', [MatchController::class, 'exportCsv'])->name('export-csv');
        
        // Match Sharing
        Route::post('/{match}/share', [MatchController::class, 'share'])->name('share');
        Route::get('/{match}/share-stats', [MatchController::class, 'shareStats'])->name('share-stats');
    });
    
    // ===== CREDIT MANAGEMENT =====
    Route::prefix('credits')->name('credits.')->group(function () {
        Route::get('/purchase', [CreditController::class, 'purchase'])->name('purchase');
        Route::post('/submit-payment', [CreditController::class, 'submitPayment'])->name('submit-payment');
        Route::get('/history', [CreditController::class, 'history'])->name('history');
        Route::get('/payment-history', [CreditController::class, 'paymentHistory'])->name('payment-history');
        Route::get('/balance', [CreditController::class, 'balance'])->name('balance');
        Route::post('/cancel-payment/{payment}', [CreditController::class, 'cancelPayment'])->name('cancel-payment');
        Route::get('/usage-stats', [CreditController::class, 'usageStats'])->name('usage-stats');
        Route::get('/packages', [CreditController::class, 'packages'])->name('packages');
    });
    
    // ===== RESELLER SYSTEM =====
    Route::prefix('reseller')->name('reseller.')->group(function () {
        // Application Process
        Route::get('/apply', [ResellerController::class, 'apply'])->name('apply');
        Route::post('/apply', [ResellerController::class, 'submitApplication'])->name('submit-application');
        Route::get('/application-status', [ResellerController::class, 'applicationStatus'])->name('application-status');
        
        // Reseller Dashboard (only for approved resellers)
        Route::middleware(['reseller'])->group(function () {
            Route::get('/dashboard', [ResellerController::class, 'dashboard'])->name('dashboard');
            Route::get('/customers', [ResellerController::class, 'customers'])->name('customers');
            Route::get('/commissions', [ResellerController::class, 'commissions'])->name('commissions');
            Route::get('/earnings', [ResellerController::class, 'earnings'])->name('earnings');
            Route::get('/referrals', [ResellerController::class, 'referrals'])->name('referrals');
            
            // Payout Management
            Route::post('/request-payout', [ResellerController::class, 'requestPayout'])->name('request-payout');
            Route::get('/payout-history', [ResellerController::class, 'payoutHistory'])->name('payout-history');
            
            // Referral System
            Route::get('/generate-referral-link', [ResellerController::class, 'generateReferralLink'])->name('generate-referral-link');
            Route::post('/customize-referral', [ResellerController::class, 'customizeReferral'])->name('customize-referral');
            
            // Customer Management
            Route::get('/customers/{customer}', [ResellerController::class, 'customerDetails'])->name('customer-details');
            Route::post('/customers/{customer}/add-note', [ResellerController::class, 'addCustomerNote'])->name('add-customer-note');
            
            // Reports
            Route::get('/reports/monthly', [ResellerController::class, 'monthlyReport'])->name('monthly-report');
            Route::get('/reports/yearly', [ResellerController::class, 'yearlyReport'])->name('yearly-report');
            Route::get('/reports/export', [ResellerController::class, 'exportReport'])->name('export-report');
        });
    });
    
    // ===== ANALYTICS =====
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/dashboard', [AnalyticsController::class, 'dashboard'])->name('dashboard');
        Route::get('/match/{match}', [AnalyticsController::class, 'matchDetails'])->name('match-details');
        Route::get('/user-analytics', [AnalyticsController::class, 'userAnalytics'])->name('user-analytics');
        Route::get('/revenue-analytics', [AnalyticsController::class, 'revenueAnalytics'])->name('revenue-analytics');
        Route::get('/engagement-analytics', [AnalyticsController::class, 'engagementAnalytics'])->name('engagement-analytics');
        
        // Export Analytics
        Route::get('/export/matches', [AnalyticsController::class, 'exportMatches'])->name('export-matches');
        Route::get('/export/revenue', [AnalyticsController::class, 'exportRevenue'])->name('export-revenue');
        
        // Admin Analytics (admin only)
        Route::middleware(['admin'])->group(function () {
            Route::get('/business', [AnalyticsController::class, 'businessDashboard'])->name('business-dashboard');
            Route::get('/system-health', [AnalyticsController::class, 'systemHealth'])->name('system-health');
            Route::get('/user-behavior', [AnalyticsController::class, 'userBehavior'])->name('user-behavior');
        });
    });
    
    // ===== OVERLAY THEMES =====
    Route::prefix('themes')->name('themes.')->group(function () {
        Route::get('/', [OverlayThemeController::class, 'index'])->name('index');
        Route::get('/{theme}', [OverlayThemeController::class, 'show'])->name('show');
        Route::get('/{theme}/preview', [OverlayThemeController::class, 'preview'])->name('preview');
        
        // Theme Application
        Route::post('/matches/{match}/apply', [OverlayThemeController::class, 'apply'])->name('apply');
        Route::get('/matches/{match}/customize', [OverlayThemeController::class, 'customize'])->name('customize');
        Route::post('/matches/{match}/customize', [OverlayThemeController::class, 'saveCustomization'])->name('save-customization');
        Route::post('/matches/{match}/reset-theme', [OverlayThemeController::class, 'resetTheme'])->name('reset-theme');
        
        // Premium Theme Access
        Route::get('/premium', [OverlayThemeController::class, 'premium'])->name('premium');
        Route::post('/unlock-premium', [OverlayThemeController::class, 'unlockPremium'])->name('unlock-premium');
    });
    
    // ===== USER PROFILE =====
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
        Route::post('/settings', [ProfileController::class, 'updateSettings'])->name('update-settings');
        Route::get('/activity', [ProfileController::class, 'activity'])->name('activity');
        Route::post('/upload-avatar', [ProfileController::class, 'uploadAvatar'])->name('upload-avatar');
    });
    
    // ===== NOTIFICATIONS =====
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::get('/settings', [NotificationController::class, 'settings'])->name('settings');
        Route::post('/settings', [NotificationController::class, 'updateSettings'])->name('update-settings');
    });
    
    // ===== SUPPORT SYSTEM =====
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [SupportController::class, 'index'])->name('index');
        Route::get('/create', [SupportController::class, 'create'])->name('create');
        Route::post('/', [SupportController::class, 'store'])->name('store');
        Route::get('/{ticket}', [SupportController::class, 'show'])->name('show');
        Route::post('/{ticket}/reply', [SupportController::class, 'reply'])->name('reply');
        Route::post('/{ticket}/close', [SupportController::class, 'close'])->name('close');
        Route::get('/faq', [SupportController::class, 'faq'])->name('faq');
        Route::post('/feedback', [SupportController::class, 'feedback'])->name('feedback');
    });
    
    // ===== SEARCH =====
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/matches', [SearchController::class, 'matches'])->name('matches');
        Route::get('/users', [SearchController::class, 'users'])->name('users');
        Route::get('/themes', [SearchController::class, 'themes'])->name('themes');
        Route::get('/global', [SearchController::class, 'global'])->name('global');
        Route::get('/suggestions', [SearchController::class, 'suggestions'])->name('suggestions');
    });
    
    // ===== ADMIN ROUTES =====
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // ===== ADMIN DASHBOARD =====
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // ===== USER MANAGEMENT =====
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [AdminController::class, 'users'])->name('index');
            Route::get('/create', [AdminController::class, 'createUser'])->name('create');
            Route::post('/', [AdminController::class, 'storeUser'])->name('store');
            Route::get('/{user}', [AdminController::class, 'showUser'])->name('show');
            Route::get('/{user}/edit', [AdminController::class, 'editUser'])->name('edit');
            Route::put('/{user}', [AdminController::class, 'updateUser'])->name('update');
            Route::delete('/{user}', [AdminController::class, 'destroyUser'])->name('destroy');
            Route::post('/{user}/adjust-credits', [AdminController::class, 'adjustCredits'])->name('adjust-credits');
            Route::post('/{user}/suspend', [AdminController::class, 'suspendUser'])->name('suspend');
            Route::post('/{user}/activate', [AdminController::class, 'activateUser'])->name('activate');
            Route::get('/{user}/activity', [AdminController::class, 'userActivity'])->name('activity');
        });
        
        // ===== PAYMENT MANAGEMENT =====
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/', [AdminController::class, 'payments'])->name('index');
            Route::get('/{payment}', [AdminController::class, 'showPayment'])->name('show');
            Route::post('/{payment}/approve', [AdminController::class, 'approvePayment'])->name('approve');
            Route::post('/{payment}/reject', [AdminController::class, 'rejectPayment'])->name('reject');
            Route::get('/bulk-approve', [AdminController::class, 'bulkApprove'])->name('bulk-approve');
        });
        
        // ===== CREDIT PACKAGE MANAGEMENT =====
        Route::prefix('packages')->name('packages.')->group(function () {
            Route::get('/', [AdminController::class, 'packages'])->name('index');
            Route::get('/create', [AdminController::class, 'createPackage'])->name('create');
            Route::post('/', [AdminController::class, 'storePackage'])->name('store');
            Route::get('/{package}', [AdminController::class, 'showPackage'])->name('show');
            Route::get('/{package}/edit', [AdminController::class, 'editPackage'])->name('edit');
            Route::put('/{package}', [AdminController::class, 'updatePackage'])->name('update');
            Route::delete('/{package}', [AdminController::class, 'destroyPackage'])->name('destroy');
            Route::post('/{package}/toggle-status', [AdminController::class, 'togglePackageStatus'])->name('toggle-status');
        });
        
        // ===== MATCH MANAGEMENT =====
        Route::prefix('matches')->name('matches.')->group(function () {
            Route::get('/', [AdminController::class, 'matches'])->name('index');
            Route::get('/{match}', [AdminController::class, 'showMatch'])->name('show');
            Route::delete('/{match}', [AdminController::class, 'destroyMatch'])->name('destroy');
            Route::post('/{match}/feature', [AdminController::class, 'featureMatch'])->name('feature');
            Route::get('/analytics/overview', [AdminController::class, 'matchAnalytics'])->name('analytics');
        });
        
        // ===== RESELLER MANAGEMENT =====
        Route::prefix('resellers')->name('resellers.')->group(function () {
            Route::get('/', [AdminController::class, 'resellers'])->name('index');
            Route::get('/applications', [AdminController::class, 'resellerApplications'])->name('applications');
            Route::get('/applications/{application}', [AdminController::class, 'viewApplication'])->name('view-application');
            Route::post('/applications/{application}/approve', [AdminController::class, 'approveApplication'])->name('approve-application');
            Route::post('/applications/{application}/reject', [AdminController::class, 'rejectApplication'])->name('reject-application');
            Route::get('/{reseller}', [AdminController::class, 'showReseller'])->name('show');
            Route::post('/{reseller}/suspend', [AdminController::class, 'suspendReseller'])->name('suspend');
            Route::post('/{reseller}/activate', [AdminController::class, 'activateReseller'])->name('activate');
            Route::get('/{reseller}/customers', [AdminController::class, 'resellerCustomers'])->name('customers');
            Route::get('/{reseller}/commissions', [AdminController::class, 'resellerCommissions'])->name('commissions');
        });
        
        // ===== COMMISSION MANAGEMENT =====
        Route::prefix('commissions')->name('commissions.')->group(function () {
            Route::get('/', [AdminController::class, 'commissions'])->name('index');
            Route::get('/tiers', [AdminController::class, 'commissionTiers'])->name('tiers');
            Route::get('/tiers/create', [AdminController::class, 'createTier'])->name('create-tier');
            Route::post('/tiers', [AdminController::class, 'storeTier'])->name('store-tier');
            Route::get('/tiers/{tier}/edit', [AdminController::class, 'editTier'])->name('edit-tier');
            Route::put('/tiers/{tier}', [AdminController::class, 'updateTier'])->name('update-tier');
            Route::delete('/tiers/{tier}', [AdminController::class, 'destroyTier'])->name('destroy-tier');
            Route::post('/{commission}/pay', [AdminController::class, 'payCommission'])->name('pay');
            Route::post('/{commission}/cancel', [AdminController::class, 'cancelCommission'])->name('cancel');
            Route::get('/payouts', [AdminController::class, 'payouts'])->name('payouts');
            Route::post('/payouts/{payout}/approve', [AdminController::class, 'approvePayout'])->name('approve-payout');
            Route::post('/payouts/{payout}/reject', [AdminController::class, 'rejectPayout'])->name('reject-payout');
        });
        
        // ===== THEME MANAGEMENT =====
        Route::prefix('themes')->name('themes.')->group(function () {
            Route::get('/', [AdminController::class, 'overlayThemes'])->name('index');
            Route::get('/create', [AdminController::class, 'createTheme'])->name('create');
            Route::post('/', [AdminController::class, 'storeTheme'])->name('store');
            Route::get('/{theme}', [AdminController::class, 'showTheme'])->name('show');
            Route::get('/{theme}/edit', [AdminController::class, 'editTheme'])->name('edit');
            Route::put('/{theme}', [AdminController::class, 'updateTheme'])->name('update');
            Route::delete('/{theme}', [AdminController::class, 'destroyTheme'])->name('destroy');
            Route::post('/{theme}/toggle-status', [AdminController::class, 'toggleThemeStatus'])->name('toggle-status');
            Route::post('/{theme}/toggle-premium', [AdminController::class, 'togglePremium'])->name('toggle-premium');
            Route::get('/{theme}/usage-stats', [AdminController::class, 'themeUsageStats'])->name('usage-stats');
        });
        
        // ===== SYSTEM SETTINGS =====
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [AdminController::class, 'systemSettings'])->name('index');
            Route::post('/', [AdminController::class, 'updateSettings'])->name('update');
            Route::get('/cache', [AdminController::class, 'cacheSettings'])->name('cache');
            Route::post('/cache/clear', [AdminController::class, 'clearCache'])->name('clear-cache');
            Route::get('/backup', [AdminController::class, 'backupSettings'])->name('backup');
            Route::post('/backup/create', [AdminController::class, 'createBackup'])->name('create-backup');
            Route::get('/maintenance', [AdminController::class, 'maintenanceMode'])->name('maintenance');
            Route::post('/maintenance/toggle', [AdminController::class, 'toggleMaintenance'])->name('toggle-maintenance');
        });
        
        // ===== ANALYTICS & REPORTS =====
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/revenue', [AdminController::class, 'revenueAnalytics'])->name('revenue');
            Route::get('/users', [AdminController::class, 'userAnalytics'])->name('users');
            Route::get('/matches', [AdminController::class, 'matchAnalytics'])->name('matches');
            Route::get('/resellers', [AdminController::class, 'resellerAnalytics'])->name('resellers');
            Route::get('/themes', [AdminController::class, 'themeAnalytics'])->name('themes');
            Route::get('/export', [AdminController::class, 'exportAnalytics'])->name('export');
            Route::get('/real-time', [AdminController::class, 'realTimeAnalytics'])->name('real-time');
        });
        
        // ===== SYSTEM MONITORING =====
        Route::prefix('monitoring')->name('monitoring.')->group(function () {
            Route::get('/system-health', [AdminController::class, 'systemHealth'])->name('system-health');
            Route::get('/error-logs', [AdminController::class, 'errorLogs'])->name('error-logs');
            Route::get('/user-activity', [AdminController::class, 'userActivityLogs'])->name('user-activity');
            Route::get('/api-usage', [AdminController::class, 'apiUsage'])->name('api-usage');
            Route::get('/database-stats', [AdminController::class, 'databaseStats'])->name('database-stats');
            Route::get('/server-stats', [AdminController::class, 'serverStats'])->name('server-stats');
        });
        
        // ===== BULK OPERATIONS =====
        Route::prefix('bulk')->name('bulk.')->group(function () {
            Route::get('/operations', [AdminController::class, 'bulkOperations'])->name('operations');
            Route::post('/send-notifications', [AdminController::class, 'bulkSendNotifications'])->name('send-notifications');
            Route::post('/update-users', [AdminController::class, 'bulkUpdateUsers'])->name('update-users');
            Route::post('/adjust-credits', [AdminController::class, 'bulkAdjustCredits'])->name('adjust-credits');
            Route::post('/approve-payments', [AdminController::class, 'bulkApprovePayments'])->name('approve-payments');
            Route::post('/reject-payments', [AdminController::class, 'bulkRejectPayments'])->name('reject-payments');
        });
        
        // ===== IMPORT/EXPORT =====
        Route::prefix('import-export')->name('import-export.')->group(function () {
            Route::get('/', [AdminController::class, 'importExport'])->name('index');
            Route::post('/import-users', [AdminController::class, 'importUsers'])->name('import-users');
            Route::get('/export-users', [AdminController::class, 'exportUsers'])->name('export-users');
            Route::get('/export-matches', [AdminController::class, 'exportMatches'])->name('export-matches');
            Route::get('/export-payments', [AdminController::class, 'exportPayments'])->name('export-payments');
            Route::get('/export-commissions', [AdminController::class, 'exportCommissions'])->name('export-commissions');
            Route::get('/export-analytics', [AdminController::class, 'exportAnalytics'])->name('export-analytics');
        });
        
        // ===== BULK ACTIONS (General) =====
        Route::post('/bulk-actions', [AdminController::class, 'bulkActions'])->name('bulk-actions');
    });
});

// ===== AJAX ROUTES =====
Route::prefix('ajax')->name('ajax.')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/user-balance', [AjaxController::class, 'userBalance'])->name('user-balance');
        Route::get('/match-status/{match}', [AjaxController::class, 'matchStatus'])->name('match-status');
        Route::get('/notifications', [AjaxController::class, 'notifications'])->name('notifications');
        Route::post('/mark-notification-read/{notification}', [AjaxController::class, 'markNotificationRead'])->name('mark-notification-read');
        Route::get('/search', [AjaxController::class, 'search'])->name('search');
        Route::get('/user-suggestions', [AjaxController::class, 'userSuggestions'])->name('user-suggestions');
    });
});

// ===== WEBHOOK ROUTES =====
Route::prefix('webhooks')->name('webhooks.')->group(function () {
    Route::post('/bkash', [WebhookController::class, 'bkash'])->name('bkash');
    Route::post('/nagad', [WebhookController::class, 'nagad'])->name('nagad');
    Route::post('/rocket', [WebhookController::class, 'rocket'])->name('rocket');
    Route::post('/stripe', [WebhookController::class, 'stripe'])->name('stripe');
    Route::post('/paypal', [WebhookController::class, 'paypal'])->name('paypal');
    Route::post('/razorpay', [WebhookController::class, 'razorpay'])->name('razorpay');
});

// ===== CRON JOB ROUTES =====
Route::prefix('cron')->name('cron.')->group(function () {
    Route::get('/calculate-commissions', [CronController::class, 'calculateCommissions'])->name('calculate-commissions');
    Route::get('/cleanup-expired-tokens', [CronController::class, 'cleanupExpiredTokens'])->name('cleanup-expired-tokens');
    Route::get('/send-daily-reports', [CronController::class, 'sendDailyReports'])->name('send-daily-reports');
    Route::get('/backup-database', [CronController::class, 'backupDatabase'])->name('backup-database');
    Route::get('/cleanup-old-logs', [CronController::class, 'cleanupOldLogs'])->name('cleanup-old-logs');
    Route::get('/send-weekly-reports', [CronController::class, 'sendWeeklyReports'])->name('send-weekly-reports');
    Route::get('/update-analytics', [CronController::class, 'updateAnalytics'])->name('update-analytics');
});

// ===== SITEMAP & SEO ROUTES =====
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');
Route::get('/manifest.json', [SitemapController::class, 'manifest'])->name('manifest');

// ===== HEALTH CHECK ROUTES =====
Route::get('/health', [HealthController::class, 'check'])->name('health');
Route::get('/status', [HealthController::class, 'status'])->name('status');

// ===== TESTING ROUTES (Development Only) =====
if (app()->environment(['local', 'testing'])) {
    Route::prefix('test')->name('test.')->group(function () {
        Route::get('/email', [TestController::class, 'email'])->name('email');
        Route::get('/notification', [TestController::class, 'notification'])->name('notification');
        Route::get('/broadcast', [TestController::class, 'broadcast'])->name('broadcast');
        Route::get('/queue', [TestController::class, 'queue'])->name('queue');
        Route::get('/cache', [TestController::class, 'cache'])->name('cache');
        Route::get('/database', [TestController::class, 'database'])->name('database');
        Route::get('/storage', [TestController::class, 'storage'])->name('storage');
    });
}

// ===== MAINTENANCE MODE ROUTES =====
Route::get('/maintenance', function () {
    return view('maintenance');
})->name('maintenance');

// ===== FALLBACK ROUTES =====
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// ===== EMERGENCY ADMIN ACCESS =====
if (app()->environment('local')) {
    Route::get('/emergency-admin/{token}', [EmergencyController::class, 'adminAccess'])
        ->name('emergency.admin')
        ->where('token', '[a-zA-Z0-9]+');
}