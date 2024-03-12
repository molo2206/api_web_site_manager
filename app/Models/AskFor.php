<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AskFor extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'full_name', 'nationalite','valid_in','valid_out', 'ministere', 'id_service', 'passport', 'yellow_card', 'motif', 'extrait_bank', 'type', 'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
