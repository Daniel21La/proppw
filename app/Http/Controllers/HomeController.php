<?php

namespace App\Http\Controllers;

use App\Models\RentalMobil;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $mobils = RentalMobil::all();
        return view('home', compact('mobils'));
    }
}
