<?php

namespace App\Http\Controllers;

use App\Models\AnalyticalSession;
use App\Models\Product;
use App\Models\Review;
use App\Models\SentimentAnalysis;
use App\Models\Source;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalReviews = Review::count();
        $averageRating = round((float) Review::avg('rating'), 1);
        $activeSources = Source::where('is_active', true)->count();
        $newProducts = Product::whereDate('created_at', '>=', now()->subDays(30))->count();

        $positivePercent = 0;
        $positiveCount = SentimentAnalysis::where('label', 'positive')->count();
        $allAnalyzed = SentimentAnalysis::count();

        if ($allAnalyzed > 0) {
            $positivePercent = round(($positiveCount / $allAnalyzed) * 100);
        }

        $latestSessions = AnalyticalSession::with('user')
            ->latest()
            ->take(5)
            ->get();

        $chartData = collect(range(1, 7))->map(function ($day) {
            return [
                'day' => 'День ' . $day,
                'positive' => fake()->numberBetween(60, 95),
                'negative' => fake()->numberBetween(3, 25),
            ];
        });

        return view('dashboard', compact(
            'totalReviews',
            'averageRating',
            'activeSources',
            'newProducts',
            'positivePercent',
            'latestSessions',
            'chartData'
        ));
    }
}