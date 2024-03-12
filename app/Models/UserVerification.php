<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserVerification extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;
    protected $fillable = ['email', 'status'];
}
