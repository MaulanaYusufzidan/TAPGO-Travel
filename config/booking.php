<?php

return [
    /*
    |--------------------------------------------------------------------
    | Service Fee Percentage
    |--------------------------------------------------------------------
    |
    | Persentase service fee yang dikenakan di atas subtotal booking
    | (PRD section 18 Checkout Requirements).
    |
    */
    'service_fee_percentage' => env('BOOKING_SERVICE_FEE_PERCENTAGE', 2),

    /*
    |--------------------------------------------------------------------
    | Tax Percentage (PPN)
    |--------------------------------------------------------------------
    |
    | Dipakai oleh HotelBookingService untuk hitung pajak booking hotel.
    |
    */
    'tax_percentage' => env('BOOKING_TAX_PERCENTAGE', 11),
];
