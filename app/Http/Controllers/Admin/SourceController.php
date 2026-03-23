<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Source;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    public function index()
    {
        $sources = Source::latest()->paginate(15);

        return view('admin.sources.index', compact('sources'));
    }

    public function create()
    {
        return view('admin.sources.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'base_url' => ['nullable', 'url'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Source::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'base_url' => $validated['base_url'] ?? null,
            'settings' => [],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.sources.index')
            ->with('success', 'Источник создан.');
    }

    public function edit(Source $source)
    {
        return view('admin.sources.edit', compact('source'));
    }

    public function update(Request $request, Source $source)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'base_url' => ['nullable', 'url'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $source->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'base_url' => $validated['base_url'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.sources.index')
            ->with('success', 'Источник обновлён.');
    }

    public function destroy(Source $source)
    {
        $source->delete();

        return redirect()->route('admin.sources.index')
            ->with('success', 'Источник удалён.');
    }
}