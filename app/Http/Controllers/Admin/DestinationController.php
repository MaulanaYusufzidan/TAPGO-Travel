<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(): View
    {
        $destinations = Destination::withCount('trips')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.destinations.index', compact('destinations'));
    }

    public function create(): View
    {
        return view('admin.destinations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $validated['slug'] = $validated['slug'] ?? '' ?: Str::slug($validated['name']);

        if ($request->hasFile('hero_image')) {
            $validated['hero_image'] = $request->file('hero_image')->store('destinations', 'public');
        }

        Destination::create($validated);

        return redirect()
            ->route('admin.destinations.index')
            ->with('status', 'Destinasi berhasil ditambahkan.');
    }

    public function edit(Destination $destination): View
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    public function update(Request $request, Destination $destination): RedirectResponse
    {
        $validated = $this->validated($request, $destination);

        $validated['slug'] = $validated['slug'] ?? '' ?: Str::slug($validated['name']);

        if ($request->hasFile('hero_image')) {
            $validated['hero_image'] = $request->file('hero_image')->store('destinations', 'public');
        }

        $destination->update($validated);

        return redirect()
            ->route('admin.destinations.index')
            ->with('status', 'Destinasi berhasil diperbarui.');
    }

    public function destroy(Destination $destination): RedirectResponse
    {
        $destination->delete();

        return redirect()
            ->route('admin.destinations.index')
            ->with('status', 'Destinasi berhasil dihapus.');
    }

    protected function validated(Request $request, ?Destination $destination = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable', 'string', 'max:255',
                'unique:destinations,slug'.($destination ? ','.$destination->id : ''),
            ],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'hero_image' => ['nullable', 'image', 'max:4096'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['required', 'in:draft,published'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');

        return $validated;
    }
}
