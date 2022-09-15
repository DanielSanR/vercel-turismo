<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalService extends Model
{
    use HasFactory;

    
    protected $table = 'local_services';


    protected $fillable = [
        'name',
        'category',
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
    ];



    /**
     * RELACIONES
     */

    public function entrepreneurships()
    {
        return $this->morphToMany(Entrepreneurship::class, 'serviceable');
    }

    public function installations()
    {
        return $this->belongsToMany(Installation::class, 'localservice_installation');
    }

}
