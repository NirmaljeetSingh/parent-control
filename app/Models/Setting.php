<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'story_presentation',
        'chat_notification',
        'post_notification',
        'parent_key'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
