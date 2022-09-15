<?php

namespace App\Models;

use App\Models\LocalService;
use App\Models\OptionalService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entrepreneurship extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'entrepreneurships';

    protected $fillable = [
        'name',
        'email',
        'address',
        'phone',
        'locality',
        'department',
        'coordinates',
        'accommodation'
    ];


    /**
     *  Delete_at
     */
    protected $dates = ['deleted_at'];





    /**
     * RELACIONES
    */
    public function users() 
    {
        return $this->hasMany(User::class);
    }

    public function workdays() 
    {
        return $this->hasMany(Workday::class);
    }

    public function employees() 
    {
        return $this->hasMany(Employee::class);
    }

    public function installations() 
    {
        return $this->hasMany(Installation::class);
    }

    public function localServices()
    {
        return $this->morphedByMany(LocalService::class, 'serviceable');
    }

    public function optionalServices()
    {
        return $this->morphedByMany(OptionalService::class, 'serviceable');
    }

    public function cashflows() 
    {
        return $this->hasMany(Cashflow::class);
    }

    public function clients() 
    {
        return $this->hasMany(Client::class);
    }

}
