<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Carro;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCarroRequest;
use App\Http\Requests\UpdateCarroRequest;
use App\Repositories\CarroRepository;

class CarroController extends Controller
{

    protected $carro;

    public function __construct(Carro $carro)
    {
        $this->carro = $carro;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carroRepository = new CarroRepository($this->carro);

        if ($request->has('atributos_modelos')) {
            $atributos_modelos = 'modelo:id,' . $request->atributos_modelos;
            $carroRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        } else {
            $carroRepository->selectAtributosRegistrosRelacionados('modelo');
        }

        if ($request->has('filtro')) {
            $carroRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $carroRepository->selectAtributos($request->atributos);
        }

        return response()->json($carroRepository->getResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->carro->rules());

        $carro = $this->carro->create([
            'modelo_id' => $request->modelo_id,
            'placa' => $request->placa,
            'disponivel' => $request->disponivel,
            'km' => $request->km,
        ]);

        return response()->json($carro, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carro = $this->carro->with('modelo')->find($id);

        if ($carro === null)
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);

        return response()->json($carro, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarroRequest  $request
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $carro = $this->carro->find($id);

        if ($carro === null)
            return response()->json(['erro' => 'Impossivel realizar a atualização. O recurso pesquisado não existe!'], 404);

        if ($request->method() === 'PATCH') {

            // dd($request->all());
            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach ($carro->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra; // pegando somente os inputs e regras enviados pela requisição
                }
            }

            $request->validate($regrasDinamicas);
        } else {
            $request->validate($carro->rules());
        }

        // preencher o obj $marca com os daods do request
        $carro->fill($request->all());
        $carro->save();

        return response()->json($carro, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carro = $this->carro->find($id);

        if ($carro === null)
            return response()->json(['erro' => 'Impossivel realizar a exclusão. O recurso pesquisado não existe!'], 404);

        $carro->delete();
        return response()->json(['msg ' => 'O carro foi removido com sucesso'], 200);
    }
}
