<?php
// app/Http/Controllers/OverlayThemeController.php
namespace App\Http\Controllers;

use App\Models\OverlayTheme;
use App\Models\Match;
use Illuminate\Http\Request;

class OverlayThemeController extends Controller
{
    public function index()
    {
        $themes = OverlayTheme::where('is_active', true)
            ->orderBy('usage_count', 'desc')
            ->get();

        return view('themes.index', compact('themes'));
    }

    public function preview(OverlayTheme $theme)
    {
        return view('themes.preview', compact('theme'));
    }

    public function apply(Request $request, Match $match)
    {
        $request->validate([
            'theme_id' => 'required|exists:overlay_themes,id',
        ]);

        $theme = OverlayTheme::findOrFail($request->theme_id);
        
        // Check if user can use premium themes
        if ($theme->is_premium && !$match->is_premium) {
            return redirect()->back()->with('error', 'প্রিমিয়াম থিম ব্যবহার করতে ক্রেডিট প্রয়োজন।');
        }

        // Update match overlay settings
        $match->update([
            'overlay_settings' => array_merge($match->overlay_settings ?? [], [
                'theme_id' => $theme->id,
                'theme_name' => $theme->name,
                'css_variables' => $theme->css_variables,
                'custom_css' => $theme->custom_css,
            ])
        ]);

        $theme->incrementUsage();

        return redirect()->back()->with('success', 'থিম সফলভাবে প্রয়োগ করা হয়েছে।');
    }

    public function customize(Match $match)
    {
        $this->authorize('update', $match);
        
        $themes = OverlayTheme::where('is_active', true)->get();
        $currentTheme = null;
        
        if ($match->overlay_settings && isset($match->overlay_settings['theme_id'])) {
            $currentTheme = OverlayTheme::find($match->overlay_settings['theme_id']);
        }

        return view('themes.customize', compact('match', 'themes', 'currentTheme'));
    }

    public function saveCustomization(Request $request, Match $match)
    {
        $this->authorize('update', $match);

        $request->validate([
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            'background_color' => 'nullable|string|max:7',
            'font_family' => 'nullable|string|max:255',
            'custom_css' => 'nullable|string',
        ]);

        $customization = [
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'text_color' => $request->text_color,
            'background_color' => $request->background_color,
            'font_family' => $request->font_family,
            'custom_css' => $request->custom_css,
        ];

        $match->update([
            'overlay_settings' => array_merge($match->overlay_settings ?? [], [
                'customization' => $customization,
            ])
        ]);

        return redirect()->back()->with('success', 'কাস্টমাইজেশন সফলভাবে সেভ করা হয়েছে।');
    }
}