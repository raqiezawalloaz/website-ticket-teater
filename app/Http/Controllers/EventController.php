<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('status_event', 'aktif')->orderBy('tanggal_event', 'asc')->get();
        return view('user.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        return view('user.events.show', compact('event'));
    }
}
