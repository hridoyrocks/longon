<?php
// app/Models/OverlayTheme.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverlayTheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'css_variables', 'custom_css',
        'preview_image', 'is_premium', 'is_active', 'usage_count'
    ];

    protected $casts = [
        'css_variables' => 'array',
    ];

    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    public function getPreviewImageUrlAttribute()
    {
        return $this->preview_image ? asset('storage/' . $this->preview_image) : null;
    }
}