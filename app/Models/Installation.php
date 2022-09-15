<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Installation extends Model
{
    use HasFactory;

    protected $table = 'installations';

    protected $fillable = [
        'category',
        'name',
        'description',
        'capacity',
        'price',
        'quantity',
        'image_path',
        'entrepreneurship_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];



    /**
     * RELACIONES
     */

    public function entrepreneurship() 
    {
        return $this->belongsTo(Entrepreneurship::class);
    }
    
    public function localServices()
    {
        return $this->belongsToMany(LocalService::class, 'localservice_installation');
    }

    public function bookings()
    {
        return $this->morphToMany(Booking::class, 'detailable')->withPivot('price_unit','quantity');
    }

    
}
