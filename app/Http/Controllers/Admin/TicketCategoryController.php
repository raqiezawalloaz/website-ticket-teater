<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketCategory;
use App\Models\Event;
use Illuminate\Http\Request;

class TicketCategoryController extends Controller
{
    public function index(Event $event)
    {
        $categories = $event->ticketCategories()->get();
        return view('admin.events.categories.index', compact('event', 'categories'));
    }

    public function create(Event $event)
    {
        return view('admin.events.categories.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        $data['event_id'] = $event->id;
        TicketCategory::create($data);

        return redirect()->route('admin.events.categories.index', $event)->with('success', 'Kategori tiket ditambah');
    }

    public function edit(Event $event, TicketCategory $category)
    {
        return view('admin.events.categories.edit', compact('event', 'category'));
    }

    public function update(Request $request, Event $event, TicketCategory $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        $category->update($data);
        return redirect()->route('admin.events.categories.index', $event)->with('success', 'Kategori tiket diperbarui');
    }

    public function destroy(Event $event, TicketCategory $category)
    {
        $category->delete();
        return redirect()->route('admin.events.categories.index', $event)->with('success', 'Kategori tiket dihapus');
    }
}
