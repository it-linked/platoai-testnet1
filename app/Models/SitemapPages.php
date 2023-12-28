<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SitemapPages extends Model
{
    use HasFactory;
    protected $table = "sitemap_pages";
    protected $fillable = [ 'sitemap_id', 'url', 'frequency', 'priority' ];

    public function sitemap()
    {
        return $this->belongsTo(Sitemap::class, 'sitemap_id', 'id'); // Assuming sitemap_id is the foreign key in SitemapPages
    }
    
}
