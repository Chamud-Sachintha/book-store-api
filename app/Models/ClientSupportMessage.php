<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSupportMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'email',
        'message',
        'time'
    ];

    public function add_log($messageInfo) {
        $map['title'] = $messageInfo['title'];
        $map['email'] = $messageInfo['email'];
        $map['message'] = $messageInfo['message'];
        $map['time'] = $messageInfo['time'];

        return $this->create($map);
    }
}
