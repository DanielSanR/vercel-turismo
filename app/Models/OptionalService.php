<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionalService extends Model
{
    use HasFactory;


    protected $table = 'optional_services';


    protected $fillable = [
        'name',
        'description',
        'price',
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
    ];



    /**
     * RELACIONES
     */

    public function entrepreneurships()
    {
        return $this->morphToMany(Entrepreneurship::class, 'serviceable');
    }

    public function bookings()
    {
        return $this->morphToMany(Booking::class, 'detailable')->withPivot('price_unit','quantity');
    }


}

