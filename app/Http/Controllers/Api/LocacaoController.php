<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Locacao;
use App\Http\Requests\StoreLocacaoRequest;
use App\Http\Requests\UpdateLocacaoRequest;
use App\Repositories\LocacaoRepository;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{
    protected $locacao;

    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);

        // if ($request->has('atributos_modelos')) {
        //     $atributos_modelos = 'modelo:id,' . $request->atributos_modelos;
        //     $clienteRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        // } else {
        //     $clienteRepository->selectAtributosRegistrosRelacionados('modelo');
        // }

        if ($request->has('filtro')) {
            $locacaoRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $locacaoRepository->selectAtributos($request->atributos);
        }

        return response()->json($locacaoRepository->getResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLocacaoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($this->validateFields($request)) {
            return response()->json(['message' => 'O cliente e o carro já estão vinculados na mesma Data de Início'], 422);
        };

        $request->validate($this->locacao->rules(), $this->locacao->feedback());

        $locacao = $this->locacao->create([
            'cliente_id' => $request->cliente_id,
            'carro_id' => $request->carro_id,
            'data_inicio_periodo' => $request->data_inicio_periodo,
            'data_final_previsto_periodo' => $request->data_final_previsto_periodo,
            'data_final_realizado_periodo' => $request->data_final_realizado_periodo,
            'valor_diaria' => $request->valor_diaria,
            'km_inicial' => $request->km_inicial,
            'km_final' => $request->km_final,
        ]);

        return response()->json($locacao, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locacao = $this->locacao->find($id);

        if ($locacao === null)
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);

        return response()->json($locacao, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLocacaoRequest  $request
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $locacao = $this->locacao->find($id);

        if ($locacao === null)
            return response()->json(['erro' => 'Impossivel realizar a atualização. O recurso pesquisado não existe!'], 404);

        if ($request->method() === 'PATCH') {

            // dd($request->all());
            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach ($locacao->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra; // pegando somente os inputs e regras enviados pela requisição
                }
            }

            $request->validate($regrasDinamicas, $this->locacao->feedback());
        } else {
            $request->validate($locacao->rules(), $this->locacao->feedback());
        }

        // preencher o obj $marca com os daods do request
        $locacao->fill($request->all());
        $locacao->save();

        return response()->json($locacao, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);

        if ($locacao === null)
            return response()->json(['erro' => 'Impossivel realizar a exclusão. O recurso pesquisado não existe!'], 404);

        $locacao->delete();
        return response()->json(['msg ' => 'A locação foi removida com sucesso'], 200);
    }

    private function validateFields($request)
    {
        $locacoes = Locacao::get();

        foreach ($locacoes as $l) {
            $date = explode(' ', $l->data_inicio_periodo);

            if (($l->cliente_id == $request->cliente_id) &&
                ($l->carro_id == $request->carro_id) &&
                ($date[0] == $request->data_inicio_periodo)
            ) return true;
        }
        return false;
    }
}
