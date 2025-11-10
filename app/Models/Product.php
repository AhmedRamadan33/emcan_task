<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\ImageTrait;

class Product extends Model
{
    use HasFactory, ImageTrait;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'slug',
        'description',
        'image_path',
        'price',
        'quantity',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Get the category that owns the product
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Override getDefaultImageUrl 
     */
    protected function getDefaultImageUrl()
    {
        return asset('images/default-product.png');
    }

    /**
     * Override getImageFileName 
     */
    protected function getImageFileName($image)
    {
        $extension = $image->getClientOriginalExtension();
        return 'product.' . $extension;
    }

    /**
     * Scope active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope search by product name
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%');
    }
}
