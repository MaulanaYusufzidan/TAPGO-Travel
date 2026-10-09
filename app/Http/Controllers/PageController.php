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
            [
                'slug' => 'considered-guide-to-bali-beyond-the-beach',
                'category' => 'Destination Guide',
                'date' => '12 Sep 2026',
                'title' => 'A considered guide to Bali beyond the beach',
                'image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=900&q=85',
                'excerpt' => 'Discover useful details, local perspective, and a more considered way to travel.',
                'content' => "Bali is far bigger than its beaches. Head inland to Ubud for rice-terrace walks and slow mornings, or north to Munduk for waterfalls without the crowds.\n\nStart your day early — the light is softer, the roads are quieter, and temples feel like they belong to you alone. Pair a cultural stop (a village temple ceremony, a traditional market) with a nature stop (a trek, a swim) so each day has contrast.\n\nWhen it's time to rest, choose accommodation close to what you actually came for rather than the busiest strip — TAPGO's hotel filters make it easy to search by city and amenity so you spend less time commuting and more time exploring.",
            ],
            [
                'slug' => 'make-a-long-weekend-feel-like-a-true-escape',
                'category' => 'Travel Tips',
                'date' => '04 Sep 2026',
                'title' => 'How to make a long weekend feel like a true escape',
                'image' => 'https://images.unsplash.com/photo-1500534623283-312aade485b7?auto=format&fit=crop&w=900&q=85',
                'excerpt' => 'Discover useful details, local perspective, and a more considered way to travel.',
                'content' => "Short trips can feel rushed, but a little planning changes everything. Pick a destination within a 2-hour flight or drive so travel time doesn't eat your weekend.\n\nBook your first night in advance so you land without decisions to make, then leave the rest loose — the best moments on a short trip are usually the ones you didn't schedule.\n\nUse TAPGO's trip packages if you'd rather have the itinerary handled for you, or mix and match flights and hotels yourself if you prefer full control.",
            ],
            [
                'slug' => 'art-food-and-stories-of-yogyakarta',
                'category' => 'City Guide',
                'date' => '28 Aug 2026',
                'title' => 'The art, food, and stories of Yogyakarta',
                'image' => 'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=900&q=85',
                'excerpt' => 'Discover useful details, local perspective, and a more considered way to travel.',
                'content' => "Yogyakarta rewards travelers who slow down. Spend a morning at Malioboro, but save your afternoon for the quieter kampungs around the Kraton where batik and silverwork are still made by hand.\n\nFood here is a story in itself — from angkringan carts after dark to a proper plate of gudeg. Ask locally for the place that's busiest with Yogyakartans, not tourists.\n\nWhen you're ready to stay over, TAPGO lists hotels across the city center and near Malioboro — check real guest reviews before you book.",
            ],
        ];
    }

    public function blogShow(string $slug): View
    {
        $post = collect($this->posts())->firstWhere('slug', $slug);

        abort_unless($post, 404);

        $related = collect($this->posts())->where('slug', '!=', $slug)->take(2);

        return view('pages.blog-detail', ['post' => $post, 'related' => $related]);
    }
}
