<?php
// app/Models/ResellerApplication.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResellerApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'business_name', 'business_type', 'business_address',
        'nid_number', 'bank_account', 'bank_name', 'bank_branch',
        'mobile_banking_number', 'mobile_banking_type', 'experience_description',
        'expected_monthly_sales', 'documents', 'status', 'admin_notes'
    ];

    protected $casts = [
        'documents' => 'array',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}