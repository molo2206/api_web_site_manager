<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Settings extends Model implements TranslatableContract
{
    use HasFactory, Translatable;
    protected $fillable = [
        'app_name',
        'emails',
        'phones',
        'adresses',
        'logo1',
        'logo2',
        "stripe",
        'social_links',
        'image1',
        'image2',
        'image3',
        'image4',
        'image5'
    ];
    public $translatedAttributes = ["about_us", "mission", "vision", "history", "values"];
}
