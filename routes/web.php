<?php
// routes/web.php - Clean Structure with Essential Routes Only

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\OverlayController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ResellerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlayerController;
use Illuminate\Support\Facades\Route;

// ===== PUBLIC ROUTES =====

Route::get('/', function () {
    return view('welcome');
});

// Public overlay route (no auth required)
Route::get('/overlay/{token}', [OverlayController::class, 'show'])->name('overlay.show');

// Real-time overlay data API
Route::get('/api/overlay-data/{match}', [OverlayController::class, 'getOverlayData']);
Route::post('/api/overlay/{token}/track-view', [OverlayController::class, 'trackViewApi']);

// Authentication Routes
require __DIR__.'/auth.php';

// ===== AUTHENTICATED ROUTES =====

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Match Management
    Route::prefix('matches')->name('matches.')->group(function () {
        Route::get('/', [MatchController::class, 'index'])->name('index');
        Route::get('/create', [MatchController::class, 'create'])->name('create');
        Route::post('/', [MatchController::class, 'store'])->name('store');
        Route::get('/{match}/control', [MatchController::class, 'control'])->name('control');
        
        // AJAX Match Actions
        Route::post('/{match}/update-score', [MatchController::class, 'updateScore'])->name('update-score');
        Route::post('/{match}/update-time', [MatchController::class, 'updateTime'])->name('update-time');
        Route::post('/{match}/update-status', [MatchController::class, 'updateStatus'])->name('update-status');
        Route::post('/{match}/add-event', [MatchController::class, 'addEvent'])->name('add-event');
        Route::post('/{match}/update-tiebreaker', [MatchController::class, 'updateTieBreaker'])->name('update-tiebreaker');
        Route::post('/{match}/update-settings', [MatchController::class, 'updateSettings'])->name('update-settings');
        Route::post('/{match}/update-penalty', [MatchController::class, 'updatePenalty'])->name('update-penalty');
        
        // Overlay Management
        Route::get('/{match}/generate-overlay-link', [MatchController::class, 'generateOverlayLink'])->name('generate-overlay-link');
        
        // Player Management
        Route::get('/{match}/players', [PlayerController::class, 'index'])->name('players.index');
        Route::post('/{match}/players', [PlayerController::class, 'store'])->name('players.store');
        Route::put('/{match}/players/{player}', [PlayerController::class, 'update'])->name('players.update');
        Route::delete('/{match}/players/{player}', [PlayerController::class, 'destroy'])->name('players.destroy');
        Route::post('/{match}/toggle-player-list', [PlayerController::class, 'togglePlayerList'])->name('toggle-player-list');
    });
    
    // Credit Management
    Route::prefix('credits')->name('credits.')->group(function () {
        Route::get('/purchase', [CreditController::class, 'purchase'])->name('purchase');
        Route::post('/submit-payment', [CreditController::class, 'submitPayment'])->name('submit-payment');
        Route::get('/history', [CreditController::class, 'history'])->name('history');
        Route::get('/payment-history', [CreditController::class, 'paymentHistory'])->name('payment-history');
    });
    
    // Reseller System
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
        });
    });
    
    // User Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
    
    // Admin Routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // User Management
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::post('/users/{user}/adjust-credits', [AdminController::class, 'adjustCredits'])->name('users.adjust-credits');
        
        // Payment Management
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments.index');
        Route::post('/payments/{payment}/approve', [AdminController::class, 'approvePayment'])->name('payments.approve');
        Route::post('/payments/{payment}/reject', [AdminController::class, 'rejectPayment'])->name('payments.reject');
        
        // Match Management
        Route::get('/matches', [AdminController::class, 'matches'])->name('matches.index');
        
        // Reseller Management
        Route::get('/resellers', [AdminController::class, 'resellers'])->name('resellers.index');
        Route::get('/reseller-applications', [AdminController::class, 'resellerApplications'])->name('reseller-applications');
        Route::get('/reseller-applications/{application}', [AdminController::class, 'viewApplication'])->name('view-application');
        Route::post('/reseller-applications/{application}/approve', [AdminController::class, 'approveApplication'])->name('approve-application');
        Route::post('/reseller-applications/{application}/reject', [AdminController::class, 'rejectApplication'])->name('reject-application');
        
        // Commission Management
        Route::get('/commissions', [AdminController::class, 'commissions'])->name('commissions');
        
        // General Actions
        Route::post('/bulk-actions', [AdminController::class, 'bulkActions'])->name('bulk-actions');
    });
});

// ===== REAL-TIME ROUTES =====

// WebSocket/Pusher broadcasting routes
Route::post('/broadcasting/auth', function () {
    return response()->json(['status' => 'ok']);
})->middleware(['auth']);

// Health check
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});

// Fallback route
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});