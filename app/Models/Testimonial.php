<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Testimonial extends Model
{
    protected $fillable = [
        'name', 'location', 'instagram', 'rating', 'content',
        'photo', 'product_id', 'is_featured', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
        'rating'      => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo) {
            return null;
        }

        return str_starts_with($this->photo, 'http')
            ? $this->photo
            : asset('storage/' . $this->photo);
    }

    public function getInstagramHandleAttribute(): ?string
    {
        if (!$this->instagram) {
            return null;
        }

        return ltrim($this->instagram, '@');
    }
}
