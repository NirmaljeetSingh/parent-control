<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $with = ['user'];
    protected $hidden = ['status'];
    protected $fillable = ['user_id',
    'sender_id',
    'reference_id',
    'notification_type',
    'status'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // public function story()
    // {
    //     return $this->belongsTo(Story::class);
    // }

}
