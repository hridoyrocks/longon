<?php
// app/Models/CommissionTier.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'min_monthly_sales', 'max_monthly_sales',
        'level_1_commission', 'level_2_commission', 'bonus_percentage',
        'benefits', 'is_active'
    ];

    protected $casts = [
        'benefits' => 'array',
    ];

    public function getTierForSales($monthlySales)
    {
        return self::where('is_active', true)
            ->where('min_monthly_sales', '<=', $monthlySales)
            ->where(function ($query) use ($monthlySales) {
                $query->where('max_monthly_sales', '>=', $monthlySales)
                    ->orWhereNull('max_monthly_sales');
            })
            ->first();
    }
}