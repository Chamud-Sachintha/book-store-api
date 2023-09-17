<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForgotPassword extends Model
{
    use HasFactory;

    protected $fillable = [
        'auth_code',
        'email',
        'time'
    ];

    public function add_log($codeInfo) {
        $map['auth_code'] = $codeInfo['authCode'];
        $map['email'] = $codeInfo['emailAddress'];
        $map['time'] = $codeInfo['time'];

        return $this->create($map);
    }

    public function update_log($codeInfo) {
        $map['email'] = $codeInfo['emailAddress'];

        return $this->where($map)->update($codeInfo);
    }

    public function query_find($email) {
        $map['email'] = $email;

        return $this->where($map)->first();
    }

    public function remove_log($email) {
        $map['email'] = $email;

        return $this->where($map)->delete();
    }

    public function get_log_by_code($code) {
        $map['auth_code'] = $code;

        return $this->where($map)->first();
    }
}
