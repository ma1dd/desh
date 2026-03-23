<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TopicController extends Controller
{
    public function index()
    {
        $topics = Topic::latest()->paginate(15);

        return view('admin.topics.index', compact('topics'));
    }

    public function create()
    {
        return view('admin.topics.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'keywords' => ['nullable', 'string'],
        ]);

        Topic::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'] . '-' . uniqid()),
            'keywords' => $validated['keywords'] ?? null,
        ]);

        return redirect()->route('admin.topics.index')
            ->with('success', 'Тема создана.');
    }

    public function edit(Topic $topic)
    {
        return view('admin.topics.edit', compact('topic'));
    }

    public function update(Request $request, Topic $topic)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'keywords' => ['nullable', 'string'],
        ]);

        $topic->update([
            'name' => $validated['name'],
            'keywords' => $validated['keywords'] ?? null,
        ]);

        return redirect()->route('admin.topics.index')
            ->with('success', 'Тема обновлена.');
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();

        return redirect()->route('admin.topics.index')
            ->with('success', 'Тема удалена.');
    }
}