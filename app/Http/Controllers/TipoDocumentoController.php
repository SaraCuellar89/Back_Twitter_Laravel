<?php

namespace App\Http\Controllers;

use App\Models\Tipo_Documento;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
    public function index()
    {
        $tipo = Tipo_Documento::all();

        return response()->json([
            'message' => 'TIPOS DE DOCUMENTO',
            'data' => $tipo
        ]);
    }
}
