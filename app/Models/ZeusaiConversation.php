<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZeusaiConversation extends Model
{
    use HasFactory;
    protected $primaryKey = 'id'; // Specify the primary key
    public $incrementing = false; // Ensure it does not increment (since it's not an auto-incrementing integer)
    protected $keyType = 'string'; 
    protected $fillable = ['title', 'user_id','id','title'];

    public function interactions()
    {
        return $this->hasMany(ZeusaiInteraction::class, 'conversation_id', 'id');
    }
}
