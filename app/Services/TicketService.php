<?php

namespace App\Services;

use App\Models\Booking;

/**
 * TicketService
 *
 * PRD section 20 Digital Ticket: "Setelah pembayaran berhasil,
 * customer mendapatkan e-ticket." Ticket tidak punya tabel sendiri
 * di skema (PRD section 28 Database Requirements tidak menyebut
 * tabel 'tickets') — e-ticket adalah data turunan dari Booking yang
 * sudah confirmed/paid, dirakit di sini.
 *
 * QR code generation dan halaman tampilannya menyusul di 2 commit
 * berikutnya.
 */
class TicketService
{
    /**
     * Rakit data e-ticket dari sebuah booking. Return null kalau
     * booking belum lunas (belum berhak dapat tiket).
     *
     * @return array{
     *   booking_code: string,
     *   customer_name: string,
     *   trip_title: string,
     *   destination_name: string,
     *   travel_date: \Illuminate\Support\Carbon,
     *   departure_time: ?string,
     *   return_time: ?string,
     *   meeting_point: ?string,
     *   travelers: array<int, string>,
     *   payment_status: string,
     * }|null
     */
    public function generateForBooking(Booking $booking): ?array
    {
        if (! $this->isEligible($booking)) {
            return null;
        }

        $booking->loadMissing(['user', 'schedule.trip.destination', 'travelers']);

        return [
            'booking_code' => $booking->booking_code,
            'customer_name' => $booking->user->name ?? '-',
            'trip_title' => $booking->schedule->trip->title,
            'destination_name' => $booking->schedule->trip->destination->name,
            'travel_date' => $booking->schedule->date,
            'departure_time' => $booking->schedule->departure_time,
            'return_time' => $booking->schedule->return_time,
            'meeting_point' => $booking->schedule->trip->meeting_point,
            'travelers' => $booking->travelers->pluck('full_name')->all(),
            'payment_status' => $booking->status,
        ];
    }

    /**
     * Booking berhak dapat e-ticket kalau statusnya sudah confirmed
     * (payment lunas) — bukan pending/cancelled/expired.
     */
    public function isEligible(Booking $booking): bool
    {
        return $booking->status === 'confirmed';
    }
}
