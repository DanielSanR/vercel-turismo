<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extra extends Model
{
    use HasFactory;


    protected $table = 'extras';

    protected $fillable = [
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

    public function bookings()
    {
        return $this->morphToMany(Booking::class, 'detailable')->withPivot('price_unit','quantity');
    }




}
