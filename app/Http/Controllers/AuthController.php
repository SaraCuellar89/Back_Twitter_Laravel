<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // FUNCION PARA REGISTRAR USUARIOS
    public function register(Request $request)
    {
        // VALIDACIONES PARA CADA UNO DE LOS CAMPOS
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|min:3',
                'age' => 'required|numeric|min:16',
                'tipo_documento' => 'required|exists:tipo_documentos,id',
                'documento' => 'required|min:7|max:11',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6'
            ],
            // MENSAJES EN CASO DE NO CUMPLIR CON EL FORMATO
            [
                'documento.min' => 'DOCUMENTO INVALIDO, CARACTERES INSUFICIENTES',
                'documento.max' => 'DOCUMENTO INVALIDO, CARACTERES DEMÁS',
                'email.unique' => 'ESTA DIRECCION EMAIL YA ESTÁ SIENDO USADA POR OTRO USUARIO',
                'age.min' => 'DEBES SER MAYOR DE 15 AÑOS PARA REGISTRARTE',
                'email.email' => 'EMAIL INVALIDO',
                'password.min' => 'LA CONTRASEÑA DEBE CONTENER AL MENOS 6 CARACTERES'
            ]
        );

        // MENSAJES EN CASO DE QUE LA VALIDACION HAYA FALLADO
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        // CREACION DEL USUARIO
        $user = User::create([
            'name' => $request->name,
            'age' => $request->age,
            'tipo_documento' => $request->tipo_documento,
            'documento' => $request->documento,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        \Log::info('Usuario creado correctamente', ['user' => $user]);

        // RESPUESTA AL SERVIDOR
        return response()->json([
            'message' => 'USUARIO AGREGADO CON EXITO',
            'data' => $user
        ]);
    }

    // FUNCION PARA INICIAR SESSION
    public function login(Request $request)
    {
        // VALIDACIONES PARA CADA UNO DE LOS CAMPOS
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:6'
            ],
            // MENSAJES EN CASO DE NO CUMPLIR CON EL FORMATO
            [
                'email.unique' => 'ESTA DIRECCION EMAIL YA ESTÁ SIENDO USADA POR OTRO USUARIO',
                'email.email' => 'EMAIL INVALIDO',
                'password.min' => 'LA CONTRASEÑA DEBE CONTENER AL MENOS 6 CARACTERES'
            ]
        );

        // MENSAJES EN CASO DE QUE LA VALIDACION HAYA FALLADO
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        // VERIFICAMOS QUE LAS CREDENCIALES SON CORRECTAS
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'CREDENCIALES INCORRECTAS'
            ]);
        }

        // RECUPERAMOS EL USUARIO AUTENTICADO
        $user = Auth::user();

        // GENERAMOS EL TOKEN PARA EL USUARIO LOGUEADO
        $token = $user->createToken('auth:token')->plainTextToken;

        return response()->json([
            'message' => "BIENVENID@ {$user->name}",
            'type_token' => 'Bearer',
            'token' => $token
        ]);
    }

    // FUNCION PARA CERRAR SESSION
    public function logout(Request $request)
    {
        // RECUPERAMOS EL USUARIO LOGUEADO PARA PODER BORRAR EL TOKEN QUE LE DA EL ACCESO A LAS DISTINTAS PANTALLAS
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'SESSION CERRADA CON EXITO']);
    }
}
