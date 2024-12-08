<?php

namespace App\Models\Lecture;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory;
    protected $fillable = ['nidn', 'nama', 'email'];
}
