<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\QrCodeService;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function __construct(
        protected TicketService $ticketService,
        protected QrCodeService $qrCode,
    ) {
    }

    /**
     * Tampilkan e-ticket untuk sebuah booking (PRD section 20 Digital
     * Ticket). Hanya bisa diakses oleh pemilik booking, dan hanya
     * kalau booking sudah confirmed (payment lunas).
     */
    public function show(Booking $booking): View|RedirectResponse
    {
        abort_unless($booking->user_id === Auth::id(), 403);

        $ticket = $this->ticketService->generateForBooking($booking);

        if (! $ticket) {
            return redirect()
                ->route('bookings.confirmation', $booking)
                ->with('status', 'Tiket belum tersedia — booking ini belum lunas.');
        }

        return view('tickets.show', [
            'ticket' => $ticket,
            'qrSvg' => $this->qrCode->generateSvg($ticket['booking_code']),
        ]);
    }
}
