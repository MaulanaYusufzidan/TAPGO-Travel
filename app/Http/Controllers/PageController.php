<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    public function hotels(): View { return view('pages.hotels', ['hotels' => $this->hotelsData()]); }
    public function flights(): View { return view('pages.flights', ['flights' => $this->flightsData()]); }
    public function flight(string $id): View { return view('pages.flight-detail', ['flight' => collect($this->flightsData())->firstWhere('id', $id) ?? $this->flightsData()[0]]); }
    public function blog(): View { return view('pages.blog', ['posts' => $this->posts()]); }
    public function about(): View { return view('pages.about'); }
    public function career(): View { return view('pages.career', ['jobs' => [['title' => 'Frontend Developer', 'team' => 'Product & Engineering', 'type' => 'Full-time', 'location' => 'Jakarta / Hybrid'], ['title' => 'Product Designer', 'team' => 'Product', 'type' => 'Full-time', 'location' => 'Jakarta / Hybrid'], ['title' => 'Customer Experience Specialist', 'team' => 'Customer Care', 'type' => 'Full-time', 'location' => 'Jakarta'], ['title' => 'Growth Marketing Specialist', 'team' => 'Marketing', 'type' => 'Full-time', 'location' => 'Jakarta / Hybrid']]]); }
    public function contact(): View { return view('pages.contact'); }
    public function profile(): View { return view('pages.profile'); }

    private function hotelsData(): array { return [
        ['name' => 'The Anvaya Beach Resort Bali', 'location' => 'Kuta, Bali', 'rating' => '4.8', 'reviews' => '1,242', 'price' => '1.250.000', 'type' => 'Resort', 'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=900&q=85'],
        ['name' => 'Maya Ubud Resort & Spa', 'location' => 'Ubud, Bali', 'rating' => '4.7', 'reviews' => '856', 'price' => '1.780.000', 'type' => 'Resort', 'image' => 'https://images.unsplash.com/photo-1582610116397-edb318620f90?auto=format&fit=crop&w=900&q=85'],
        ['name' => 'Hotel Tentrem Yogyakarta', 'location' => 'Yogyakarta, Indonesia', 'rating' => '4.8', 'reviews' => '694', 'price' => '1.140.000', 'type' => 'Hotel', 'image' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=900&q=85'],
        ['name' => 'Padma Hotel Bandung', 'location' => 'Bandung, Indonesia', 'rating' => '4.7', 'reviews' => '932', 'price' => '1.320.000', 'type' => 'Hotel', 'image' => 'https://images.unsplash.com/photo-1540541338287-41700207dee6?auto=format&fit=crop&w=900&q=85'],
    ]; }
    private function flightsData(): array { return [
        ['id' => 'ga-402', 'airline' => 'Garuda Indonesia', 'number' => 'GA 402', 'from' => 'CGK', 'fromCity' => 'Jakarta', 'to' => 'DPS', 'toCity' => 'Bali', 'depart' => '08:00', 'arrive' => '11:00', 'duration' => '2h 00m', 'stops' => 'Non-stop', 'price' => '1.450.000'],
        ['id' => 'id-6502', 'airline' => 'Batik Air', 'number' => 'ID 6502', 'from' => 'CGK', 'fromCity' => 'Jakarta', 'to' => 'DPS', 'toCity' => 'Bali', 'depart' => '10:30', 'arrive' => '13:35', 'duration' => '2h 05m', 'stops' => 'Non-stop', 'price' => '1.290.000'],
        ['id' => 'jt-018', 'airline' => 'Lion Air', 'number' => 'JT 018', 'from' => 'CGK', 'fromCity' => 'Jakarta', 'to' => 'DPS', 'toCity' => 'Bali', 'depart' => '14:10', 'arrive' => '17:15', 'duration' => '2h 05m', 'stops' => 'Non-stop', 'price' => '1.180.000'],
    ]; }
    private function posts(): array { return [['category'=>'Destination Guide','date'=>'12 Sep 2026','title'=>'A considered guide to Bali beyond the beach','image'=>'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=900&q=85'],['category'=>'Travel Tips','date'=>'04 Sep 2026','title'=>'How to make a long weekend feel like a true escape','image'=>'https://images.unsplash.com/photo-1500534623283-312aade485b7?auto=format&fit=crop&w=900&q=85'],['category'=>'City Guide','date'=>'28 Aug 2026','title'=>'The art, food, and stories of Yogyakarta','image'=>'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=900&q=85']]; }
}
