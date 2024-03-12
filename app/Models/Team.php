<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ['full_name', 'image', 'status', 'deleted', 'email', 'facebook', 'twitter', 'instagram', 'linkedin', 'fonction'];
}
