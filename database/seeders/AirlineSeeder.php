<?php

namespace Database\Seeders;

use App\Models\Airline;
use Illuminate\Database\Seeder;

class AirlineSeeder extends Seeder
{
    /**
     * Brand maskapai fiktif (bukan Garuda Indonesia/Lion Air/Batik Air asli
     * yang sebelumnya dipakai di static flightsData()) — sama alasan dengan
     * kenapa hotel dibrand ulang jadi TAPGO, bukan nama hotel asli.
     */
    protected array $airlines = [
        ['name' => 'Nusantara Airlines', 'code' => 'NU'],
        ['name' => 'Andalas Air', 'code' => 'AD'],
        ['name' => 'Java Sky Airways', 'code' => 'JS'],
        ['name' => 'Borneo Wings', 'code' => 'BW'],
        ['name' => 'Cendrawasih Air', 'code' => 'CW'],
    ];

    public function run(): void
    {
        foreach ($this->airlines as $airline) {
            Airline::updateOrCreate(['code' => $airline['code']], $airline);
        }
    }
}
