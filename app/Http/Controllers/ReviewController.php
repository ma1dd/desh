<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use App\Models\Source;
use App\Models\Topic;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['product', 'source', 'sentimentAnalysis', 'topics']);

        if ($request->filled('search')) {
            $query->where('text', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('source_id')) {
            $query->where('source_id', $request->source_id);
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('region')) {
            $query->where('region', 'like', '%' . $request->region . '%');
        }

        if ($request->filled('sentiment')) {
            $query->whereHas('sentimentAnalysis', function ($q) use ($request) {
                $q->where('label', $request->sentiment);
            });
        }

        if ($request->filled('topic_id')) {
            $query->whereHas('topics', function ($q) use ($request) {
                $q->where('topics.id', $request->topic_id);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('published_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('published_at', '<=', $request->date_to);
        }

        $reviews = $query->latest('published_at')->paginate(20)->withQueryString();

        $products = Product::orderBy('name')->get();
        $sources = Source::orderBy('name')->get();
        $topics = Topic::orderBy('name')->get();

        return view('reviews.index', compact('reviews', 'products', 'sources', 'topics'));
    }

    public function show(Review $review)
    {
        $review->load(['product', 'source', 'sentimentAnalysis', 'topics']);

        return view('reviews.show', compact('review'));
    }
}