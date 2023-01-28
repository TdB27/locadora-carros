<?php

namespace App\Http\Controllers\Servicos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    public function index()
    {
        return view('app.clientes');
    }
}
