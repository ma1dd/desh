<?php

namespace App\Http\Controllers;

use App\Models\AnalyticalSession;
use App\Models\Product;
use App\Models\Review;
use App\Models\Source;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticalSessionController extends Controller
{
    public function index(Request $request)
    {
        $query = AnalyticalSession::with('user');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $sessions = $query->latest()->paginate(15)->withQueryString();

        return view('sessions.index', compact('sessions'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('sessions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'thoughts' => ['nullable', 'string'],
            'comment' => ['nullable', 'string'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'products' => ['required', 'array', 'min:2', 'max:5'],
            'products.*' => ['required', 'integer', 'exists:products,id'],
        ]);

        $parameters = [
            'date_from' => $validated['date_from'] ?? null,
            'date_to' => $validated['date_to'] ?? null,
            'products' => $validated['products'],
            'thoughts' => $validated['thoughts'] ?? null,
            'comment' => $validated['comment'] ?? null,
        ];

        $reviewQuery = Review::query();

        if (!empty($parameters['date_from'])) {
            $reviewQuery->whereDate('published_at', '>=', $parameters['date_from']);
        }

        if (!empty($parameters['date_to'])) {
            $reviewQuery->whereDate('published_at', '<=', $parameters['date_to']);
        }

        if (!empty($parameters['products'])) {
            $reviewQuery->whereIn('product_id', $parameters['products']);
        }

        $results = [
            'total_reviews' => $reviewQuery->count(),
            'average_rating' => round((float) $reviewQuery->avg('rating'), 1),
        ];

        $session = AnalyticalSession::create([
            'user_id' => Auth::id(),
            'name' => $validated['title'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'parameters' => $parameters,
            'results' => $results,
            'started_at' => now(),
            'finished_at' => now(),
        ]);

        return redirect()->route('sessions.show', $session);
    }

    public function show(Request $request, AnalyticalSession $session)
    {
        $session->load('user');

        $dateFrom = data_get($session->parameters, 'date_from') ?? data_get($session->parameters, 'period.from');
        $dateTo = data_get($session->parameters, 'date_to') ?? data_get($session->parameters, 'period.to');
        $productIds = collect(data_get($session->parameters, 'products', data_get($session->parameters, 'productIds', [])))
            ->filter()
            ->map(fn ($v) => (int) $v)
            ->values();

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->orderBy('name')
            ->get();

        // Данные для фильтров таблицы отзывов
        $sources = Source::query()->orderBy('name')->get();
        $topics = Topic::query()->orderBy('name')->get();

        // Базовый запрос (без подгрузки связей) для метрик по всей выборке
        $base = Review::query()
            ->when($dateFrom, fn ($q) => $q->whereDate('published_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('published_at', '<=', $dateTo))
            ->when($productIds->isNotEmpty(), fn ($q) => $q->whereIn('product_id', $productIds));

        // KPI по всей выборке (не только по последним 200)
        $kpiRow = (clone $base)
            ->leftJoin('sentiment_analyses', 'sentiment_analyses.review_id', '=', 'reviews.id')
            ->selectRaw('COUNT(reviews.id) as total')
            ->selectRaw('ROUND(AVG(reviews.rating), 1) as avg_rating')
            ->selectRaw("SUM(CASE WHEN sentiment_analyses.label = 'positive' THEN 1 ELSE 0 END) as positive")
            ->selectRaw("SUM(CASE WHEN sentiment_analyses.label = 'negative' THEN 1 ELSE 0 END) as negative")
            ->selectRaw("SUM(CASE WHEN sentiment_analyses.label = 'neutral' THEN 1 ELSE 0 END) as neutral")
            ->first();

        $total = (int) ($kpiRow->total ?? 0);
        $avgRating = (float) ($kpiRow->avg_rating ?? 0);
        $positive = (int) ($kpiRow->positive ?? 0);
        $negative = (int) ($kpiRow->negative ?? 0);
        $neutral = (int) ($kpiRow->neutral ?? 0);

        $negShare = $total ? round(($negative / $total) * 100, 1) : 0.0;
        $nps = $total ? round((($positive / $total) - ($negative / $total)) * 100, 1) : 0.0;

        $uniqueTopics = (int) DB::table('review_topic')
            ->join('reviews', 'reviews.id', '=', 'review_topic.review_id')
            ->when($dateFrom, fn ($q) => $q->whereDate('reviews.published_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('reviews.published_at', '<=', $dateTo))
            ->when($productIds->isNotEmpty(), fn ($q) => $q->whereIn('reviews.product_id', $productIds))
            ->distinct('review_topic.topic_id')
            ->count('review_topic.topic_id');

        $kpi = [
            'total' => $total,
            'positive' => $positive,
            'negative' => $negative,
            'neutral' => $neutral,
            'avg_rating' => $avgRating,
            'nps' => $nps,
            'neg_share' => $negShare,
            'unique_topics' => $uniqueTopics,
        ];

        // Сравнение товаров (по всей выборке)
        $byProductRows = (clone $base)
            ->leftJoin('sentiment_analyses', 'sentiment_analyses.review_id', '=', 'reviews.id')
            ->select('reviews.product_id')
            ->selectRaw('COUNT(reviews.id) as total')
            ->selectRaw('ROUND(AVG(reviews.rating), 1) as avg')
            ->selectRaw("SUM(CASE WHEN sentiment_analyses.label = 'positive' THEN 1 ELSE 0 END) as positive")
            ->selectRaw("SUM(CASE WHEN sentiment_analyses.label = 'negative' THEN 1 ELSE 0 END) as negative")
            ->selectRaw("SUM(CASE WHEN sentiment_analyses.label = 'neutral' THEN 1 ELSE 0 END) as neutral")
            ->groupBy('reviews.product_id')
            ->get();

        $topTopicsByProduct = DB::table('review_topic')
            ->join('reviews', 'reviews.id', '=', 'review_topic.review_id')
            ->join('topics', 'topics.id', '=', 'review_topic.topic_id')
            ->when($dateFrom, fn ($q) => $q->whereDate('reviews.published_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('reviews.published_at', '<=', $dateTo))
            ->when($productIds->isNotEmpty(), fn ($q) => $q->whereIn('reviews.product_id', $productIds))
            ->select('reviews.product_id', 'topics.name')
            ->selectRaw('COUNT(*) as cnt')
            ->groupBy('reviews.product_id', 'topics.name')
            ->orderByDesc('cnt')
            ->get()
            ->groupBy('product_id')
            ->map(fn ($rows) => $rows->first()->name ?? null);

        $byProduct = $byProductRows->keyBy('product_id')->map(function ($r, $productId) use ($topTopicsByProduct) {
            return [
                'total' => (int) $r->total,
                'avg' => (float) $r->avg,
                'positive' => (int) $r->positive,
                'negative' => (int) $r->negative,
                'neutral' => (int) $r->neutral,
                'top_topic' => $topTopicsByProduct->get($productId),
            ];
        });

        // Топ тем + доля негатива по теме (по всей выборке)
        $topicStats = DB::table('review_topic')
            ->join('reviews', 'reviews.id', '=', 'review_topic.review_id')
            ->join('topics', 'topics.id', '=', 'review_topic.topic_id')
            ->leftJoin('sentiment_analyses', 'sentiment_analyses.review_id', '=', 'reviews.id')
            ->when($dateFrom, fn ($q) => $q->whereDate('reviews.published_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('reviews.published_at', '<=', $dateTo))
            ->when($productIds->isNotEmpty(), fn ($q) => $q->whereIn('reviews.product_id', $productIds))
            ->selectRaw('topics.id as topic_id')
            ->selectRaw('topics.name as name')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw("ROUND(100 * SUM(CASE WHEN sentiment_analyses.label = 'negative' THEN 1 ELSE 0 END) / NULLIF(COUNT(*), 0), 1) as neg_share")
            ->groupBy('topics.id', 'topics.name')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->map(fn ($r) => ['name' => $r->name, 'count' => (int) $r->count, 'neg_share' => (float) $r->neg_share]);

        // Динамика по дням (по всей выборке)
        $daily = (clone $base)
            ->leftJoin('sentiment_analyses', 'sentiment_analyses.review_id', '=', 'reviews.id')
            ->whereNotNull('reviews.published_at')
            ->selectRaw('DATE(reviews.published_at) as day')
            ->selectRaw("SUM(CASE WHEN sentiment_analyses.label = 'positive' THEN 1 ELSE 0 END) as positive")
            ->selectRaw("SUM(CASE WHEN sentiment_analyses.label = 'negative' THEN 1 ELSE 0 END) as negative")
            ->selectRaw("SUM(CASE WHEN sentiment_analyses.label = 'neutral' THEN 1 ELSE 0 END) as neutral")
            ->selectRaw('ROUND(AVG(reviews.rating), 2) as avg_rating')
            ->groupBy(DB::raw('DATE(reviews.published_at)'))
            ->orderBy('day')
            ->get()
            ->map(fn ($r) => [
                'day' => (string) $r->day,
                'positive' => (int) $r->positive,
                'negative' => (int) $r->negative,
                'neutral' => (int) $r->neutral,
                'avg_rating' => (float) $r->avg_rating,
            ]);

        // Отзывы для таблицы (пагинация по 20)
        $reviewsQuery = (clone $base)
            ->with(['product.category', 'source', 'sentimentAnalysis', 'topics'])
            ->latest('published_at');

        // Фильтры таблицы отзывов (как на reviews.index)
        if ($request->filled('product_id')) {
            $reviewsQuery->where('product_id', $request->product_id);
        }

        if ($request->filled('source_id')) {
            $reviewsQuery->where('source_id', $request->source_id);
        }

        if ($request->filled('rating')) {
            $reviewsQuery->where('rating', $request->rating);
        }

        if ($request->filled('region')) {
            $reviewsQuery->where('region', 'like', '%' . $request->region . '%');
        }

        if ($request->filled('sentiment')) {
            $reviewsQuery->whereHas('sentimentAnalysis', function ($q) use ($request) {
                $q->where('label', $request->sentiment);
            });
        }

        if ($request->filled('topic_id')) {
            $reviewsQuery->whereHas('topics', function ($q) use ($request) {
                $q->where('topics.id', $request->topic_id);
            });
        }

        if ($request->filled('date_from')) {
            $reviewsQuery->whereDate('published_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $reviewsQuery->whereDate('published_at', '<=', $request->date_to);
        }

        $reviews = $reviewsQuery
            ->paginate(5, ['reviews.*'], 'reviewsPage')
            ->withQueryString();

        $findings = $this->buildFindings($kpi, $byProduct, $topicStats);

        return view('sessions.show', compact(
            'session',
            'products',
            'reviews',
            'sources',
            'topics',
            'kpi',
            'daily',
            'byProduct',
            'topicStats',
            'findings',
            'dateFrom',
            'dateTo',
        ));
    }

    public function rerun(AnalyticalSession $session)
    {
        $dateFrom = data_get($session->parameters, 'date_from') ?? data_get($session->parameters, 'period.from');
        $dateTo = data_get($session->parameters, 'date_to') ?? data_get($session->parameters, 'period.to');
        $productIds = collect(data_get($session->parameters, 'products', data_get($session->parameters, 'productIds', [])))
            ->filter()
            ->map(fn ($v) => (int) $v)
            ->values();

        $reviewQuery = Review::query()
            ->when($dateFrom, fn ($q) => $q->whereDate('published_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('published_at', '<=', $dateTo))
            ->when($productIds->isNotEmpty(), fn ($q) => $q->whereIn('product_id', $productIds));

        $results = [
            'total_reviews' => $reviewQuery->count(),
            'average_rating' => round((float) $reviewQuery->avg('rating'), 1),
        ];

        $session->update([
            'results' => $results,
            'finished_at' => now(),
        ]);

        return redirect()->route('sessions.show', $session)->with('success', 'Анализ перезапущен.');
    }

    public function exportCsv(AnalyticalSession $session): StreamedResponse
    {
        $dateFrom = data_get($session->parameters, 'date_from') ?? data_get($session->parameters, 'period.from');
        $dateTo = data_get($session->parameters, 'date_to') ?? data_get($session->parameters, 'period.to');
        $productIds = collect(data_get($session->parameters, 'products', data_get($session->parameters, 'productIds', [])))
            ->filter()
            ->map(fn ($v) => (int) $v)
            ->values();

        $query = Review::query()
            ->with(['product', 'source', 'sentimentAnalysis', 'topics'])
            ->when($dateFrom, fn ($q) => $q->whereDate('published_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('published_at', '<=', $dateTo))
            ->when($productIds->isNotEmpty(), fn ($q) => $q->whereIn('product_id', $productIds))
            ->orderByDesc('published_at');

        $filename = "session-{$session->id}-reviews.csv";

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['id', 'date', 'product', 'source', 'rating', 'sentiment', 'topics', 'region', 'text']);
            $query->chunk(500, function ($chunk) use ($out) {
                foreach ($chunk as $r) {
                    fputcsv($out, [
                        $r->id,
                        optional($r->published_at)->toDateString(),
                        $r->product->name ?? '',
                        $r->source->name ?? '',
                        $r->rating,
                        $r->sentimentAnalysis?->label,
                        $r->topics->pluck('name')->implode('; '),
                        $r->region,
                        preg_replace('/\s+/', ' ', trim($r->text)),
                    ]);
                }
            });
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function buildFindings(array $kpi, $byProduct, $topicStats): array
    {
        $lines = [];

        if (($kpi['total'] ?? 0) === 0) {
            return [
                'summary' => ['За выбранный период отзывов не найдено — измените период или список товаров.'],
                'recommendations' => [],
            ];
        }

        $lines[] = "Средний рейтинг за период: {$kpi['avg_rating']} / 5.";
        $lines[] = "Доля негатива: {$kpi['neg_share']}%. Индекс NPS: {$kpi['nps']}.";

        $best = $byProduct->sortByDesc('avg')->keys()->first();
        $worstNeg = $byProduct->sortByDesc('negative')->keys()->first();

        if ($best) {
            $lines[] = "Лидер по рейтингу: товар #{$best}.";
        }
        if ($worstNeg) {
            $lines[] = "Товар с наибольшим числом негативных отзывов: товар #{$worstNeg}.";
        }

        $topProblem = $topicStats->sortByDesc('neg_share')->first();
        $reco = [];
        if ($topProblem && ($topProblem['neg_share'] ?? 0) >= 50) {
            $reco[] = "Проблемная тема: «{$topProblem['name']}» (негатив {$topProblem['neg_share']}%). Проверьте процессы и подготовьте корректирующие действия.";
        }

        return [
            'summary' => $lines,
            'recommendations' => $reco,
        ];
    }

    public function destroy(AnalyticalSession $session)
    {
        $session->delete();

        return redirect()->route('sessions.index')
            ->with('success', 'Аналитическая сессия удалена.');
    }
}