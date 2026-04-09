<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function promoCode() {
        return $this->belongsTo(PromoCode::class);
    }

    // relationship to get the tickets associated with this booking, including pivot data like price and quantity
    public function tickets() {
        return $this->belongsToMany(Ticket::class, 'booking_ticket')
                    ->withPivot('price', 'quantity') 
                    ->withTimestamps();
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }

    public function histories() {
        return $this->hasMany(BookingHistory::class);
    }
}