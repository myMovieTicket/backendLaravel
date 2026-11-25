<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class CinemaController extends Controller
{
    public function index(): View
    {
        return view('welcome');
    }
}
