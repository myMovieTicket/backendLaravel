<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'description', 'duration_minutes', 'genre', 'rating', 'poster_url', 'status'])]
class Movie extends Model {}
