<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'address',
        'gender',
        'username',
        'email',
        'status',
        'verify_user_token',
        'user_verify_token_expiration',
        'remember_token',
        'deleted_at',
        'email_verified_at',
        'password',
        'avatar',
        'province',
        'district',
        'ward',
        'birthday_day',
        'birthday_month',
        'birthday_year',
        'phone',
        'created_by',
        'updated_by',
        'creator_name',
        'updater_name',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
    ];
}
