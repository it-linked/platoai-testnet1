<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZeusaiInteraction extends Model
{
    use HasFactory;
    protected $fillable = ['conversation_id', 'user_message', 'chatgpt_response'];

    public function conversation()
    {
        return $this->belongsTo(ZeusaiConversation::class);
    }
}
