<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'age',
        'sex',
        'nic_number',
        'mobile_number',
        'school_name',
        'grade',
        'district',
        'time'
    ];

    public function add_log($profileInfo) {
        $map['user_id'] = $profileInfo['userId'];
        $map['age'] = $profileInfo['age'];
        $map['sex'] = $profileInfo['sex'];
        $map['nic_number'] = $profileInfo['nicNumber'];
        $map['mobile_number'] = $profileInfo['mobileNumber'];
        $map['school_name'] = $profileInfo['schoolName'];
        $map['grade'] = $profileInfo['grade'];
        $map['district'] = $profileInfo['district'];
        $map['time'] = $profileInfo['time'];

        return $this->create($map);

    }

    public function update_log($profileInfo) {
        $map['nic_number'] = $profileInfo['nicNumber'];
        $map['school_name'] = $profileInfo['schoolName'];
        $map['age'] = $profileInfo['age'];
        $map['sex'] = $profileInfo['sex'];
        $map['mobile_number'] = $profileInfo['mobileNumber'];
        $map['grade'] = (int)$profileInfo['grade'];
        $map['district'] = $profileInfo['district'];
        $map['time'] = $profileInfo['time'];
        $map1['user_id'] = $profileInfo['userId'];

        // dd($map);

        return $this->where($map1)->update($map);
    }

    public function query_find($clientId) {
        $map['user_id'] = $clientId;
        
        return $this->where($map)->first();
    }
}
