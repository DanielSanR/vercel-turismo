<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    use HasFactory;

    protected $table = 'observations';

    protected $fillable = [
        'moment',
        'description',
        'booking_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    /**
     * RELACIONES
    */
    public function booking() 
    {
        return $this->belongsTo(Booking::class);
    }


}
