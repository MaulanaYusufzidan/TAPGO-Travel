<?php

namespace Tests\Feature\Admin;

use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\HotelPayment;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelBookingAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(RolePermissionSeeder::class);
    }

    private function makeBooking(array $overrides = []): HotelBooking
    {
        $customer = $overrides['user'] ?? User::factory()->create(['name' => 'Budi Customer']);
        unset($overrides['user']);

        $hotel = Hotel::firstOrCreate(
            ['slug' => 'tapgo-test-hotel'],
            ['name' => 'TAPGO Test Hotel', 'city' => 'Bandung', 'status' => 'published']
        );

        static $n = 0;
        $n++;

        return HotelBooking::create(array_merge([
            'booking_code' => 'TPGH-TEST'.str_pad((string) $n, 4, '0', STR_PAD_LEFT),
            'user_id' => $customer->id,
            'hotel_id' => $hotel->id,
            'check_in' => '2026-12-01',
            'check_out' => '2026-12-03',
            'guests' => 2,
            'rooms' => 1,
            'subtotal' => 2000000,
            'tax' => 220000,
            'service_fee' => 40000,
            'discount' => 0,
            'total' => 2260000,
            'status' => 'awaiting_payment',
            'guest_name' => 'Siti Tamu',
            'guest_email' => 'siti@example.test',
            'guest_phone' => '081234567890',
        ], $overrides));
    }

    public function test_admin_can_see_hotel_bookings_from_hotel_bookings_table(): void
    {
        $booking = $this->makeBooking();
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.hotel-bookings.index'))
            ->assertOk()
            ->assertSee($booking->booking_code)
            ->assertSee('TAPGO Test Hotel')
            ->assertSee('Budi Customer');

        // Data berasal dari hotel_bookings, bukan tabel bookings milik Trip.
        $this->assertDatabaseCount('bookings', 0);
        $this->assertDatabaseCount('hotel_bookings', 1);
    }

    public function test_admin_can_open_hotel_booking_detail_without_leaking_gateway_payload(): void
    {
        $booking = $this->makeBooking();
        HotelPayment::create([
            'hotel_booking_id' => $booking->id,
            'payment_code' => 'PAYH-TESTCODE',
            'method' => 'qris',
            'amount' => 2260000,
            'status' => 'pending',
            'raw_response' => 'SECRET-GATEWAY-PAYLOAD',
        ]);
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.hotel-bookings.show', $booking))
            ->assertOk()
            ->assertSee($booking->booking_code)
            ->assertSee('Siti Tamu')
            ->assertSee('PAYH-TESTCODE')
            ->assertSee('2.260.000')
            ->assertDontSee('SECRET-GATEWAY-PAYLOAD');
    }

    public function test_unknown_hotel_booking_returns_404(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.hotel-bookings.show', 999999))
            ->assertNotFound();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $booking = $this->makeBooking();

        $this->get(route('admin.hotel-bookings.index'))->assertRedirect();
        $this->get(route('admin.hotel-bookings.show', $booking))->assertRedirect();
    }

    public function test_customer_without_admin_role_is_forbidden(): void
    {
        $booking = $this->makeBooking();
        $customer = User::factory()->create();

        $this->actingAs($customer)->get(route('admin.hotel-bookings.index'))->assertForbidden();
        $this->actingAs($customer)->get(route('admin.hotel-bookings.show', $booking))->assertForbidden();
    }

    public function test_search_and_status_filter_work(): void
    {
        $paid = $this->makeBooking(['status' => 'confirmed', 'guest_name' => 'Tamu Konfirmasi']);
        $pending = $this->makeBooking(['status' => 'pending', 'guest_name' => 'Tamu Pending']);
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.hotel-bookings.index', ['status' => 'confirmed']))
            ->assertOk()
            ->assertSee($paid->booking_code)
            ->assertDontSee($pending->booking_code);

        $this->actingAs($admin)
            ->get(route('admin.hotel-bookings.index', ['q' => $pending->booking_code]))
            ->assertOk()
            ->assertSee($pending->booking_code)
            ->assertDontSee($paid->booking_code);
    }

    public function test_index_is_paginated(): void
    {
        for ($i = 0; $i < 16; $i++) {
            $this->makeBooking();
        }
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('admin.hotel-bookings.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.hotel-bookings.index', ['page' => 2]))->assertOk();
    }
}
