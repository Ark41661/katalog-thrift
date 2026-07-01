<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBadge extends Model
{
    public $timestamps = false;
    protected $fillable = ['user_id', 'badge_type', 'badge_name', 'badge_icon', 'criteria'];
    const CREATED_AT = 'earned_at';
    const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
