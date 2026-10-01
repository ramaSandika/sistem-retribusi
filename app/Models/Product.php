<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'price',
        'description',
        'size',
        'condition',
        'brand',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true)->withDefault([
            'image_path' => 'placeholder.jpg'
        ]);
    }

    public function getPrimaryImageUrlAttribute()
    {
        if ($this->primaryImage && $this->primaryImage->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->primaryImage->image_path)) {
            return asset('storage/' . $this->primaryImage->image_path);
        }

        $firstImage = $this->images->first();
        if ($firstImage && $firstImage->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($firstImage->image_path)) {
            return asset('storage/' . $firstImage->image_path);
        }

        return asset('images/no-image.svg');
    }
}
