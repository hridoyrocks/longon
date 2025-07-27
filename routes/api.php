<?php
// routes/api.php - Complete API Routes for Phase 1 + Phase 2

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MatchApiController;
use App\Http\Controllers\Api\OverlayApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\AnalyticsApiController;
use App\Http\Controllers\Api\CreditApiController;
use App\Http\Controllers\Api\ThemeApiController;
use App\Http\Controllers\Api\ResellerApiController;
use App\Http\Controllers\Api\WebhookApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// ===== PUBLIC API ROUTES =====

// Overlay API (Public access)
Route::prefix('overlay')->name('overlay.')->group(function () {
    Route::get('/{token}', [OverlayApiController::class, 'show'])->name('show');
    Route::post('/{token}/view', [OverlayApiController::class, 'trackView'])->name('track-view');
    Route::get('/{token}/stream', [OverlayApiController::class, 'streamData'])->name('stream-data');
});

// Overlay data endpoint (for real-time updates)
Route::get('/overlay-data/{match}', [OverlayApiController::class, 'getData'])->name('overlay.data');

// Authentication API
Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
    Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('verify-email');
    Route::post('/resend-verification', [AuthController::class, 'resendVerification'])->name('resend-verification');
    
    // Social Authentication
    Route::post('/google', [AuthController::class, 'googleLogin'])->name('google-login');
    Route::post('/facebook', [AuthController::class, 'facebookLogin'])->name('facebook-login');
});

// Public data endpoints
Route::prefix('public')->name('public.')->group(function () {
    Route::get('/themes', [ThemeApiController::class, 'publicThemes'])->name('themes');
    Route::get('/packages', [CreditApiController::class, 'publicPackages'])->name('packages');
    Route::get('/stats', [AnalyticsApiController::class, 'publicStats'])->name('stats');
});

// Webhook endpoints
Route::prefix('webhooks')->name('webhooks.')->group(function () {
    Route::post('/bkash', [WebhookApiController::class, 'bkash'])->name('bkash');
    Route::post('/nagad', [WebhookApiController::class, 'nagad'])->name('nagad');
    Route::post('/rocket', [WebhookApiController::class, 'rocket'])->name('rocket');
    Route::post('/stripe', [WebhookApiController::class, 'stripe'])->name('stripe');
    Route::post('/paypal', [WebhookApiController::class, 'paypal'])->name('paypal');
});

// ===== AUTHENTICATED API ROUTES =====

