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

    public function add_log() {

    }

    public function query_find($clientId) {
        $map['user_id'] = $clientId;

        return $this->where($map)->first();
    }
}
