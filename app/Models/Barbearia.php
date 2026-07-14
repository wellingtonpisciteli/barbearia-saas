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
        'endereco',
        'ativo',
        'instagram',
        'logo',
        'cidade'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function admins()
    {
        return $this->hasMany(User::class)
            ->where('role', User::ROLE_ADMIN);
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }

    public function disponibilidades()
    {
        return $this->hasMany(Disponibilidade::class);
    }

    public function getTelefoneFormatadoAttribute()
    {
        return preg_replace(
            '/(\d{2})(\d{5})(\d{4})/',
            '($1) $2-$3',
            $this->telefone
        );
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo
            ? asset('storage/' . $this->logo)
            : asset('img/barbearia/logo.png');
    }

    public function assinatura()
    {
        return $this->hasOne(Assinatura::class);
    }
}
