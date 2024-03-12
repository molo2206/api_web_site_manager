<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livres extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ['title', 'description', 'image', 'file', 'price','deleted','status'];

}
