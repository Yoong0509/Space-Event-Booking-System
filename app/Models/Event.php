<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = []; // allows mass assignment for all fields, be careful in production!

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function venue() {
        return $this->belongsTo(Venue::class);
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

    public function bookings() {
        return $this->hasMany(Booking::class);
    }
}