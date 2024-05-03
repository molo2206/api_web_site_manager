<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Communiques extends Model
{
    use HasFactory, HasUuids, Translatable;
    protected $fillable = ["created", "author","file"];
    public $translatedAttributes = ["title"];

    public function author()
    {
        return $this->belongsTo(Team::class, 'author', 'id');
    }
}