<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\JwtAuth;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\User;

class UserController extends Controller
{
    public function register(Request $request){
        //echo "ACCION REGISTRO"; die();
        //recoger post
        $json = $request->input('json',null);
        $params = json_decode($json);

        $email      = (!is_null($json) && isset($params->email)) ? $params->email : null;
        $name       = (!is_null($json) && isset($params->name)) ? $params->name : null;
        $surname    = (!is_null($json) && isset($params->surname)) ? $params->surname : null;
        $role       = 'ROLE_USER';
        $password   = (!is_null($json) && isset($params->password)) ? $params->password : null;

        if(!is_null($email)){
            //crea usuario
            $user = new User();
            $user ->email = $email;
            //$user ->password = $password;
            $user ->name = $name;
            $user ->surname = $surname;
            $user ->role = $role;

            $pwd = hash('sha256',$password);
            $user->password = $pwd;

            //comprobar usuario duplicado
            $isset_user = User::where('email','=',$email)->first();

            if(count($isset_user) == 0){
                //guardar usuario
                $user->save();
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Usuario creado'
                );
            }else{
                //no guardar porq ya existe
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Usuario ya existe'
                );
            }

        }else{
            $data = array(
              'status' => 'error',
                'code' => 400,
                'message' => 'Usuario no se ha podido crear'
            );
        }

        return response()->json($data,200);
    }

    public function login(Request $request){
        //echo "ACCION LOGIN"; die();
        $jwtAuth = new JwtAuth();

        //recibir fatos por post
        $json = $request->input('json', null);
        $params = json_decode($json);

        $email = (!is_null($json) && isset($params->email)) ? $params->email : null;
        $password = (!is_null($json) && isset($params->password)) ? $params->password : null;
        //$gettoken = (!is_null($json) && isset($params->gettoken) && $params->gettoken == true ) ? $params->gettoken : null;
        $gettoken = (!is_null($json) && isset($params->gettoken)) ? $params->gettoken : null;

        //cifrar password
        $pwd = hash('sha256',$password);

        if(!is_null($email) && !is_null($password) && ($gettoken == null || $gettoken == 'false') ){
            $signup = $jwtAuth->signup($email,$pwd);//añadiendo true devuelve el objeto decodificado

            //return response()->json($signup,200);
        }elseif ($gettoken != null){
            //var_dump($gettoken); die();
            $signup = $jwtAuth->signup($email,$pwd,$gettoken);

            //return response()->json($signup,200);

        }else{
            $signup = array(
                'status' => 'error',
                'message' => 'envia tus datos por post'
            );
        }

        return response()->json($signup,200);

    }
}
