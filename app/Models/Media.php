<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ["url",'cover','category_id','type','country_id','city_id'];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function country(){
        return $this->belongsTo(Countries::class, 'country_id', 'id');
    }
    public function city(){
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
