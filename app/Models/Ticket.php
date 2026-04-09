<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $guarded = [];

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function bookings() {
        return $this->belongsToMany(Booking::class, 'booking_ticket')
                    ->withPivot('price', 'quantity') 
                    ->withTimestamps();
    }
}