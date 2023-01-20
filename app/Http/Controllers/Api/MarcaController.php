<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    protected $marca;

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $marcaRepository = new MarcaRepository($this->marca);

        if ($request->has('atributos_modelos')) {
            $atributos_modelos = 'modelos:id,' . $request->atributos_modelos;
            $marcaRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        } else {
            $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
        }

        if ($request->has('filtro')) {
            $marcaRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $marcaRepository->selectAtributos($request->atributos);
        }

        return response()->json($marcaRepository->getResultadoPaginado(3), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());

        $img = $request->file('imagem');
        $img_urn = $img->store('imagens', 'public');

        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $img_urn
        ]);

        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);

        if ($marca === null)
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);

        return response()->json($marca, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null)
            return response()->json(['erro' => 'Impossivel realizar a atualização. O recurso pesquisado não existe!'], 404);

        if ($request->method() === 'PATCH') {

            // dd($request->all());
            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach ($marca->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra; // pegando somente os inputs e regras enviados pela requisição
                }
            }

            $request->validate($regrasDinamicas, $marca->feedback());
        } else {
            $request->validate($marca->rules(), $marca->feedback());
        }

        // preencher o obj $marca com os daods do request
        $marca->fill($request->all());

        // remove o arquivo antigo caso um novo arquivo tenha sido enviado no request
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);

            $img = $request->file('imagem');
            $img_urn = $img->store('imagens', 'public');
            $marca->imagem = $img_urn;
        }

        $marca->save();

        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null)
            return response()->json(['erro' => 'Impossivel realizar a exclusão. O recurso pesquisado não existe!'], 404);

        // remove o arquivo antigo caso um novo arquivo tenha sido enviado no request
        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();
        return response()->json(['msg' => 'A marca foi removida com sucesso'], 200);
    }
}
