<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Illuminate\Support\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::firstOrCreate([
            'nama_event' => 'Pertunjukan Teater Klasik',
        ], [
            'deskripsi' => 'Pertunjukan drama klasik dengan pemain lokal.',
            'tanggal_event' => Carbon::now()->addDays(7),
            'lokasi' => 'Teater Utama',
            'status_event' => 'aktif',
        ]);

        Event::firstOrCreate([
            'nama_event' => 'Musikal Modern',
        ], [
            'deskripsi' => 'Musikal kontemporer dengan musik live.',
            'tanggal_event' => Carbon::now()->addDays(20),
            'lokasi' => 'Gedung Kesenian',
            'status_event' => 'aktif',
        ]);

        Event::firstOrCreate([
            'nama_event' => 'Workshop Akting',
        ], [
            'deskripsi' => 'Workshop intensif untuk calon aktor dan aktris.',
            'tanggal_event' => Carbon::now()->addDays(30),
            'lokasi' => 'Studio Teater',
            'status_event' => 'nonaktif',
        ]);
    }
}
