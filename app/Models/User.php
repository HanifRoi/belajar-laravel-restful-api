<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable as AuthAuthenticatable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

class User extends Model implements Authenticatable
{
    use HasFactory, AuthAuthenticatable;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'username',
        'password',
        'name'
    ];

    public function contact(): HasMany
    {
        return $this->hasMany(Contact::class,'user_id', 'id');
    }

    #[Override]
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    #[Override]
    public function getAuthIdentifier()
    {
        return $this->username;
    }

    #[Override]
    public function getAuthPassword()
    {
        return $this->password;
    }

    #[Override]
    public function getRememberToken()
    {
        return $this->token;
    }

    #[Override]
    public function setRememberToken($value)
    {
        $this->token = $value;
    }

    #[Override]
    public function getRememberTokenName()
    {
        return 'token';
    }
}
