<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'reported_user_id',
        'reported_by_user_id',
        'reason'
    ];
    public function reported()
    {
        return $this->belongsTo(User::class,'reported_user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'reported_by_user_id');
    }
}
