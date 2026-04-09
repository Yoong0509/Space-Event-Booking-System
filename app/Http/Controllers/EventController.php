<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category; 

class EventController extends Controller
{
    // show the homepage with the latest 3 events and all categories for filtering
    public function index()
    {
        $categories = Category::all();
        
        $events = Event::with('venue')->latest()->take(3)->get(); 

        return view('welcome', compact('categories', 'events'));
    }

    // all events page with search and filter functionality
    public function allEvents(Request $request)
    {
        $categories = Category::all();
        
        // query builder for events with eager loading of venue
        $query = Event::with('venue')->latest();

        // search functionality: if the user has entered a search term, filter events by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // filter by category: if the user has selected a category, filter events by that category ID
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // get the filtered events
        $events = $query->paginate(9); 

        return view('events', compact('events', 'categories'));
    }
    
    public function show($id)
    {
        // eager load the event with its venue and only the available tickets for display
        $event = Event::with(['venue', 'tickets' => function($query) {
            $query->where('status', 'available');
        }])->findOrFail($id);

        // send the event data to the view for display
        return view('events.show', compact('event'));
    }
}