<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;


class CarController extends Controller
{
    public function index(Request $request){
        echo "INDEX DE CARCONTROLLER"; die();
    }
}
