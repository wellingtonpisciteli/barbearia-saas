<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Disponibilidade extends Model
{
    use HasFactory;

    protected $fillable = [
        'barbeiro_id',
        'dia_semana',
        'inicio',
        'intervalo_inicio',
        'intervalo_fim',
        'fim',
        'ativo',
        'barbearia_id'
    ];

    public function barbearia()
    {
        return $this->belongsTo(Barbearia::class);
    }
}
