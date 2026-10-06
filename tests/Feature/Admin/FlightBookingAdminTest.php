<?php

namespace Tests\Feature\Admin;

use App\Models\Airline;
use App\Models\Flight;
use App\Models\FlightBooking;
use App\Models\FlightBookingPassenger;
use App\Models\FlightOffer;
use App\Models\FlightPayment;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlightBookingAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(RolePermissionSeeder::class);
    }

    private function makeBooking(array $overrides = []): FlightBooking
    {
        $customer = User::factory()->create(['name' => 'Rina Customer']);

        $airline = Airline::firstOrCreate(['code' => 'NU'], ['name' => 'Nusantara Airlines']);
        $dep = Flight::create([
            'airline_id' => $airline->id,
            'flight_number' => 'DP100',
            'origin_code' => 'CGK', 'origin_city' => 'Jakarta',
            'destination_code' => 'DPS', 'destination_city' => 'Bali',
            'departure_at' => '2026-12-10 08:30:00',
            'arrival_at' => '2026-12-10 10:20:00',
            'duration_minutes' => 110,
            'stops' => 0,
            'travel_class' => 'Economy',
        ]);
        $offer = FlightOffer::create([
            'departure_flight_id' => $dep->id,
            'base_price' => 1320000,
            'seats_available' => 5,
            'status' => 'published',
        ]);

        static $n = 0;
        $n++;

        $booking = FlightBooking::create(array_merge([
            'booking_code' => 'TPGF-TEST'.str_pad((string) $n, 4, '0', STR_PAD_LEFT),
            'user_id' => $customer->id,
            'flight_offer_id' => $offer->id,
            'passengers' => 1,
            'subtotal' => 1320000,
            'tax' => 145200,
            'service_fee' => 26400,
            'discount' => 0,
            'total' => 1491600,
            'status' => 'awaiting_payment',
            'contact_email' => 'rina@example.test',
            'contact_phone' => '081200000000',
        ], $overrides));

        FlightBookingPassenger::create([
            'flight_booking_id' => $booking->id,
            'first_name' => 'Rina',
            'last_name' => 'Penumpang',
            'passport_number' => 'X1234567',
            'passport_expiry' => '2030-01-01',
            'date_of_birth' => '1995-05-05',
            'gender' => 'female',
            'nationality' => 'Indonesian',
        ]);

        return $booking;
    }

    public function test_admin_can_see_flight_bookings_from_flight_bookings_table(): void
    {
        $booking = $this->makeBooking();
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.flight-bookings.index'))
            ->assertOk()
            ->assertSee($booking->booking_code)
            ->assertSee('Rina Customer')
            ->assertSee('CGK')
            ->assertSee('DPS');

        $this->assertDatabaseCount('bookings', 0);
        $this->assertDatabaseCount('flight_bookings', 1);
    }

    public function test_admin_can_open_flight_booking_detail_with_masked_passport(): void
    {
        $booking = $this->makeBooking();
        FlightPayment::create([
            'flight_booking_id' => $booking->id,
            'payment_code' => 'PAYF-TESTCODE',
            'method' => 'bank_transfer',
            'amount' => 1491600,
            'status' => 'pending',
        ]);
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.flight-bookings.show', $booking))
            ->assertOk()
            ->assertSee($booking->booking_code)
            ->assertSee('Rina Penumpang')
            ->assertSee('PAYF-TESTCODE')
            ->assertSee('1.491.600')
            ->assertSee('567')            // 3 digit terakhir paspor
            ->assertDontSee('X1234567');  // nomor paspor penuh tidak bocor
    }

    public function test_unknown_flight_booking_returns_404(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.flight-bookings.show', 999999))
            ->assertNotFound();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $booking = $this->makeBooking();

        $this->get(route('admin.flight-bookings.index'))->assertRedirect();
        $this->get(route('admin.flight-bookings.show', $booking))->assertRedirect();
    }

    public function test_customer_without_admin_role_is_forbidden(): void
    {
        $booking = $this->makeBooking();
        $customer = User::factory()->create();

        $this->actingAs($customer)->get(route('admin.flight-bookings.index'))->assertForbidden();
        $this->actingAs($customer)->get(route('admin.flight-bookings.show', $booking))->assertForbidden();
    }

    public function test_status_filter_and_search_work(): void
    {
        $confirmed = $this->makeBooking(['status' => 'confirmed']);
        $pending = $this->makeBooking(['status' => 'pending']);
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.flight-bookings.index', ['status' => 'confirmed']))
            ->assertOk()
            ->assertSee($confirmed->booking_code)
            ->assertDontSee($pending->booking_code);

        $this->actingAs($admin)
            ->get(route('admin.flight-bookings.index', ['q' => $pending->booking_code]))
            ->assertOk()
            ->assertSee($pending->booking_code)
            ->assertDontSee($confirmed->booking_code);
    }
}
