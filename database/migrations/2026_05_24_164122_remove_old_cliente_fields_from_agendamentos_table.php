<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::table('agendamentos', function (Blueprint $table) {

            $table->dropColumn([
                'nome_cliente',
                'telefone_cliente'
            ]);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->string('nome_cliente')->nullable();
            $table->string('telefone_cliente')->nullable();
        });
    }
};
