<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelPolicySeeder extends Seeder
{
    public function run(): void
    {
        Hotel::all()->each(function (Hotel $hotel) {
            $isPetFriendly = $hotel->amenities()->where('name', 'Pet Friendly')->exists();

            $hotel->policy()->updateOrCreate([], [
                'check_in_policy' => "Check-in mulai pukul {$hotel->check_in_time->format('H:i')}. Tunjukkan KTP/paspor asli saat check-in.",
                'check_out_policy' => "Check-out sebelum pukul {$hotel->check_out_time->format('H:i')}. Late check-out tergantung ketersediaan kamar.",
                'child_policy' => 'Anak di bawah 6 tahun menginap gratis menggunakan tempat tidur yang sudah ada. Extra bed tersedia dengan biaya tambahan.',
                'pet_policy' => $isPetFriendly
                    ? 'Hewan peliharaan diperbolehkan dengan biaya tambahan, maksimal 2 ekor per kamar.'
                    : 'Hewan peliharaan tidak diperbolehkan, kecuali hewan pemandu bagi disabilitas.',
                'smoking_policy' => 'Dilarang merokok di seluruh area kamar. Tersedia area merokok khusus di lobi/luar gedung.',
                'cancellation_policy' => 'Pembatalan gratis hingga 24 jam sebelum check-in. Pembatalan setelahnya dikenakan biaya 1 malam.',
                'payment_policy' => 'Pembayaran penuh dilakukan di muka melalui aplikasi. Kartu kredit ditahan sebagai jaminan untuk insidental di hotel.',
            ]);
        });
    }
}
