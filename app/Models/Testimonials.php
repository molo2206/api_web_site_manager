<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Testimonials extends Model implements TranslatableContract
{
    use HasFactory, HasUuids, Translatable;
    protected $fillable=["name","image","status","deleted"];
    public $translatedAttributes = ["message", "fonction"];
}
