<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    public function blog(): View
    {
        return view('pages.blog', ['posts' => $this->posts()]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function career(): View
    {
        return view('pages.career', ['jobs' => [
            ['title' => 'Frontend Developer', 'team' => 'Product & Engineering', 'type' => 'Full-time', 'location' => 'Jakarta / Hybrid'],
            ['title' => 'Product Designer', 'team' => 'Product', 'type' => 'Full-time', 'location' => 'Jakarta / Hybrid'],
            ['title' => 'Customer Experience Specialist', 'team' => 'Customer Care', 'type' => 'Full-time', 'location' => 'Jakarta'],
            ['title' => 'Growth Marketing Specialist', 'team' => 'Marketing', 'type' => 'Full-time', 'location' => 'Jakarta / Hybrid'],
        ]]);
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function submitContact(\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        \Illuminate\Support\Facades\Log::info('New contact message received', $data);

        return redirect()->route('contact')->with('status', 'Thanks, ' . $data['name'] . '! Your message has been sent — our team will reply to ' . $data['email'] . ' shortly.');
    }

    public function profile(): View
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        $bookings = $user->bookings()
            ->with(['schedule.trip.destination'])
            ->latest()
            ->get();

        $now = now()->toDateString();

        $upcoming = $bookings->filter(fn ($b) => $b->schedule && $b->schedule->date->toDateString() >= $now && $b->status !== 'cancelled');
        $completed = $bookings->filter(fn ($b) => $b->schedule && $b->schedule->date->toDateString() < $now && $b->status !== 'cancelled');

        return view('pages.profile', [
            'bookings' => $bookings,
            'upcomingCount' => $upcoming->count(),
            'completedCount' => $completed->count(),
            'nextBooking' => $upcoming->sortBy(fn ($b) => $b->schedule->date)->first(),
        ]);
    }

    public function updateProfile(\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($data);

        return back()->with('status', 'Your profile has been updated.');
    }

    public function updatePassword(\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update(['password' => \Illuminate\Support\Facades\Hash::make($data['password'])]);

        return back()->with('status', 'Your password has been changed.');
    }

    private function posts(): array
    {
        return [
            ['category' => 'Destination Guide', 'date' => '12 Sep 2026', 'title' => 'A considered guide to Bali beyond the beach', 'image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=900&q=85'],
            ['category' => 'Travel Tips', 'date' => '04 Sep 2026', 'title' => 'How to make a long weekend feel like a true escape', 'image' => 'https://images.unsplash.com/photo-1500534623283-312aade485b7?auto=format&fit=crop&w=900&q=85'],
            ['category' => 'City Guide', 'date' => '28 Aug 2026', 'title' => 'The art, food, and stories of Yogyakarta', 'image' => 'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=900&q=85'],
        ];
    }
}
