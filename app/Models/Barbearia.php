<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barbearia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'slug',
        'telefone',
        'endereco'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }

    public function disponibilidades()
    {
        return $this->hasMany(Disponibilidade::class);
    }
}
