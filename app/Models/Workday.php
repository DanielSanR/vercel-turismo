<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workday extends Model
{
    use HasFactory;


    protected $table = 'workdays';

    protected $fillable = [
        'day',
        'opening',
        'closing',
        'time_interval',
        'entrepreneurship_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * RELACIONES
    */

    public function entrepreneurship() {
        return $this->belongsTo(Entrepreneurship::class);
    }


}
