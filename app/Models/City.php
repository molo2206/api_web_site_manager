<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ['deleted', 'status','country_id','name'];
    public function country(){
        return $this->belongsTo(Countries::class, 'country_id', 'id');
    }
}
