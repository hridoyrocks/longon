<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'credits', 'price', 'discount_percentage', 'is_active', 'description'
    ];

    public function paymentRequests()
    {
        return $this->hasMany(PaymentRequest::class, 'package_id');
    }

    public function getDiscountedPriceAttribute()
    {
        return $this->price * (1 - $this->discount_percentage / 100);
    }

    public function getPricePerCreditAttribute()
    {
        return $this->discounted_price / $this->credits;
    }
}