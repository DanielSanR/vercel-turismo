<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailable extends Model
{
    use HasFactory;

    protected $table = 'detailables';

    protected $filleable = [
        'detailable_type',
        'detailable_id',
        'booking_id',
        'price_unit',
        'quantity'
    ];


    public function detailable() {
        return $this->morphTo();
    }


}
