<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;
    protected $fillable = ['marca_id', 'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'];

    public function rules()
    {
        return [
            'marca_id' => 'exists:marcas,id',
            'nome' => 'required|unique:modelos,nome,' . $this->id . '|min:3',
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean',
        ];

        // unique recebe tres parametros
        // 1) nome da tabela
        // 2) nome da coluna
        // 3) id que será desconsiderado
    }

    public function feedback()
    {
        return [
            'marca_id.exists' => 'A marca do carro não existe',
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'O campo nome da marca já existe',
            'nome.min' => 'O campo nome deve ter no mínimo 3 caracteres',
            'imagem.mimes' => 'O arquivo deve ser uma imagem',
            'integer' => 'O campo :attribute precisar ser um número',
            'numero_portas.digits_between' => 'O campo numero portas deve ter entre 1 e 4 portas',
            'lugares.digits_between' => 'O campo lugares deve ter entre 1 e 20 lugares',
            'boolean' => 'O campo :attribute não reconheceu a requisição',
        ];
    }

    public function marca()
    {
        return  $this->belongsTo('App\Models\Marca');
    }
}
