<?php
// app/Http/Controllers/Api/OverlayApiController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OverlayToken;
use App\Models\MatchAnalytics;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OverlayApiController extends Controller
{
    public function show($token): JsonResponse
    {
        $overlayToken = OverlayToken::where('token', $token)
            ->with('match.events')
            ->first();

        if (!$overlayToken || $overlayToken->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Overlay not found or expired'
            ], 404);
        }

        $match = $overlayToken->match;
        $showWatermark = !$match->is_premium;

        return response()->json([
            'success' => true,
            'data' => [
                'match' => $match,
                'show_watermark' => $showWatermark,
                'overlay_settings' => $overlayToken->settings,
            ]
        ]);
    }

    public function trackView(Request $request, $token): JsonResponse
    {
        $overlayToken = OverlayToken::where('token', $token)->first();

        if (!$overlayToken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 404);
        }

        $match = $overlayToken->match;
        $analytics = $match->analytics;

        if (!$analytics) {
            $analytics = MatchAnalytics::create([
                'match_id' => $match->id,
                'total_goals' => 0,
                'total_cards' => 0,
                'total_substitutions' => 0,
                'overlay_views' => 0,
                'engagement_score' => 0,
            ]);
        }

        $isUnique = !$request->session()->has('viewed_overlay_' . $token);
        $analytics->incrementViews($isUnique);

        if ($isUnique) {
            $request->session()->put('viewed_overlay_' . $token, true);
        }

        return response()->json([
            'success' => true,
            'message' => 'View tracked successfully'
        ]);
    }
}