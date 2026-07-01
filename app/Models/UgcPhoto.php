<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UgcPhoto extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'submitter_name', 'submitter_instagram',
        'photo', 'caption', 'status', 'is_featured',
    ];
    protected $casts = ['is_featured' => 'boolean'];

    public function user()    { return $this->belongsTo(User::class); }
    public function product() { return $this->belongsTo(Product::class); }

    public function getPhotoUrlAttribute(): string
    {
        if (str_starts_with($this->photo, 'http')) return $this->photo;
        return Storage::url($this->photo);
    }
}