Route::middleware('auth:sanctum')->group(function () {
    
    // User Authentication & Profile
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
        Route::get('/user', [AuthController::class, 'user'])->name('user');
        Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password');
        Route::post('/update-profile', [AuthController::class, 'updateProfile'])->name('update-profile');
        Route::post('/delete-account', [AuthController::class, 'deleteAccount'])->name('delete-account');
    });
    
    // User API
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/profile', [UserApiController::class, 'profile'])->name('profile');
        Route::put('/profile', [UserApiController::class, 'updateProfile'])->name('update-profile');
        Route::get('/credits', [UserApiController::class, 'credits'])->name('credits');
        Route::get('/matches', [UserApiController::class, 'matches'])->name('matches');
        Route::get('/analytics', [UserApiController::class, 'analytics'])->name('analytics');
        Route::get('/transactions', [UserApiController::class, 'transactions'])->name('transactions');
        Route::get('/notifications', [UserApiController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/{notification}/read', [UserApiController::class, 'markNotificationRead'])->name('mark-notification-read');
        Route::post('/upload-avatar', [UserApiController::class, 'uploadAvatar'])->name('upload-avatar');
        Route::get('/activity-logs', [UserApiController::class, 'activityLogs'])->name('activity-logs');
        Route::get('/referrals', [UserApiController::class, 'referrals'])->name('referrals');
    });
    
    // Match API
    Route::prefix('matches')->name('matches.')->group(function () {
        Route::get('/', [MatchApiController::class, 'index'])->name('index');
        Route::post('/', [MatchApiController::class, 'store'])->name('store');
        Route::get('/{match}', [MatchApiController::class, 'show'])->name('show');
        Route::put('/{match}', [MatchApiController::class, 'update'])->name('update');
        Route::delete('/{match}', [MatchApiController::class, 'destroy'])->name('destroy');
        
        // Match Actions
        Route::patch('/{match}/score', [MatchApiController::class, 'updateScore'])->name('update-score');
        Route::patch('/{match}/time', [MatchApiController::class, 'updateTime'])->name('update-time');
        Route::patch('/{match}/status', [MatchApiController::class, 'updateStatus'])->name('update-status');
        
        // Match Events
        Route::get('/{match}/events', [MatchApiController::class, 'events'])->name('events');
        Route::post('/{match}/events', [MatchApiController::class, 'addEvent'])->name('add-event');
        Route::delete('/{match}/events/{event}', [MatchApiController::class, 'deleteEvent'])->name('delete-event');
        
        // Overlay Management
        Route::get('/{match}/overlay', [MatchApiController::class, 'getOverlay'])->name('get-overlay');
        Route::post('/{match}/overlay', [MatchApiController::class, 'createOverlay'])->name('create-overlay');
        Route::put('/{match}/overlay', [MatchApiController::class, 'updateOverlay'])->name('update-overlay');
        Route::delete('/{match}/overlay', [MatchApiController::class, 'deleteOverlay'])->name('delete-overlay');
        
        // Match Analytics
        Route::get('/{match}/analytics', [MatchApiController::class, 'analytics'])->name('analytics');
        Route::post('/{match}/analytics/track', [MatchApiController::class, 'trackAnalytics'])->name('track-analytics');
        
        // Match Sharing
        Route::post('/{match}/share', [MatchApiController::class, 'share'])->name('share');
        Route::get('/{match}/share-stats', [MatchApiController::class, 'shareStats'])->name('share-stats');
        
        // Match Export
        Route::get('/{match}/export', [MatchApiController::class, 'export'])->name('export');
        Route::get('/{match}/export/pdf', [MatchApiController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/{match}/export/csv', [MatchApiController::class, 'exportCsv'])->name('export-csv');
    });
    
    // Credit API
    Route::prefix('credits')->name('credits.')->group(function () {
        Route::get('/balance', [CreditApiController::class, 'balance'])->name('balance');
        Route::get('/packages', [CreditApiController::class, 'packages'])->name('packages');
        Route::post('/purchase', [CreditApiController::class, 'purchase'])->name('purchase');
        Route::get('/transactions', [CreditApiController::class, 'transactions'])->name('transactions');
        Route::get('/payments', [CreditApiController::class, 'payments'])->name('payments');
        Route::post('/payments/{payment}/cancel', [CreditApiController::class, 'cancelPayment'])->name('cancel-payment');
        Route::get('/usage-stats', [CreditApiController::class, 'usageStats'])->name('usage-stats');
    });
    
    // Theme API
    Route::prefix('themes')->name('themes.')->group(function () {
        Route::get('/', [ThemeApiController::class, 'index'])->name('index');
        Route::get('/{theme}', [ThemeApiController::class, 'show'])->name('show');
        Route::post('/{theme}/apply/{match}', [ThemeApiController::class, 'apply'])->name('apply');
        Route::get('/my-themes', [ThemeApiController::class, 'myThemes'])->name('my-themes');
        Route::post('/custom', [ThemeApiController::class, 'createCustom'])->name('create-custom');
        Route::put('/custom/{theme}', [ThemeApiController::class, 'updateCustom'])->name('update-custom');
        Route::delete('/custom/{theme}', [ThemeApiController::class, 'deleteCustom'])->name('delete-custom');
    });
    
    // Analytics API
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/dashboard', [AnalyticsApiController::class, 'dashboard'])->name('dashboard');
        Route::get('/matches', [AnalyticsApiController::class, 'matches'])->name('matches');
        Route::get('/matches/{match}', [AnalyticsApiController::class, 'matchDetails'])->name('match-details');
        Route::get('/revenue', [AnalyticsApiController::class, 'revenue'])->name('revenue');
        Route::get('/engagement', [AnalyticsApiController::class, 'engagement'])->name('engagement');
        Route::get('/trends', [AnalyticsApiController::class, 'trends'])->name('trends');
        Route::get('/export', [AnalyticsApiController::class, 'export'])->name('export');
        Route::get('/real-time', [AnalyticsApiController::class, 'realTime'])->name('real-time');
    });
    
    // Reseller API (Phase 2)
    Route::prefix('reseller')->name('reseller.')->group(function () {
        Route::post('/apply', [ResellerApiController::class, 'apply'])->name('apply');
        Route::get('/application', [ResellerApiController::class, 'application'])->name('application');
        
        // Reseller Dashboard (for approved resellers)
        Route::middleware(['reseller'])->group(function () {
            Route::get('/dashboard', [ResellerApiController::class, 'dashboard'])->name('dashboard');
            Route::get('/customers', [ResellerApiController::class, 'customers'])->name('customers');
            Route::get('/customers/{customer}', [ResellerApiController::class, 'customerDetails'])->name('customer-details');
            Route::post('/customers/{customer}/note', [ResellerApiController::class, 'addCustomerNote'])->name('add-customer-note');
            
            // Commission Management
            Route::get('/commissions', [ResellerApiController::class, 'commissions'])->name('commissions');
            Route::get('/earnings', [ResellerApiController::class, 'earnings'])->name('earnings');
            Route::post('/payout-request', [ResellerApiController::class, 'requestPayout'])->name('request-payout');
            Route::get('/payout-history', [ResellerApiController::class, 'payoutHistory'])->name('payout-history');
            
            // Referral System
            Route::get('/referral-link', [ResellerApiController::class, 'getReferralLink'])->name('get-referral-link');
            Route::post('/referral-link', [ResellerApiController::class, 'generateReferralLink'])->name('generate-referral-link');
            Route::get('/referrals', [ResellerApiController::class, 'referrals'])->name('referrals');
            
            // Reports
            Route::get('/reports/monthly', [ResellerApiController::class, 'monthlyReport'])->name('monthly-report');
            Route::get('/reports/yearly', [ResellerApiController::class, 'yearlyReport'])->name('yearly-report');
            Route::get('/reports/export', [ResellerApiController::class, 'exportReport'])->name('export-report');
            
            // Marketing Tools
            Route::get('/marketing-materials', [ResellerApiController::class, 'marketingMaterials'])->name('marketing-materials');
            Route::post('/marketing-materials', [ResellerApiController::class, 'createMarketingMaterial'])->name('create-marketing-material');
        });
    });
    
    // Notification API
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationApiController::class, 'index'])->name('index');
        Route::get('/unread', [NotificationApiController::class, 'unread'])->name('unread');
        Route::post('/{notification}/read', [NotificationApiController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/mark-all-read', [NotificationApiController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notification}', [NotificationApiController::class, 'destroy'])->name('destroy');
        Route::get('/settings', [NotificationApiController::class, 'settings'])->name('settings');
        Route::post('/settings', [NotificationApiController::class, 'updateSettings'])->name('update-settings');
    });
    
    // Support API
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/tickets', [SupportApiController::class, 'tickets'])->name('tickets');
        Route::post('/tickets', [SupportApiController::class, 'createTicket'])->name('create-ticket');
        Route::get('/tickets/{ticket}', [SupportApiController::class, 'showTicket'])->name('show-ticket');
        Route::post('/tickets/{ticket}/reply', [SupportApiController::class, 'replyToTicket'])->name('reply-to-ticket');
        Route::post('/tickets/{ticket}/close', [SupportApiController::class, 'closeTicket'])->name('close-ticket');
        Route::get('/faq', [SupportApiController::class, 'faq'])->name('faq');
        Route::post('/feedback', [SupportApiController::class, 'feedback'])->name('feedback');
    });
    
    // File Upload API
    Route::prefix('upload')->name('upload.')->group(function () {
        Route::post('/image', [FileUploadApiController::class, 'image'])->name('image');
        Route::post('/document', [FileUploadApiController::class, 'document'])->name('document');
        Route::post('/avatar', [FileUploadApiController::class, 'avatar'])->name('avatar');
        Route::post('/logo', [FileUploadApiController::class, 'logo'])->name('logo');
        Route::delete('/file/{file}', [FileUploadApiController::class, 'deleteFile'])->name('delete-file');
    });
    
    // Search API
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/matches', [SearchApiController::class, 'matches'])->name('matches');
        Route::get('/users', [SearchApiController::class, 'users'])->name('users');
        Route::get('/themes', [SearchApiController::class, 'themes'])->name('themes');
        Route::get('/global', [SearchApiController::class, 'global'])->name('global');
        Route::get('/suggestions', [SearchApiController::class, 'suggestions'])->name('suggestions');
    });
    
    // ===== ADMIN API ROUTES =====
    
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminApiController::class, 'dashboard'])->name('dashboard');
        Route::get('/stats', [AdminApiController::class, 'stats'])->name('stats');
        
        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [AdminApiController::class, 'users'])->name('index');
            Route::post('/', [AdminApiController::class, 'createUser'])->name('create');
            Route::get('/{user}', [AdminApiController::class, 'showUser'])->name('show');
            Route::put('/{user}', [AdminApiController::class, 'updateUser'])->name('update');
            Route::delete('/{user}', [AdminApiController::class, 'deleteUser'])->name('delete');
            Route::post('/{user}/suspend', [AdminApiController::class, 'suspendUser'])->name('suspend');
            Route::post('/{user}/activate', [AdminApiController::class, 'activateUser'])->name('activate');
            Route::post('/{user}/adjust-credits', [AdminApiController::class, 'adjustCredits'])->name('adjust-credits');
            Route::get('/{user}/activity', [AdminApiController::class, 'userActivity'])->name('activity');
        });
        
        // Payment Management
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/', [AdminApiController::class, 'payments'])->name('index');
            Route::get('/{payment}', [AdminApiController::class, 'showPayment'])->name('show');
            Route::post('/{payment}/approve', [AdminApiController::class, 'approvePayment'])->name('approve');
            Route::post('/{payment}/reject', [AdminApiController::class, 'rejectPayment'])->name('reject');
            Route::post('/bulk-approve', [AdminApiController::class, 'bulkApprovePayments'])->name('bulk-approve');
            Route::post('/bulk-reject', [AdminApiController::class, 'bulkRejectPayments'])->name('bulk-reject');
        });
        
        // Match Management
        Route::prefix('matches')->name('matches.')->group(function () {
            Route::get('/', [AdminApiController::class, 'matches'])->name('index');
            Route::get('/{match}', [AdminApiController::class, 'showMatch'])->name('show');
            Route::delete('/{match}', [AdminApiController::class, 'deleteMatch'])->name('delete');
            Route::post('/{match}/feature', [AdminApiController::class, 'featureMatch'])->name('feature');
            Route::get('/analytics/overview', [AdminApiController::class, 'matchAnalytics'])->name('analytics');
        });
        
        // Reseller Management
        Route::prefix('resellers')->name('resellers.')->group(function () {
            Route::get('/', [AdminApiController::class, 'resellers'])->name('index');
            Route::get('/applications', [AdminApiController::class, 'applications'])->name('applications');
            Route::get('/applications/{application}', [AdminApiController::class, 'showApplication'])->name('show-application');
            Route::post('/applications/{application}/approve', [AdminApiController::class, 'approveApplication'])->name('approve-application');
            Route::post('/applications/{application}/reject', [AdminApiController::class, 'rejectApplication'])->name('reject-application');
            Route::get('/{reseller}', [AdminApiController::class, 'showReseller'])->name('show');
            Route::post('/{reseller}/suspend', [AdminApiController::class, 'suspendReseller'])->name('suspend');
            Route::post('/{reseller}/activate', [AdminApiController::class, 'activateReseller'])->name('activate');
        });
        
        // Commission Management
        Route::prefix('commissions')->name('commissions.')->group(function () {
            Route::get('/', [AdminApiController::class, 'commissions'])->name('index');
            Route::get('/tiers', [AdminApiController::class, 'commissionTiers'])->name('tiers');
            Route::post('/tiers', [AdminApiController::class, 'createTier'])->name('create-tier');
            Route::put('/tiers/{tier}', [AdminApiController::class, 'updateTier'])->name('update-tier');
            Route::delete('/tiers/{tier}', [AdminApiController::class, 'deleteTier'])->name('delete-tier');
            Route::post('/{commission}/pay', [AdminApiController::class, 'payCommission'])->name('pay');
            Route::get('/payouts', [AdminApiController::class, 'payouts'])->name('payouts');
            Route::post('/payouts/{payout}/approve', [AdminApiController::class, 'approvePayout'])->name('approve-payout');
            Route::post('/payouts/{payout}/reject', [AdminApiController::class, 'rejectPayout'])->name('reject-payout');
        });
        
        // Theme Management
        Route::prefix('themes')->name('themes.')->group(function () {
            Route::get('/', [AdminApiController::class, 'themes'])->name('index');
            Route::post('/', [AdminApiController::class, 'createTheme'])->name('create');
            Route::get('/{theme}', [AdminApiController::class, 'showTheme'])->name('show');
            Route::put('/{theme}', [AdminApiController::class, 'updateTheme'])->name('update');
            Route::delete('/{theme}', [AdminApiController::class, 'deleteTheme'])->name('delete');
            Route::post('/{theme}/toggle-status', [AdminApiController::class, 'toggleThemeStatus'])->name('toggle-status');
            Route::get('/{theme}/usage-stats', [AdminApiController::class, 'themeUsageStats'])->name('usage-stats');
        });
        
        // Analytics & Reports
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/revenue', [AdminApiController::class, 'revenueAnalytics'])->name('revenue');
            Route::get('/users', [AdminApiController::class, 'userAnalytics'])->name('users');
            Route::get('/matches', [AdminApiController::class, 'matchAnalytics'])->name('matches');
            Route::get('/resellers', [AdminApiController::class, 'resellerAnalytics'])->name('resellers');
            Route::get('/export', [AdminApiController::class, 'exportAnalytics'])->name('export');
        });
        
        // System Management
        Route::prefix('system')->name('system.')->group(function () {
            Route::get('/health', [AdminApiController::class, 'systemHealth'])->name('health');
            Route::get('/logs', [AdminApiController::class, 'systemLogs'])->name('logs');
            Route::post('/cache/clear', [AdminApiController::class, 'clearCache'])->name('clear-cache');
            Route::post('/backup', [AdminApiController::class, 'createBackup'])->name('create-backup');
            Route::get('/settings', [AdminApiController::class, 'systemSettings'])->name('settings');
            Route::post('/settings', [AdminApiController::class, 'updateSettings'])->name('update-settings');
        });
        
        // Bulk Operations
        Route::prefix('bulk')->name('bulk.')->group(function () {
            Route::post('/send-notifications', [AdminApiController::class, 'bulkSendNotifications'])->name('send-notifications');
            Route::post('/update-users', [AdminApiController::class, 'bulkUpdateUsers'])->name('update-users');
            Route::post('/adjust-credits', [AdminApiController::class, 'bulkAdjustCredits'])->name('adjust-credits');
        });
        
        // Import/Export
        Route::prefix('import-export')->name('import-export.')->group(function () {
            Route::post('/import-users', [AdminApiController::class, 'importUsers'])->name('import-users');
            Route::get('/export-users', [AdminApiController::class, 'exportUsers'])->name('export-users');
            Route::get('/export-matches', [AdminApiController::class, 'exportMatches'])->name('export-matches');
            Route::get('/export-payments', [AdminApiController::class, 'exportPayments'])->name('export-payments');
        });
    });
});

