<?php
// app/Models/CommissionPayment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reseller_id', 'customer_id', 'payment_request_id', 'purchase_amount',
        'commission_amount', 'commission_percentage', 'commission_level',
        'status', 'paid_at', 'notes'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function reseller()
    {
        return $this->belongsTo(User::class, 'reseller_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function paymentRequest()
    {
        return $this->belongsTo(PaymentRequest::class);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }
}