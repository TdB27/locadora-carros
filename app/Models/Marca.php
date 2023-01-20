<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'imagem'];

    public function rules()
    {
        return [
            'nome' => 'required|unique:marcas,nome,' . $this->id . '|min:3',
            'imagem' => 'required|file|mimes:png,jpeg',
        ];

        // unique recebe tres parametros
        // 1) nome da tabela
        // 2) nome da coluna
        // 3) id que será desconsiderado
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'O campo nome da marca já existe',
            'nome.min' => 'O campo nome deve ter no mínimo 3 caracteres',
            'imagem.mimes' => 'O arquivo deve ser uma imagem',
        ];
    }

    public function modelos()
    {
        return $this->hasMany('App\Models\Modelo');
    }
}
