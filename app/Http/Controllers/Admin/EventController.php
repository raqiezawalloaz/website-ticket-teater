<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('tanggal_event', 'desc')->get();
        return view('admin.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load(['feedbacks.user', 'feedbacks' => function($q) {
            $q->orderBy('created_at', 'desc');
        }]);
        
        return view('admin.events.show', compact('event'));
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
            'status_event' => 'required|in:aktif,nonaktif',
            'certificate_background' => 'nullable|image|max:2048', // 2MB Max
        ]);

        if ($request->hasFile('certificate_background')) {
            $path = $request->file('certificate_background')->store('certificates', 'public');
            $data['certificate_background'] = $path;
        }

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
            'status_event' => 'required|in:aktif,nonaktif',
            'certificate_background' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('certificate_background')) {
            // Delete old file if exists
            if ($event->certificate_background) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($event->certificate_background);
            }
            $path = $request->file('certificate_background')->store('certificates', 'public');
            $data['certificate_background'] = $path;
        }

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
