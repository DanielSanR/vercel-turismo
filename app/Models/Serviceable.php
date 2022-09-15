<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serviceable extends Model
{
    use HasFactory;

    protected $table = 'serviceables';

    protected $filleable = [
        'entrepreneurship_id',
        'serviceable_type',
        'serviceable_id',
    ];


    public function serviceable() {
        return $this->morphTo();
    }

}
