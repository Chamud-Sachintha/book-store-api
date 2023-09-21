<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'login_time',
        'logout_time',
        'time_diff'
    ];

    public function add_log($timeInfo) {
        $map['client_id'] = $timeInfo['clientId'];
        $map['login_time'] = $timeInfo['loginTime'];
        $map['logout_time'] = 0;
        $map['time_diff'] = 0;

        return $this->create($map);
    }
    
    public function update_logout_time($timeInfo) {
        $map['client_id'] = $timeInfo['clientId'];
        $map1['logout_time'] = $timeInfo['logOutTime'];
        $map1['time_diff'] = $timeInfo['timeDiff'];

        return $this->where($map)->update($map1);
    }

    public function get_time_info($clientId) {
        $map['client_id'] = $clientId;

        return $this->where($map)->first();
    }

    public function update_login_time($timeInfo) {
        $map['client_id'] = $timeInfo['clientId'];
        $map1['login_time'] = $timeInfo['loginTime'];

        return $this->where($map)->update($map1);
    }
}
