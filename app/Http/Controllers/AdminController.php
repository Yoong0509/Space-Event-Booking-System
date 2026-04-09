<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use App\Models\Inquiry;
use App\Models\User;
use App\Models\Category; 
use App\Models\Venue;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // --- 1. display dashboard ---
    public function index(Request $request) // 🌟 记得这里要加上 Request $request
    {
        $stats = [
            'total_revenue' => Booking::sum('total_amount'),
            'total_tickets_sold' => \DB::table('booking_ticket')->sum('quantity'),
            'total_users' => User::where('role', 'user')->count(),
            'unread_inquiries' => Inquiry::where('status', 'unread')->count(),
        ];

        // receive sorting parameters from the request (default to sorting by id in descending order)
        $sortColumn = $request->input('sort', 'id'); 
        $sortDirection = $request->input('direction', 'desc');

        // only allow sorting by specific columns to prevent SQL injection
        $validColumns = ['id', 'title', 'event_date'];
        if (!in_array($sortColumn, $validColumns)) {
            $sortColumn = 'id';
        }

        // using eager loading to optimize queries when fetching events and their associated venues for the dashboard
        $events = Event::with('venue')
                    ->orderBy($sortColumn, $sortDirection)
                    ->paginate(5); 

        $inquiries = Inquiry::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'events', 'inquiries'));
    }

    // --- 2. display create form (Create) ---
    public function createEvent()
    {
        $categories = Category::all();
        $venues = Venue::all();
        
        // empty event object to differentiate from edit mode in the form view (we can check if $event->id exists to know if it's edit or create mode)
        $event = new Event(); 
        
        return view('admin.events.form', compact('categories', 'venues', 'event'));
    }

    // --- 3. store the event (Store) ---
    public function storeEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|file|max:5120', 
            'tickets' => 'required|array', 
        ]);

        // receive venue data: if the admin filled in the "new venue" field, we create a new venue; otherwise, we use the selected existing venue
        if ($request->filled('new_venue')) {
            $venue = Venue::create([
                'name' => $request->new_venue,
                'address' => $request->new_venue_address ?? 'Address TBA', // receive new venue address, if not filled, set to "Address TBA"
                'capacity' => $request->new_venue_capacity ?? 0,           // receive new venue capacity, if not filled, set to 0
            ]);
            $venueId = $venue->id;
        } else {
            $request->validate(['venue_id' => 'required|exists:venues,id']);
            $venueId = $request->venue_id;
        }

        $imagePath = '';
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $imagePath = '/storage/' . $path;
        }

        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'category_id' => $request->category_id,
            'venue_id' => $venueId,
            'image' => $imagePath,
        ]);

        if ($request->has('tickets')) {
            foreach ($request->tickets as $ticketData) {
                $event->tickets()->create([
                    'name' => $ticketData['name'],
                    'price' => $ticketData['price'],
                    'stock' => $ticketData['quantity'],
                ]);
            }
        }

        return redirect()->route('admin.dashboard')->with('success', 'Event perfectly created!');
    }

    // --- 4. display edit form (Edit) ---
    public function editEvent(Event $event)
    {
        $categories = Category::all();
        $venues = Venue::all();
        
        // the form view will check if $event->id exists to determine if it's in edit mode, and pre-fill the form fields with the event's existing data
        return view('admin.events.form', compact('categories', 'venues', 'event'));
    }

   // --- 5. update the event (Update) ---
    public function updateEvent(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|file|max:5120', 
            'tickets' => 'required|array', 
        ]);

        if ($request->filled('new_venue')) {
            $venue = Venue::create([
                'name' => $request->new_venue,
                'address' => $request->new_venue_address ?? 'Address TBA',
                'capacity' => $request->new_venue_capacity ?? 0,
            ]);
            $venueId = $venue->id;
        } else {
            $request->validate(['venue_id' => 'required|exists:venues,id']);
            $venueId = $request->venue_id;
        }

        $updateData = [
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'category_id' => $request->category_id,
            'venue_id' => $venueId,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $updateData['image'] = '/storage/' . $path; 
        }

        $event->update($updateData);

        if ($request->has('tickets')) {
            $event->tickets()->delete(); 
            foreach ($request->tickets as $ticketData) {
                $event->tickets()->create([
                    'name' => $ticketData['name'],
                    'price' => $ticketData['price'],
                    'stock' => $ticketData['quantity'],
                ]);
            }
        }

        return redirect()->route('admin.dashboard')->with('success', 'Event perfectly updated!');
    }

    // --- 6. delete the event (Delete) ---
    public function destroyEvent(Event $event)
    {
        $event->delete();
        return redirect()->back()->with('success', 'Event deleted successfully!');
    }

    // ==========================================
    // --- 3. Manage Users ---
    // ==========================================
    public function users(Request $request)
    {
        $stats['unread_inquiries'] = Inquiry::where('status', 'unread')->count();
        
        $sortColumn = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'desc');

        $validColumns = ['id', 'name', 'email', 'role'];
        if (!in_array($sortColumn, $validColumns)) {
            $sortColumn = 'id';
        }

        $users = User::orderBy($sortColumn, $sortDirection)->paginate(10);
        return view('admin.users', compact('users', 'stats'));
    }

    public function editUser(User $user)
    {
        $stats['unread_inquiries'] = Inquiry::where('status', 'unread')->count();
        return view('admin.users_edit', compact('user', 'stats'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8', 
            'avatar' => 'nullable|file|max:5120',  // picture is optional, max 5MB, and can be any file type to avoid image validation errors
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // If the aadmin filled in a new password, hash it and save it to the database
        if ($request->filled('password')) {
            $updateData['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        // If the admin uploaded a new avatar, save it to the public/storage/avatars folder
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $updateData['avatar'] = '/storage/' . $path;
        }

        $user->update($updateData);

        return redirect()->route('admin.users')->with('success', 'User details, avatar, and password updated successfully!');
    }

    public function changeRole(User $user)
    {
        $user->role = $user->role === 'admin' ? 'user' : 'admin';
        $user->save();
        return redirect()->back()->with('success', 'User role updated successfully!');
    }

    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully!');
    }

    // ==========================================
    // --- 8. Manage Inquiries ---
    // ==========================================
    public function inquiries()
    {
        $stats['unread_inquiries'] = Inquiry::where('status', 'unread')->count();
        $inquiries = Inquiry::latest()->paginate(10);
        
        return view('admin.inquiries', compact('inquiries', 'stats'));
    }

    public function readInquiry(Inquiry $inquiry)
    {
        $inquiry->update(['status' => 'read']); // 标为已读
        return redirect()->back()->with('success', 'Message marked as read!');
    }

    public function destroyInquiry(Inquiry $inquiry)
    {
        $inquiry->delete();
        return redirect()->back()->with('success', 'Message deleted successfully!');
    }
}