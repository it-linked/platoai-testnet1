<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vertical extends Model
{
    use HasFactory;

    protected $fillable = [ 'title', 'content', 'feature_image', 'slug', 'seo_title', 'category_id', 'status', 'user_id', 'updated_at' ];

    public function category()
    {
        return $this->hasOne(VerticalCategory::class, 'id', 'category_id');
    }
}
