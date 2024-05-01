<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Category extends Model implements TranslatableContract
{
    use HasFactory, HasUuids, Translatable;
    protected $fillable = ['deleted','status','types'];

    public $translatedAttributes = ["name"];

    public function events(){
        return $this->hasMany(Event::class, 'category_id', 'id');
    }

    public function blogs(){
        return $this->hasMany(Blogs::class, 'category_id', 'id');
    }


}
