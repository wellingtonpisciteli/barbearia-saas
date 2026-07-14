<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assinatura extends Model
{
    protected $fillable = [
        'barbearia_id',
        'valor',
        'status',
        'inicio',
        'proxima_cobranca',
        'fim',
        'gateway',
        'gateway_id',
    ];

    protected $casts = [
        'inicio' => 'date',
        'proxima_cobranca' => 'date',
        'fim' => 'date',
    ];

    public function barbearia()
    {
        return $this->belongsTo(Barbearia::class);
    }
}
