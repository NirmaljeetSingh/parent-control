<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportStory extends Model
{
    use HasFactory;
    protected $fillable = [
        'story_id',
        'reported_by_user_id',
        'reason'
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'reported_by_user_id','id');
    }
    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}
