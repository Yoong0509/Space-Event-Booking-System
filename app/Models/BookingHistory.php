<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BookingHistory extends Model
{
    protected $guarded = [];

    public function booking() {
        return $this->belongsTo(Booking::class);
    }
}