<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerticalScheduling extends Model
{
    use HasFactory;
    protected $fillable = [ 'group_id', 'source_link', 'cron_interval', 'status' ];
    
    public function group(){
        return $this->belongsTo(Sitemap::class, 'group_id', 'id');
    }
}
