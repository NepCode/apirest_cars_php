<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Validator;


use App\Http\Requests;
use App\Helpers\JwtAuth;
use App\Car;


class CarController extends Controller
{
    public function index(Request $request){

        $cars = Car::all()->load('user');
        return response()->json(array(
            'cars' => $cars,
            'status' => 'success'
        ),200);

       /* $cars = Car::all();
        return response()->json(array(
            'cars' => $cars,
            'status' => 'success'
        ),200);*/

       /* $hash = $request->header('Authorization',null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
            echo "INDEX DE CARCONTROLLER  AUTENTICADO"; die();
        }else {
            echo "INDEX DE CARCONTROLLER NO AUTENTICADO"; die();
        }*/
    }


    public function show($id){
        $car = Car::find($id)->load('user');
        return response()->json(array(
            'cars' => $car,
            'status' => 'success'
        ),200);

       /* $isset_user  = Car::where('id','=',$id)->first()->load('user');

        if(count($isset_user) == 0){
            $data = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'Car not found'
            );
        }else{
            $data = array(
                'cars' => $isset_user,
                'status' => 'success',
                'code' => 400
            );
        }
        return response()->json($data,200);*/

       /* $car = Car::find($id)->load('user');
        if($car != null){
            return response()->json(array(
                'cars' => $car,
                'status' => 'success'
            ),200);
        }else{
            return response()->json(array(
                'cars' => 'car not found',
                'status' => 'success'
            ),200);
        }*/

    }


    public function store(Request $request){
        $hash = $request->header('Authorization',null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){

            //obtener datos por post
            $json = $request->input('json',null);
            $params = json_decode($json);
            $params_array = json_decode($json,true);

            //conseguir usuario identificado
            $user = $jwtAuth->checkToken($hash,true);

            
            //validacion modo laravel
            $validate= \Validator::make($params_array, [
                'title' => 'required|min:5',
                'description' => 'required',
                'price' => 'required',
                'status' => 'required'
            ]);

            if($validate->fails()){
                return response()->json($validate->errors(),400);
            }

            /*$request->merge($params_array);

            try{
                $validate= $this->validate($request, [
                    'title' => 'required|min:5',
                    'description' => 'required',
                    'price' => 'required',
                    'status' => 'required'
                ]);
            }catch (\Illuminate\Validation\ValidationException $e){
                return $e->getResponse();
            }*/


          /*  $errors = $validate->errors();
            if($errors){
                return $errors->toJson();
            }*/


            //guardar coche
                $car = new Car();
                $car->user_id = $user->sub;
                $car->title = $params->title;
                $car->description = $params->description;
                $car->price = $params->price;
                $car->status = $params->status;

                $car->save();

                $data = array(
                    'car' => $car,
                    'status' => 'success',
                    'code' => 200,
                );

            //guaradar el coche
            //validar normal
           /* if(isset($params->title) && isset($params->description) && isset($params->price) && isset($params->status)) {
                $car = new Car();
                $car->title = $params->title;
                $car->description = $params->description;
                $car->price = $params->price;
                $car->status = $params->status;

                $car->save();
            }*/
        }else {
            //devolver error
            $data = array(
                'message' => 'login incorrecto',
                'status' => 'error',
                'code' => 300,
            );

        }

        return response()->json($data,200);

    }


    public function update($id,Request $request){
        $hash = $request->header('Authorization',null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
            //recoger parametros que llegan por post
            $json = $request->input('json',null);
            $params = json_decode($json);
            $params_array = json_decode($json,true);



            //validar datos
            $validate= \Validator::make($params_array, [
                'title' => 'required|min:5',
                'description' => 'required',
                'price' => 'required',
                'status' => 'required'
            ]);

            if($validate->fails()){
                return response()->json($validate->errors(),400);
            }


            //actualizar el registro
            $car = Car::where('id',$id)->update($params_array);

            $data = array(
                'car' => $params,
                'status' => 'success',
                'code' => 200,
            );

        }else {
            //devolver error
            $data = array(
                'message' => 'login incorrecto',
                'status' => 'error',
                'code' => 300,
            );

        }

        return response()->json($data,200);

    }

    public function destroy($id, Request $request){
        $hash = $request->header('Authorization',null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
            //comprobar que existe el registro
            $car = Car::find($id);

            //borrarlo
            $car->delete();

            //devolverlo
            $data = array(
                'car' => $car,
                'status' => 'success',
                'code' => 200,
            );

        }else {
            //devolver error
            $data = array(
                'message' => 'login incorrecto',
                'status' => 'error',
                'code' => 400,
            );

        }

        return response()->json($data,200);
    }

}//end class
