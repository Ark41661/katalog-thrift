<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'activity_type', 'description',
        'points_earned', 'referenceable_id', 'referenceable_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function referenceable()
    {
        return $this->morphTo();
    }
}
