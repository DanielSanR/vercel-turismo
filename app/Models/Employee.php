<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = [
        'first_name',
        'last_name',
        'dni',
        'email',
        'phone',
        'image_path',
        'entrepreneurship_id',
        'user_id',
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function getFullNameAttribute() 
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }


    /**
     * RELACIONES
    */

    public function entrepreneurship() 
    {
        return $this->belongsTo(Entrepreneurship::class);
    }

    public function bookingsCheckin()
    {
        return $this->hasMany(Booking::class, 'checkin_employee_id', 'id');
    }

    public function bookingsCheckout()
    {
        return $this->hasMany(Booking::class, 'checkout_employee_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
