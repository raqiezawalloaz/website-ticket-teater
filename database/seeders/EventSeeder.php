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
        Event::create([
            'nama_event' => 'Teater Romeo & Juliet',
            'deskripsi' => 'Sebuah pementasan teater klasik karya William Shakespeare yang mengisahkan cinta terlarang antara dua remaja dari keluarga bermusuhan.',
            'tanggal_event' => Carbon::now()->addDays(10),
            'lokasi' => 'Gedung Kesenian Jakarta',
            'status_event' => 'aktif',
        ]);

        Event::create([
            'nama_event' => 'Teater Hamlet',
            'deskripsi' => 'Tragedi tentang Pangeran Denmark yang ingin membalas dendam atas kematian ayahnya. Salah satu karya terbaik sepanjang masa.',
            'tanggal_event' => Carbon::now()->addDays(20),
            'lokasi' => 'Taman Ismail Marzuki',
            'status_event' => 'aktif',
        ]);

        Event::create([
            'nama_event' => 'Workshop Akting Dasar',
            'deskripsi' => 'Pelatihan teknik akting dasar bagi pemula yang ingin terjun ke dunia seni peran. Dipandu oleh aktor profesional.',
            'tanggal_event' => Carbon::now()->addDays(15),
            'lokasi' => 'Aula Unpad',
            'status_event' => 'aktif',
        ]);
    }
}
