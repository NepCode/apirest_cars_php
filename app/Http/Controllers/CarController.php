<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Helpers\JwtAuth;
use App\Car;


class CarController extends Controller
{
    public function index(Request $request){
        $hash = $request->header('Authorization',null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
            echo "INDEX DE CARCONTROLLER  AUTENTICADO"; die();
        }else {
            echo "INDEX DE CARCONTROLLER NO AUTENTICADO"; die();
        }
    }


    public function store(Request $request){
        $hash = $request->header('Authorization',null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){

            //obtener datos por post
            $json = $request->input('json',null);
            $params = json_decode($json);

            //conseguir usuario identificado
            $user = $jwtAuth->checkToken($hash,true);

            //guaradar el coche

            if(isset($params->title) && isset($params->description) && isset($params->price) && isset($params->status)) {
                $car = new Car();
                $car->title = $params->title;
                $car->description = $params->description;
                $car->price = $params->price;
                $car->status = $params->status;

                $car->save();
            }
        }else {
            //devolver error
        }
    }


}
