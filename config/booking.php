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
];
