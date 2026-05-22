<?php
namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agendamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'barbeiro_id',
        'barbearia_id',
        'nome_cliente',
        'telefone_cliente',
        'inicio',
        'fim',
    ];

    protected $casts = [
        'inicio' => 'datetime',
        'fim' => 'datetime',
    ];

    /*
    |----------------------------
    | RELAÇÃO: BARBEIRO
    |----------------------------
    */
    public function barbeiro()
    {
        return $this->belongsTo(User::class, 'barbeiro_id');
    }

    public function barbearia()
    {
        return $this->belongsTo(Barbearia::class);
    }
}
