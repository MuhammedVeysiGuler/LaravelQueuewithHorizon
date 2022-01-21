<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    use Notifiable;
    use HasFactory, Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'userUID',
        'userFullName',
        'userFirstName',
        'userLastName',
        'userEMailAddress',
        'userLogonName',
        'userOfficeLocation',
        'userTitle',
        'userTelephoneNumber',
        'userGender',
        'userDescription',
        'userLogonNamePreWindows2000',
        'userDistinguishedName',
        'department_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    function department(){
        $this->hasOne('App\Models\Department', 'department_id', 'id');
    }

    function getGraduated(){
        return $this->hasOne('App\Models\GraduatedSocialUser','user_id','id');
    }

}
