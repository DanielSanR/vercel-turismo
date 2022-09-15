<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory; 
    use Notifiable; 
    use HasRoles;
    use HasApiTokens;
    use SoftDeletes;


    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'image_path',
        'password',
        'entrepreneurship_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    
    protected $dates = ['deleted_at'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * RELACIONES
     */
    public function entrepreneurship() 
    {
        return $this->belongsTo(Entrepreneurship::class);
    } 

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

}
