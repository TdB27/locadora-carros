<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Locacao extends Model
{
    use HasFactory;
    protected $table = 'locacoes';
    protected $fillable = [
        'cliente_id',
        'carro_id',
        'data_inicio_periodo',
        'data_final_previsto_periodo',
        'data_final_realizado_periodo',
        'valor_diaria',
        'km_inicial',
        'km_final',
    ];

    public function rules()
    {
        return [
            'cliente_id' => 'exists:clientes,id',
            'carro_id' => 'exists:carros,id',
            'data_inicio_periodo' => 'required',
            'data_final_previsto_periodo' => 'required',
            'data_final_realizado_periodo' => 'required',
            'valor_diaria' => 'required',
            'km_inicial' => 'required',
            'km_final' => 'required',
        ];
    }

    public function feedback()
    {
        return [
            'cliente_id.exists' => 'O cliente não existe',
            'carro_id.exists' => 'O carro não existe',
            'required' => 'O campo :attribute é obirgatório',
        ];
    }

    public function carro()
    {
        return $this->BelongsTo('App\Models\Carro');
    }

    public function cliente()
    {
        return $this->BelongsTo('App\Models\Cliente');
    }
}
