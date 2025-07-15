<?php
// app/Models/MatchAnalytics.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchAnalytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id', 'total_goals', 'total_cards', 'total_substitutions',
        'overlay_views', 'overlay_unique_views', 'viewer_countries',
        'peak_viewing_times', 'average_viewing_duration', 'event_timeline',
        'engagement_score'
    ];

    protected $casts = [
        'viewer_countries' => 'array',
        'peak_viewing_times' => 'array',
        'event_timeline' => 'array',
    ];

    public function match()
    {
        return $this->belongsTo(Match::class);
    }

    public function incrementViews($unique = false)
    {
        $this->increment('overlay_views');
        if ($unique) {
            $this->increment('overlay_unique_views');
        }
    }
}