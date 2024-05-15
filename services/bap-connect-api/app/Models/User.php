<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Override;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    public const SYSTEM_USER_ID = '00000001-0000-7000-8000-000000000001';

    public const SYSTEM_USER_NAME = 'Administrator';

    public $timestamps = true;

    public $incrementing = false;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
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
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'user_verify_token_expiration' => 'datetime',
        'updated_at' => 'datetime',
        'birthday' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    #[Override]
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    #[Override]
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
