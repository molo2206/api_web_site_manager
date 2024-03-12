<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ['name', 'code', 'status', 'deleted'];

    public function city(){
        return $this->hasMany(City::class, 'country_id', 'id')->where('deleted', 0)->where('status',1);
    }
}
