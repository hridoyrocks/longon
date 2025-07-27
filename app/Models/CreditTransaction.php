<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'match_id', 'payment_request_id', 'credits_used',
        'transaction_type', 'balance_before', 'balance_after', 'description'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function footballMatch()
    {
        return $this->belongsTo(\App\Models\FootballMatch::class, 'match_id');
    }

    public function paymentRequest()
    {
        return $this->belongsTo(\App\Models\PaymentRequest::class);
    }

    public function isCredit()
    {
        return $this->transaction_type === 'credit';
    }

    public function isDebit()
    {
        return $this->transaction_type === 'debit';
    }
}