<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'clients';

    protected $fillable = [
        'first_name',
        'last_name',
        'dni',
        'date_birth',
        'reason',
        'departure_locality',
        'residence_locality',
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

    public function bookings() 
    {
        return $this->hasMany(Booking::class, 'client_id');
    }

    public function guests() 
    {
        return $this->belongsToMany(Booking::class, 'client_bookings');
    }



}
