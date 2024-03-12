<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Event extends Model implements TranslatableContract
{
    use HasFactory, HasUuids, Translatable;
    protected $fillable = ['deleted', 'status', 'category_id',"country_id","city_id","in","out", "image", "debut", "fin"];
    public $translatedAttributes = ["title", "description"];

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
