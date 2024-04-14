<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getByUsername($username)
    {
        return self::where('username', $username)->first();
    }
    public static function UpdateToken($username, $token)
    {
        return self::where('username', $username)->update(['remember_token' => $token]);
    }

    public static function checkUsername($username)
    {
        return self::where('username', $username)->exists();
    }

    public static function createUser($name, $username, $password)
    {
        $create = new User();
        $create->name = $name;
        $create->username = $username;
        $create->password = $password;
        return $create->save();
    }

    public static function getUser()
    {
        return self::all();
    }

    public static function updateUser($id, $username, $name, $password)
    {
        return self::where('id', $id)->update([
            'username' => $username,
            'name' => $name,
            'password' => $password
        ]);
    }
    public static function checkValidById($id)
    {
        return self::where('id', $id)->exists();
    }
    public static function getUserById($id)
    {
        return self::where('id', $id)->first();
    }
    public static function deleteUser($id)
    {
        return self::where('id', $id)->delete();
    }

    public static function get_user_by_id($id)
    {
        return self::where('id', $id)->first();
    }
}