// ===== RATE LIMITED ROUTES =====

// Rate limited routes for heavy operations
Route::middleware(['auth:sanctum', 'throttle:10,1'])->group(function () {
    Route::post('/matches/bulk-create', [MatchApiController::class, 'bulkCreate'])->name('matches.bulk-create');
    Route::post('/analytics/heavy-report', [AnalyticsApiController::class, 'heavyReport'])->name('analytics.heavy-report');
    Route::post('/export/large-dataset', [ExportApiController::class, 'largeDataset'])->name('export.large-dataset');
});

// ===== TESTING ROUTES (Development only) =====

if (app()->environment('local', 'testing')) {
    Route::prefix('test')->name('test.')->group(function () {
        Route::get('/broadcast', [TestApiController::class, 'broadcast'])->name('broadcast');
        Route::get('/queue', [TestApiController::class, 'queue'])->name('queue');
        Route::get('/notification', [TestApiController::class, 'notification'])->name('notification');
        Route::get('/email', [TestApiController::class, 'email'])->name('email');
    });
}

// ===== API DOCUMENTATION ROUTES =====

Route::get('/docs', function () {
    return view('api.docs');
})->name('api.docs');

Route::get('/docs/postman', [ApiDocController::class, 'postman'])->name('api.postman');
Route::get('/docs/openapi', [ApiDocController::class, 'openapi'])->name('api.openapi');