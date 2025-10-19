<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    // FUNCION PARA LISTAR TODOS LOS POSTS
    public function index()
    {
        $post = Post::all();

        return response()->json([
            'message' => 'LISTA DE POSTS',
            'data' => $post
        ]);
    }

    // FUNCION PARA CREAR UN POST
    public function store(Request $request)
    {

        // VALIDACION ANTES DE CREAR EL POST
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        // EN CASO DE QUE FALLE ALGO EN LA VALIDACION, MOSTRARÁ DONDE SE EQUIVOCÓ EL USUARIO
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        // SI TODO ES CORECTO SE AGREGA EL POST Y SE GUARDA EN LA BASE DE DATOS
        $post = Post::create([
            // RECUPERAMOS LOS VALORES QUE VENGAN DEL FORMULARIO
            'user_id' => $request->user_id,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // FINALMENTE MOSTRAMOS EL POST RECIEN CREADO
        return response()->json([
            'message' => 'SE CREÓ EL POST EXITOSAMENTE',
            'data' => $post
        ]);
    }

    // FUNCION PARA BUSCAR UN POST POR ID
    public function show(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'NO SE ENCONTRÓ NINGÚN POST']);
        }

        return response()->json([
            'message' => 'POST ENCONTRADO',
            'data' => $post
        ]);
    }

    // FUNCION PARA BUSCAR UN POST POR TITULO
    public function search(Request $request)
    {

        // PREPARAMOS UN QUERY PERO SIN EJECUTARSE AUN
        $query = Post::query();

        // VERIFICAMOS QUE EL INPUT SEARCH TRAIGA CONSIGO UN TERMINO DE BUSQUEDA
        if ($request->has('search')) {
            // RECUPERAMOS EL VALOR DEL INPUT
            $search = $request->input('search');
            // HACEMOS EL FILTRO CON EL COMODIN "%" Y  LIKE PARA LAS COINCIDENCIAS
            $query->where('title', 'LIKE', '%' . $search . '%');
        }

        // EJECUTAMOS LA CONSULTA
        $post = $query->get();

        // EN CASO DE NO HABER UNA COINCIDENCIA
        if ($post->isEmpty()) {
            return response()->json([
                'message' => 'NO SE ENCONTRARON PUBLICACIONES'
            ], 404);
        }
        // MOSTRAR POSTS
        return response()->json([
            'message' => 'PUBLICACIONES ENCONTRADAS',
            'data' => $post
        ]);
    }

    // FUNCION PARA EDITAR POST
    public function update(Request $request, string $id)
    {

        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'NO SE ENCONTRÓ NINGÚN POST']);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'string',
            'description' => 'string',
        ]);

        // EN CASO DE QUE FALLE ALGO EN LA VALIDACION, MOSTRARÁ DONDE SE EQUIVOCÓ EL USUARIO
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        // SI NO DECIDE CAMBIAR TODOS LOS CAMPOS MANTENDRÁ LOS QUE YA TIENE
        if ($request->has('title')) {
            $post->title = $request->title;
        }
        if ($request->has('description')) {
            $post->description = $request->description;
        }

        $post->save();

        return response()->json([
            'message' => 'CAMBIO EXITOSO',
            'data' => $post
        ]);
    }

    public function destroy(Request $request, string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'NO SE ENCONTRÓ NINGÚN POST']);
        }

        $post->delete();

        return response()->json([
            'message' => 'POST ELIMINADO'
        ]);
    }

    
}
