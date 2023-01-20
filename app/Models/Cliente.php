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
            'nome' => 'required'
        ];
    }

    public function modelo()
    {
        return $this->belongsTo('App\Models\Modelo');
    }
}
