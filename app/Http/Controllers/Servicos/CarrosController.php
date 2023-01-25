<?php

namespace App\Http\Controllers\Servicos;

use App\Http\Controllers\Controller;
use App\Models\Modelo;
use Illuminate\Http\Request;

class CarrosController extends Controller
{
    public function index()
    {
        $modelos = Modelo::all();
        return view('app.carros', [
            'modelos' => $modelos
        ]);
    }
}
