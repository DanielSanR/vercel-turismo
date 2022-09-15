<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'bookings';

    protected $fillable = [
        'phone_contact',
        'adults',
        'minors',
        'date_from',
        'date_to',
        'checkin_date',
        'checkout_date',
        'amount',
        'checkin_employee_id',
        'checkout_employee_id',
        'client_id',
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
    public function client() 
    {
        return $this->belongsTo(Client::class);
    }

    public function guests() 
    {
        return $this->belongsToMany(Client::class, 'client_bookings');
    }

    public function payments() 
    {
        return $this->hasMany(Cashflow::class);
    }

    public function installations() 
    {
        return $this->morphedByMany(Installation::class, 'detailable')->withPivot('price_unit','quantity');
    }

    public function optionalServices() 
    {
        return $this->morphedByMany(OptionalService::class, 'detailable')->withPivot('price_unit','quantity');
    }

    public function extras() 
    {
        return $this->morphedByMany(Extra::class, 'detailable')->withPivot('price_unit','quantity');
    }

    public function employeeCheckin()
    {
        return $this->belongsTo(Employee::class, 'checkin_employee_id', 'id');
    }

    public function employeeCheckout()
    {
        return $this->belongsTo(Employee::class, 'checkout_employee_id', 'id');
    }

    public function observations() 
    {
        return $this->hasMany(Observation::class);
    }


}
