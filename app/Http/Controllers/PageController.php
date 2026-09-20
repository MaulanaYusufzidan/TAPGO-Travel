<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    public function hotels(): View
    {
        return view('pages.hotels', ['hotels' => $this->hotelsData()]);
    }

    public function hotel(string $slug): View
    {
        $hotel = collect($this->hotelsData())->firstWhere('slug', $slug) ?? $this->hotelsData()[0];

        return view('pages.hotel-detail', ['hotel' => $hotel]);
    }

    public function flights(): View
    {
        return view('pages.flights', ['flights' => $this->flightsData()]);
    }

    public function flight(string $id): View
    {
        return view('pages.flight-detail', [
            'flight' => collect($this->flightsData())->firstWhere('id', $id) ?? $this->flightsData()[0],
        ]);
    }

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

    /**
     * Static demo inventory for the marketing-style Hotels pages.
     * TAPGO's real bookable, database-backed product is the Trip
     * Package flow (see TripController) — these hotel listings are
     * illustrative browse pages, consistent with how they shipped
     * originally, now extended with enough detail for a hotel page.
     */
    private function hotelsData(): array
    {
        return [
            [
                'slug' => 'the-anvaya-beach-resort-bali',
                'name' => 'The Anvaya Beach Resort Bali',
                'location' => 'Kuta, Bali',
                'rating' => '4.8',
                'rating_label' => 'Exceptional',
                'reviews' => '1,242',
                'price' => '1.250.000',
                'original_price' => '1.470.000',
                'discount' => 15,
                'type' => 'Resort',
                'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=85',
                'gallery' => [
                    'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=800&q=80',
                ],
                'description' => "Beachfront resort in the heart of Kuta with direct access to the sand, three swimming pools, and easy reach to Bali's best sunset spots.",
                'amenities' => ['Free Wi-Fi', 'Beachfront pool', 'Spa & wellness', 'Airport shuttle', 'Free parking', 'Air conditioning'],
                'top_attractions' => ['Kuta Beach (350m)', 'Beachwalk Shopping Center (600m)', 'Waterbom Bali (1.2km)'],
                'nearest_airport' => ['I Gusti Ngurah Rai Airport (12km)'],
                'cafe_bars' => ['SKAI Beach Club (200m)', 'Sundara Beach Club (450m)'],
                'rooms' => [
                    ['name' => 'Deluxe Garden Room', 'notes' => 'Non-refundable · Breakfast included · Instant confirmation', 'price' => '1.250.000'],
                    ['name' => 'Deluxe Ocean View', 'notes' => 'Free cancellation · Breakfast included · Instant confirmation', 'price' => '1.680.000'],
                ],
                'services' => [
                    'transport' => ['Kuta Beach' => '350m', 'Grab pick-up point' => '80m'],
                    'landmarks' => ['Beachwalk Mall' => '600m', 'Discovery Mall' => '900m'],
                    'dining' => ['SKAI Beach Club' => '200m', "Made's Warung" => '500m'],
                    'shopping' => ['Beachwalk Shopping Center' => '600m', 'Kuta Art Market' => '750m'],
                ],
                'review_breakdown' => ['Cleanliness' => 9.0, 'Location' => 9.4, 'Service' => 8.9, 'Value' => 8.6],
            ],
            [
                'slug' => 'maya-ubud-resort-spa',
                'name' => 'Maya Ubud Resort & Spa',
                'location' => 'Ubud, Bali',
                'rating' => '4.7',
                'rating_label' => 'Exceptional',
                'reviews' => '856',
                'price' => '1.780.000',
                'original_price' => '1.780.000',
                'discount' => 0,
                'type' => 'Resort',
                'image' => 'https://images.unsplash.com/photo-1582610116397-edb318620f90?auto=format&fit=crop&w=1200&q=85',
                'gallery' => [
                    'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1601918774946-25832a4be0d6?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1602002418082-a4443e081dd1?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1587874522738-eb1f2c69f2fc?auto=format&fit=crop&w=800&q=80',
                ],
                'description' => 'Set above the Petanu river valley, Maya Ubud blends traditional Balinese architecture with a jungle spa and infinity pool overlooking the gorge.',
                'amenities' => ['Free Wi-Fi', 'Infinity pool', 'Riverside spa', 'Free parking', 'Yoga pavilion', 'Air conditioning'],
                'top_attractions' => ['Ubud Monkey Forest (2.5km)', 'Tegalalang Rice Terrace (5km)', 'Ubud Palace (2km)'],
                'nearest_airport' => ['I Gusti Ngurah Rai Airport (28km)'],
                'cafe_bars' => ['Room4Dessert (1.8km)', 'CasCades Bar (on-site)'],
                'rooms' => [
                    ['name' => 'Valley View Room', 'notes' => 'Non-refundable · Breakfast included · Instant confirmation', 'price' => '1.780.000'],
                    ['name' => 'Pool Villa', 'notes' => 'Free cancellation · Breakfast included · Instant confirmation', 'price' => '2.950.000'],
                ],
                'services' => [
                    'transport' => ['Ubud Center' => '2km', 'Grab pick-up point' => '150m'],
                    'landmarks' => ['Ubud Palace' => '2km', 'Campuhan Ridge Walk' => '3km'],
                    'dining' => ['Room4Dessert' => '1.8km', 'Locavore' => '2.2km'],
                    'shopping' => ['Ubud Art Market' => '2.1km'],
                ],
                'review_breakdown' => ['Cleanliness' => 9.2, 'Location' => 8.8, 'Service' => 9.3, 'Value' => 8.7],
            ],
            [
                'slug' => 'hotel-tentrem-yogyakarta',
                'name' => 'Hotel Tentrem Yogyakarta',
                'location' => 'Yogyakarta, Indonesia',
                'rating' => '4.8',
                'rating_label' => 'Exceptional',
                'reviews' => '694',
                'price' => '1.140.000',
                'original_price' => '1.340.000',
                'discount' => 15,
                'type' => 'Hotel',
                'image' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1200&q=85',
                'gallery' => [
                    'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1590073844006-33379778ae09?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=800&q=80',
                ],
                'description' => 'A modern five-star hotel in central Yogyakarta, minutes from Malioboro Street, with a rooftop pool and classic Javanese hospitality.',
                'amenities' => ['Free Wi-Fi', 'Rooftop pool', 'Fitness center', 'Free parking', 'Airport shuttle', 'Air conditioning'],
                'top_attractions' => ['Malioboro Street (1.5km)', 'Kraton Yogyakarta (2.4km)', 'Tugu Monument (1.1km)'],
                'nearest_airport' => ['Adisutjipto Airport (7km)', 'Yogyakarta International Airport (42km)'],
                'cafe_bars' => ['Vast Restaurant (on-site)', 'Loenpia Cafe (900m)'],
                'rooms' => [
                    ['name' => 'Deluxe Room', 'notes' => 'Non-refundable · Breakfast included · Instant confirmation', 'price' => '1.140.000'],
                    ['name' => 'Executive Suite', 'notes' => 'Free cancellation · Breakfast included · Instant confirmation', 'price' => '1.980.000'],
                ],
                'services' => [
                    'transport' => ['Tugu Station' => '1.3km', 'Grab pick-up point' => '50m'],
                    'landmarks' => ['Malioboro Street' => '1.5km', 'Kraton Yogyakarta' => '2.4km'],
                    'dining' => ['Vast Restaurant' => 'On-site', 'Gudeg Yu Djum' => '2km'],
                    'shopping' => ['Malioboro Mall' => '1.6km'],
                ],
                'review_breakdown' => ['Cleanliness' => 9.1, 'Location' => 9.0, 'Service' => 9.2, 'Value' => 8.9],
            ],
            [
                'slug' => 'padma-hotel-bandung',
                'name' => 'Padma Hotel Bandung',
                'location' => 'Bandung, Indonesia',
                'rating' => '4.7',
                'rating_label' => 'Excellent',
                'reviews' => '932',
                'price' => '1.320.000',
                'original_price' => '1.320.000',
                'discount' => 0,
                'type' => 'Hotel',
                'image' => 'https://images.unsplash.com/photo-1540541338287-41700207dee6?auto=format&fit=crop&w=1200&q=85',
                'gallery' => [
                    'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1590073242678-70ee3fc28f8e?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1560347876-aeef00ee58a1?auto=format&fit=crop&w=800&q=80',
                ],
                'description' => 'Perched in the hills of North Bandung, Padma offers panoramic valley views, an adventure park, and cool mountain air just outside the city.',
                'amenities' => ['Free Wi-Fi', 'Outdoor pool', 'Adventure park', 'Free parking', 'Spa', 'Air conditioning'],
                'top_attractions' => ['Dago Highland (1km)', 'Tebing Keraton (4km)', 'Bandung City Center (9km)'],
                'nearest_airport' => ['Husein Sastranegara Airport (15km)'],
                'cafe_bars' => ['Sunda Kelapa Restaurant (on-site)', 'Sierra Cafe (1.2km)'],
                'rooms' => [
                    ['name' => 'Superior Valley View', 'notes' => 'Non-refundable · Breakfast included · Instant confirmation', 'price' => '1.320.000'],
                    ['name' => 'Padma Suite', 'notes' => 'Free cancellation · Breakfast included · Instant confirmation', 'price' => '2.450.000'],
                ],
                'services' => [
                    'transport' => ['Dago Terminal' => '2km', 'Grab pick-up point' => '100m'],
                    'landmarks' => ['Tebing Keraton' => '4km', 'Dago Highland' => '1km'],
                    'dining' => ['Sunda Kelapa Restaurant' => 'On-site', 'Warung Nasi Ampera' => '3km'],
                    'shopping' => ['Cihampelas Walk' => '9km'],
                ],
                'review_breakdown' => ['Cleanliness' => 8.9, 'Location' => 8.5, 'Service' => 9.0, 'Value' => 8.8],
            ],
        ];
    }

    /**
     * Static demo inventory for the Flights browse pages.
     */
    private function flightsData(): array
    {
        return [
            ['id' => 'ga-402', 'airline' => 'Garuda Indonesia', 'number' => 'GA 402', 'from' => 'CGK', 'fromCity' => 'Jakarta', 'to' => 'DPS', 'toCity' => 'Bali', 'depart' => '08:00', 'arrive' => '11:00', 'duration' => '2h 00m', 'stops' => 'Non-stop', 'price' => '1.450.000', 'original_price' => '1.450.000', 'amenities' => ['Wi-Fi', 'Meal', '20kg baggage']],
            ['id' => 'id-6502', 'airline' => 'Batik Air', 'number' => 'ID 6502', 'from' => 'CGK', 'fromCity' => 'Jakarta', 'to' => 'DPS', 'toCity' => 'Bali', 'depart' => '10:30', 'arrive' => '13:35', 'duration' => '2h 05m', 'stops' => 'Non-stop', 'price' => '1.290.000', 'original_price' => '1.450.000', 'amenities' => ['Meal', '20kg baggage']],
            ['id' => 'jt-018', 'airline' => 'Lion Air', 'number' => 'JT 018', 'from' => 'CGK', 'fromCity' => 'Jakarta', 'to' => 'DPS', 'toCity' => 'Bali', 'depart' => '14:10', 'arrive' => '17:15', 'duration' => '2h 05m', 'stops' => 'Non-stop', 'price' => '1.180.000', 'original_price' => '1.180.000', 'amenities' => ['15kg baggage']],
        ];
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
