<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Story extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'image',
        'description',
        'user_id'
    ];
    protected $appends = [
        'seen'
    ];
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ($value) ? asset($value) : $value,
        );
    }
    protected function seen(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => StoryView::where([['story_id',$this->id],['user_id',auth()->user()->id]])->exists(),
        );
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
