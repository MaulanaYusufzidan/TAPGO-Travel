<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Trip;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(): View
    {
        $schedules = Schedule::with('trip')
            ->withCount('bookings')
            ->orderByDesc('date')
            ->paginate(15);

        return view('admin.schedules.index', compact('schedules'));
    }

    public function create(): View
    {
        return view('admin.schedules.create', [
            'trips' => Trip::orderBy('title')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        Schedule::create($validated);

        return redirect()
            ->route('admin.schedules.index')
            ->with('status', 'Schedule berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule): View
    {
        return view('admin.schedules.edit', [
            'schedule' => $schedule,
            'trips' => Trip::orderBy('title')->get(),
        ]);
    }

    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        $validated = $this->validated($request, $schedule);

        // booked_seats tidak boleh diubah manual dari form — dikelola
        // AvailabilityService lewat proses booking (PRD section 16).
        // Tapi kalau admin menaikkan capacity di bawah booked_seats
        // yang sudah ada, tolak supaya data tidak inkonsisten.
        if ($validated['capacity'] < $schedule->booked_seats) {
            return back()->withInput()->withErrors([
                'capacity' => "Capacity tidak boleh kurang dari booked_seats saat ini ({$schedule->booked_seats}).",
            ]);
        }

        $schedule->update($validated);

        return redirect()
            ->route('admin.schedules.index')
            ->with('status', 'Schedule berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        if ($schedule->bookings()->exists()) {
            return back()->withErrors([
                'schedule' => 'Schedule ini punya booking terkait, tidak bisa dihapus.',
            ]);
        }

        $schedule->delete();

        return redirect()
            ->route('admin.schedules.index')
            ->with('status', 'Schedule berhasil dihapus.');
    }

    protected function validated(Request $request, ?Schedule $schedule = null): array
    {
        return $request->validate([
            'trip_id' => ['required', 'exists:trips,id'],
            'date' => ['required', 'date'],
            'departure_time' => ['nullable', 'date_format:H:i'],
            'return_time' => ['nullable', 'date_format:H:i'],
            'capacity' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:available,full,closed,cancelled'],
        ]);
    }
}
