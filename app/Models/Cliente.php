<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Agendamento;
use App\Models\Barbearia;

class Cliente extends Model
{
    protected $fillable = [
        'barbearia_id',
        'nome',
        'telefone',
        'token',
    ];

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }

    public function barbearia()
    {
        return $this->belongsTo(Barbearia::class);
    }
}
