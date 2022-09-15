<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cashflow extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cashflows';

    protected $fillable = [
        'detail',
        'amount',
        'type',
        'booking_id',
        'entrepreneurship_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     *  Delete_at
     */
    protected $dates = ['deleted_at'];


    
    /**
     * RELACIONES
    */
    public function entrepreneurship() 
    {
        return $this->belongsTo(Entrepreneurship::class);
    }

    public function booking() 
    {
        return $this->belongsTo(Booking::class);
    }

}
