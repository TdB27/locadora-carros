<?php

namespace App\Http\Controllers\Servicos;

use App\Http\Controllers\Controller;
use App\Models\Carro;
use App\Models\Cliente;
use Illuminate\Http\Request;

class LocacoesController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        $carros = Carro::with('modelo')->get();

        return view('app.locacoes', [
            'clientes' => $clientes,
            'carros' => $carros,
        ]);
    }
}
