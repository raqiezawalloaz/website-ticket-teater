<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('ticketCategories')->orderBy('tanggal_event', 'desc')->get();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_event' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_event' => 'required|date',
            'lokasi' => 'nullable|string|max:255',
            'total_capacity' => 'nullable|integer|min:0',
            'status_event' => 'required|in:aktif,nonaktif',
        ]);

        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dibuat');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'nama_event' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_event' => 'required|date',
            'lokasi' => 'nullable|string|max:255',
            'total_capacity' => 'nullable|integer|min:0',
            'status_event' => 'required|in:aktif,nonaktif',
        ]);

        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diupdate');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus');
    }

    /**
     * Export rundown sederhana untuk event (CSV)
     */
    public function exportRundown(Event $event)
    {
        $filename = 'rundown_event_'.$event->id.'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($event) {
            $out = fopen('php://output', 'w');
            // header
            fputcsv($out, ['Event', 'Tanggal', 'Lokasi', 'Status']);
            fputcsv($out, [$event->nama_event, $event->tanggal_event, $event->lokasi, $event->status_event]);

            fputcsv($out, []);
            fputcsv($out, ['Ticket Categories']);
            fputcsv($out, ['Name', 'Price', 'Quantity']);
            foreach ($event->ticketCategories as $cat) {
                fputcsv($out, [$cat->name, $cat->price, $cat->quantity]);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}
