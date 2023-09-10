<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSupportMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'message',
        'time'
    ];

    public function add_log($messageInfo) {
        $map['first_name'] = $messageInfo['firstName'];
        $map['last_name'] = $messageInfo['lastName'];
        $map['email'] = $messageInfo['email'];
        $map['message'] = $messageInfo['message'];
        $map['time'] = $messageInfo['time'];

        return $this->create($map);
    }
}
