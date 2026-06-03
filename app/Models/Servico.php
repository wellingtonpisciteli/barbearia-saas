<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;

    protected $fillable = [
        'barbearia_id',
        'nome',
        'preco',
        'duracao',
        'ativo',
    ];

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }

}
