<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Booking;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\PromoCode;
use Carbon\Carbon;

class BookingController extends Controller
{
public function checkout(Request $request)
    {
        // prevent empty submission (no tickets selected)
        $quantities = array_filter($request->input('quantities', [])); 

        if (empty($quantities)) {
            return redirect()->back()->with('error', 'Please select at least one ticket.');
        }

        // find the selected tickets and calculate total price
        $tickets = Ticket::whereIn('id', array_keys($quantities))->get();
        
        $totalAmount = 0;
        $orderItems = [];

        foreach ($tickets as $ticket) {
            $qty = $quantities[$ticket->id];
            
            // if user tries to book more than available stock, show error
            if ($qty > $ticket->stock) {
                return redirect()->back()->with('error', "Not enough stock for {$ticket->name}. Only {$ticket->stock} left.");
            }

            $subtotal = $ticket->price * $qty;
            $totalAmount += $subtotal;
            
            // clear structure for order items to be used in the checkout view
            $orderItems[] = [
                'ticket' => $ticket,
                'quantity' => $qty,
                'subtotal' => $subtotal
            ];
        }

        $event = $tickets->first()->event;
        return view('bookings.checkout', compact('orderItems', 'totalAmount', 'event'));
    }

    //store the booking after payment confirmation
    public function store(Request $request)
    {
        // get the selected tickets and quantities from the request
        $quantities = array_filter($request->input('quantities', []));
        $tickets = \App\Models\Ticket::whereIn('id', array_keys($quantities))->get();
        $totalAmount = 0;
        foreach ($tickets as $ticket) {
            $totalAmount += $ticket->price * $quantities[$ticket->id];
        }

        $discountAmount = 0;
        $promoId = null;
        $promo = null;

        // process promo code if provided
        $promoCodeInput = $request->input('promo_code');
        if ($promoCodeInput) {
            $promo = PromoCode::where('code', strtoupper($promoCodeInput))->first();
            if ($promo && 
               (!$promo->valid_until || Carbon::now()->lessThanOrEqualTo($promo->valid_until)) && 
               ($promo->usage_limit === null || $promo->used_count < $promo->usage_limit)) 
            {
                if ($promo->type === 'percentage') {
                    $discountAmount = ($totalAmount * $promo->value) / 100;
                } else {
                    $discountAmount = $promo->value;
                }
                if ($discountAmount > $totalAmount) $discountAmount = $totalAmount;
                
                $totalAmount = $totalAmount - $discountAmount;
                $promoId = $promo->id;
            }
        }

        // create the booking record in the database
        $booking = \App\Models\Booking::create([
            'booking_reference' => 'TK-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'user_id' => Auth::id(),
            'event_id' => $tickets->first()->event_id,
            'total_amount' => $totalAmount,
            'promo_code_id' => $promoId, 
            'discount_amount' => $discountAmount, 
            'status' => 'confirmed', 
            'payment_method' => $request->payment_method,
            'paid_at' => now(),
        ]);

        // if a promo code was used, increment its used_count
        if ($promo) {
            $promo->increment('used_count');
        }

        // Deducting inventory and related intermediate tables
        foreach ($tickets as $ticket) {
            $qty = $quantities[$ticket->id];
            $booking->tickets()->attach($ticket->id, [
                'price' => $ticket->price,
                'quantity' => $qty,
            ]);
            $ticket->decrement('stock', $qty);
            if ($ticket->stock <= 0) {
                $ticket->update(['status' => 'sold']);
            }
        }

        return redirect()->route('booking.success', $booking->id);    
    }


    // payment page where user can review order and enter payment details
    public function payment(Request $request)
    {
        $request->validate([
            'quantities' => 'required|array',
            'payment_method' => 'required|string',
            'promo_code' => 'nullable|string'
        ]);

        $quantities = array_filter($request->input('quantities', []));
        $paymentMethod = $request->input('payment_method');
        $promoCodeInput = $request->input('promo_code');

        $tickets = \App\Models\Ticket::whereIn('id', array_keys($quantities))->get();
        $totalAmount = 0;
        foreach ($tickets as $ticket) {
            $totalAmount += $ticket->price * $quantities[$ticket->id];
        }

        $discountAmount = 0;
        $promoId = null;

        // process promo code if provided
        if ($promoCodeInput) {
            $promo = PromoCode::where('code', strtoupper($promoCodeInput))->first();
            
            if (!$promo) {
                return redirect()->back()->with('error', 'Invalid Promo Code.');
            }
            // check if promo code is expired
            if ($promo->valid_until && Carbon::now()->greaterThan($promo->valid_until)) {
                return redirect()->back()->with('error', 'This Promo Code has expired.');
            }
            // check if promo code has reached its usage limit
            if ($promo->usage_limit !== null && $promo->used_count >= $promo->usage_limit) {
                return redirect()->back()->with('error', 'This Promo Code has reached its usage limit.');
            }

            // calculate discount based on promo type
            if ($promo->type === 'percentage') {
                $discountAmount = ($totalAmount * $promo->value) / 100;
            } else {
                $discountAmount = $promo->value;
            }

            // make sure discount does not exceed total amount
            if ($discountAmount > $totalAmount) {
                $discountAmount = $totalAmount;
            }

            $totalAmount = $totalAmount - $discountAmount;
            $promoId = $promo->id; // record it for the next page
        }

        return view('bookings.payment', compact('quantities', 'paymentMethod', 'totalAmount', 'discountAmount', 'promoCodeInput', 'promoId'));
    }

    // payment success page showing the receipt and e-tickets
    public function success(\App\Models\Booking $booking)
    {
        // make sure the user can only see their own booking
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // eager load related event and tickets data for display
        $booking->load('event.venue', 'tickets');

        return view('bookings.success', compact('booking'));
    }

    // display the details of a specific booking, including the tickets and event info
    public function show(\App\Models\Booking $booking)
    {
        // security check: only allow the owner of the booking to view it
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $booking->load('event.venue', 'tickets');

        return view('bookings.show', compact('booking'));
    }
}