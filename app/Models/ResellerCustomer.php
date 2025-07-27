<?php
// app/Models/ResellerCustomer.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResellerCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'reseller_id', 'customer_id', 'referrer_id', 'total_purchases',
        'total_commission_earned', 'first_purchase_at', 'last_purchase_at', 'is_active'
    ];

    protected $casts = [
        'first_purchase_at' => 'datetime',
        'last_purchase_at' => 'datetime',
    ];

    public function reseller()
    {
        return $this->belongsTo(User::class, 'reseller_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }
}