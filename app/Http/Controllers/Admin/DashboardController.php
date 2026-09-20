<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * PRD section 25 Admin Dashboard: KPI + Analytics.
     */
    public function index(): View
    {
        $kpi = [
            'total_revenue' => Payment::where('status', 'paid')->sum('amount'),
            'total_bookings' => Booking::count(),
            'total_customers' => User::whereHas('role', fn ($q) => $q->where('slug', 'customer'))->count(),
            'total_trips' => Trip::count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
        ];

        $monthFormat = match (DB::connection()->getDriverName()) {
            'sqlite' => "strftime('%Y-%m', paid_at)",
            'pgsql' => "to_char(paid_at, 'YYYY-MM')",
            default => "DATE_FORMAT(paid_at, '%Y-%m')",
        };

        $monthlyRevenue = Payment::query()
            ->where('status', 'paid')
            ->selectRaw("{$monthFormat} as month, SUM(amount) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $bookingMonthFormat = match (DB::connection()->getDriverName()) {
            'sqlite' => "strftime('%Y-%m', created_at)",
            'pgsql' => "to_char(created_at, 'YYYY-MM')",
            default => "DATE_FORMAT(created_at, '%Y-%m')",
        };

        $monthlyBooking = Booking::query()
            ->selectRaw("{$bookingMonthFormat} as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $popularDestinations = DB::table('destinations')
            ->join('trips', 'trips.destination_id', '=', 'destinations.id')
            ->join('schedules', 'schedules.trip_id', '=', 'trips.id')
            ->join('bookings', 'bookings.schedule_id', '=', 'schedules.id')
            ->select('destinations.id', 'destinations.name', DB::raw('COUNT(bookings.id) as bookings_count'))
            ->groupBy('destinations.id', 'destinations.name')
            ->orderByDesc('bookings_count')
            ->limit(5)
            ->get();

        $popularTrips = DB::table('trips')
            ->join('schedules', 'schedules.trip_id', '=', 'trips.id')
            ->join('bookings', 'bookings.schedule_id', '=', 'schedules.id')
            ->select('trips.id', 'trips.title', DB::raw('COUNT(bookings.id) as bookings_count'))
            ->groupBy('trips.id', 'trips.title')
            ->orderByDesc('bookings_count')
            ->limit(5)
            ->get();

        $recentBookings = Booking::with(['user', 'schedule.trip'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', [
            'kpi' => $kpi,
            'monthlyRevenue' => $monthlyRevenue,
            'monthlyBooking' => $monthlyBooking,
            'popularDestinations' => $popularDestinations,
            'popularTrips' => $popularTrips,
            'recentBookings' => $recentBookings,
        ]);
    }
}
