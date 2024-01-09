<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefiX extends Model
{
    use HasFactory;

    protected $table = 'defix_gateways';
    protected $fillable = ['title', 'slug', 'external_link', 'status','parent_id'];


    // Relationship for the parent record
    public function parent()
    {
        return $this->belongsTo(DefiX::class, 'parent_id');
    }

    // Relationship for the child records
    public function children()
    {
        return $this->hasMany(DefiX::class, 'parent_id');
    }
}
