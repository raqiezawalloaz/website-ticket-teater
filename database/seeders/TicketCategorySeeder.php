<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\TicketCategory;

class TicketCategorySeeder extends Seeder
{
    public function run(): void
    {
        $events = Event::all();

        foreach ($events as $event) {
            // Update capacity if it's 0 or null
            if (!$event->total_capacity) {
                $event->update(['total_capacity' => 500]);
            }

            TicketCategory::updateOrCreate(
                ['event_id' => $event->id, 'name' => 'VIP'],
                ['price' => 250000, 'quantity' => 50]
            );

            TicketCategory::updateOrCreate(
                ['event_id' => $event->id, 'name' => 'Reguler'],
                ['price' => 100000, 'quantity' => 150]
            );

            TicketCategory::updateOrCreate(
                ['event_id' => $event->id, 'name' => 'Presale'],
                ['price' => 75000, 'quantity' => 100]
            );
        }
    }
}
