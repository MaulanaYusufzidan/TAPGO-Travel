<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Trip;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TripController extends Controller
{
    public function index(): View
    {
        $trips = Trip::with('destination', 'category')
            ->withCount('schedules')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.trips.index', compact('trips'));
    }

    public function create(): View
    {
        return view('admin.trips.create', [
            'destinations' => Destination::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = $validated['slug'] ?? '' ?: Str::slug($validated['title']);

        Trip::create($validated);

        return redirect()
            ->route('admin.trips.index')
            ->with('status', 'Trip berhasil ditambahkan.');
    }

    public function edit(Trip $trip): View
    {
        return view('admin.trips.edit', [
            'trip' => $trip,
            'destinations' => Destination::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Trip $trip): RedirectResponse
    {
        $validated = $this->validated($request, $trip);
        $validated['slug'] = $validated['slug'] ?? '' ?: Str::slug($validated['title']);

        $trip->update($validated);

        return redirect()
            ->route('admin.trips.index')
            ->with('status', 'Trip berhasil diperbarui.');
    }

    public function destroy(Trip $trip): RedirectResponse
    {
        $trip->delete();

        return redirect()
            ->route('admin.trips.index')
            ->with('status', 'Trip berhasil dihapus.');
    }

    protected function validated(Request $request, ?Trip $trip = null): array
    {
        $validated = $request->validate([
            'destination_id' => ['required', 'exists:destinations,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable', 'string', 'max:255',
                'unique:trips,slug'.($trip ? ','.$trip->id : ''),
            ],
            'description' => ['nullable', 'string'],
            'meeting_point' => ['nullable', 'string', 'max:255'],
            'duration' => ['nullable', 'string', 'max:50'],
            'min_group_size' => ['nullable', 'integer', 'min:1'],
            'max_group_size' => ['nullable', 'integer', 'gte:min_group_size'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['required', 'in:draft,published'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');

        return $validated;
    }
}
