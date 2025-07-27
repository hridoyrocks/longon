<?php
// app/Models/User.php - Updated with reseller features
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'user_type', 'referral_code',
        'referrer_id', 'business_name', 'monthly_sales_target', 'reseller_settings',
        'credits_balance', 'is_active', 'email_verified_at', 'reseller_approved_at'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_activity' => 'datetime',
        'reseller_approved_at' => 'datetime',
        'reseller_settings' => 'array',
    ];

    // Existing relationships
    public function matches()
    {
        return $this->hasMany(\App\Models\FootballMatch::class);
    }

    public function paymentRequests()
    {
        return $this->hasMany(\App\Models\PaymentRequest::class);
    }

    public function creditTransactions()
    {
        return $this->hasMany(\App\Models\CreditTransaction::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(\App\Models\UserActivityLog::class);
    }

    // New reseller relationships
    public function referrer()
    {
        return $this->belongsTo(\App\Models\User::class, 'referrer_id');
    }

    public function referrals()
    {
        return $this->hasMany(\App\Models\User::class, 'referrer_id');
    }

    public function resellerApplication()
    {
        return $this->hasOne(\App\Models\ResellerApplication::class);
    }

    public function customers()
    {
        return $this->hasMany(\App\Models\ResellerCustomer::class, 'reseller_id');
    }

    public function commissionPayments()
    {
        return $this->hasMany(\App\Models\CommissionPayment::class, 'reseller_id');
    }

    // Existing methods
    public function hasCredits($amount = 1)
    {
        return $this->credits_balance >= $amount;
    }

    public function deductCredits($amount)
    {
        if ($this->hasCredits($amount)) {
            $this->decrement('credits_balance', $amount);
            return true;
        }
        return false;
    }

    public function addCredits($amount)
    {
        $this->increment('credits_balance', $amount);
    }

    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    public function isReseller()
    {
        return $this->user_type === 'reseller';
    }

    // New reseller methods
    public function isApprovedReseller()
    {
        return $this->isReseller() && $this->reseller_approved_at;
    }

    public function addCommission($amount)
    {
        $this->increment('commission_balance', $amount);
        $this->increment('total_commission_earned', $amount);
    }

    public function deductCommission($amount)
    {
        if ($this->commission_balance >= $amount) {
            $this->decrement('commission_balance', $amount);
            $this->increment('total_commission_paid', $amount);
            return true;
        }
        return false;
    }

    public function getMonthlySales($month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        return $this->customers()
            ->whereHas('customer.paymentRequests', function ($query) use ($month, $year) {
                $query->where('status', 'approved')
                    ->whereMonth('approved_at', $month)
                    ->whereYear('approved_at', $year);
            })
            ->sum('total_purchases');
    }

    public function getCurrentCommissionTier()
    {
        $monthlySales = $this->getMonthlySales();
        return \App\Models\CommissionTier::where('is_active', true)
            ->where('min_monthly_sales', '<=', $monthlySales)
            ->where(function ($query) use ($monthlySales) {
                $query->where('max_monthly_sales', '>=', $monthlySales)
                    ->orWhereNull('max_monthly_sales');
            })
            ->first();
    }

    public function generateReferralCode()
    {
        $this->referral_code = strtoupper(\Illuminate\Support\Str::random(8));
        $this->save();
    }
}