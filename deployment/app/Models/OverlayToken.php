<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverlayToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id', 'token', 'settings', 'expires_at'
    ];

    protected $casts = [
        'settings' => 'array',
        'expires_at' => 'datetime',
    ];

    public function match()
    {
        return $this->belongsTo(\App\Models\FootballMatch::class, 'match_id');
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}