<?php

namespace App\Http\Controllers\Servicos;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;

class ModelosController extends Controller
{
    public function index()
    {
        $marcas = Marca::all();

        return view('app.modelos', [
            'marcas' => $marcas
        ]);
    }
}
