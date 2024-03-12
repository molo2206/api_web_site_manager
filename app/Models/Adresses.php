<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adresses extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ['country_id', 'adresse', 'emails', 'phones', 'city'];
    public function country(){
        return $this->belongsTo(Countries::class, 'country_id', 'id');
    }
}
