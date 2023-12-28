<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerticalCategory extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'slug', 'icon'];

    public function vertical()
    {
        return $this->belongsTo( Vertical::class, 'id', 'category_id' );
    }
}
