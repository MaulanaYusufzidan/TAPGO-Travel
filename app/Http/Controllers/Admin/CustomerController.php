<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Daftar customer untuk admin: nama, email, jumlah booking, dan
     * total yang sudah dibayar (status payment = paid).
     */
    public function index(Request $request): View
    {
        $customers = User::query()
            ->whereHas('role', fn ($q) => $q->where('slug', 'customer'))
            ->withCount('bookings')
            ->with(['bookings.payments'])
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->get('q');
                $q->where(function ($qq) use ($term) {
                    $qq->where('name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $customers->getCollection()->transform(function (User $user) {
            $user->total_spent = $user->bookings
                ->flatMap(fn ($booking) => $booking->payments)
                ->where('status', 'paid')
                ->sum('amount');

            return $user;
        });

        return view('admin.customers.index', [
            'customers' => $customers,
            'filters' => $request->only('q'),
        ]);
    }

    public function show(User $customer): View
    {
        $customer->load(['bookings.schedule.trip', 'bookings.payments']);

        $totalSpent = $customer->bookings
            ->flatMap(fn ($booking) => $booking->payments)
            ->where('status', 'paid')
            ->sum('amount');

        return view('admin.customers.show', [
            'customer' => $customer,
            'totalSpent' => $totalSpent,
        ]);
    }
}
