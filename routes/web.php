<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController; 
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

Route::get('/', [EventController::class, 'index'])->name('home');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
Route::get('/events', [EventController::class, 'allEvents'])->name('events.index');

Route::get('/about', function () {
    return view('additional.about'); 
})->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

Route::get('/history', function () {
    // take the bookings of the current user, and also load the related event, venue, and tickets data
    $bookings = Booking::where('user_id', Auth::id())
                        ->with(['event.venue', 'tickets'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(6);
                        

    // bring the bookings data to the history view
    return view('history', compact('bookings'));
})->middleware(['auth', 'verified'])->name('history');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/checkout', [BookingController::class, 'checkout'])->name('checkout');
    Route::post('/payment', [BookingController::class, 'payment'])->name('payment.show');
    Route::post('/book', [BookingController::class, 'store'])->name('book.store');
    Route::get('/booking/{booking}/success', [BookingController::class, 'success'])->name('booking.success');
    Route::get('/bookings/{booking}/tickets', [BookingController::class, 'show'])->name('bookings.show');
});

Route::middleware(['auth', 'can:admin-access'])->prefix('admin')->name('admin.')->group(function () {
    
    // 1. display admin (Dashboard)
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // 2. event management 
    // display create form 
    Route::get('/events/create', [AdminController::class, 'createEvent'])->name('events.create');
    // receive form data and store in database 
    Route::post('/events', [AdminController::class, 'storeEvent'])->name('events.store');
    
    // display edit form 
    Route::get('/events/{event}/edit', [AdminController::class, 'editEvent'])->name('events.edit');
    // receive update data and update database (Update)
    Route::put('/events/{event}', [AdminController::class, 'updateEvent'])->name('events.update');
    
    // delete event (Delete)
    Route::delete('/events/{event}', [AdminController::class, 'destroyEvent'])->name('events.destroy');

    // 3. user management 
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::patch('/users/{user}/role', [AdminController::class, 'changeRole'])->name('users.role');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    // 4. Inquiries management
    Route::get('/inquiries', [AdminController::class, 'inquiries'])->name('inquiries');
    Route::patch('/inquiries/{inquiry}/read', [AdminController::class, 'readInquiry'])->name('inquiries.read');
    Route::delete('/inquiries/{inquiry}', [AdminController::class, 'destroyInquiry'])->name('inquiries.destroy');
});

require __DIR__.'/auth.php';