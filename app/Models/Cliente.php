<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $fillable = ['nome'];

    public function rules()
    {
        return [
            'nome' => 'required|min:3'
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'min' => 'O campo :attribute precisa no mínimo de 3 caracteres'
        ];
    }

    public function modelo()
    {
        return $this->belongsTo('App\Models\Modelo');
    }
}
