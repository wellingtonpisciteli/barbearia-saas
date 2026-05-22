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
        'fim',
        'intervalo',
        'ativo',
    ];

    public function barbearia()
    {
        return $this->belongsTo(Barbearia::class);
    }
}
