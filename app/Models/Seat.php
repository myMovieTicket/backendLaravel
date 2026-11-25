<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['auditorium_id', 'row_label', 'seat_number', 'seat_type'])]
class Seat extends Model {}
