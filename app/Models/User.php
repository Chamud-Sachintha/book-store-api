<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'status',
        'remark'
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

    public function add_user($user) {
        $userDetails['first_name'] = $user['firstName'];
        $userDetails['last_name'] = $user['lastName'];
        $userDetails['email'] = $user['email'];
        $userDetails['password'] = Hash::make($user['password']);

        return $this->create($userDetails);
    }

    public function delete_user_by_username($info) {
        $map['email'] = $info['userName'];
        $map1['status'] = 0;
        $map['remark'] = $info['remark'];

        $this->where($map)->update($map1);
    }

    public function check_email($email) {
        $map['email'] = $email;
        
        return $this->where($map)->first();
    }

    public function check_user_status($userInfo) {
        $map['email'] = $userInfo['userEmail'];
        $map['status'] = 0;

        return $this->where($map)->first();
    }
 
    public function update_login_time($authDetails) {
        $uid['id'] = $authDetails['uid'];
        $map['token'] = $authDetails['token'];
        $map['login_time'] = $authDetails['time'];

        return $this->where($uid)->update($map);
    }

    public function query_find_by_token($token) {
        $map['token'] = $token;

        return $this->where($map)->first();
    }

    public function query_log($email) {
        // $map['status'] = 1;
        $map['email'] = $email;

        return $this->where($map)->first();
    }

    public function update_user($user) {
        $map['email'] = $user['email'];

        return $this->where($map)->update($user);
    }

    public function find_by_id($clientId) {
        $map['id'] = $clientId;

        return $this->where($map)->first();
    }

    public function update_password($passInfo) {
        $map['email'] = $passInfo['emailAddress'];
        $map1['password'] = $passInfo['password'];

        return $this->where($map)->update($map1);
    }
}
