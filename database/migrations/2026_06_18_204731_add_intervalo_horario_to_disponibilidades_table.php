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
        Schema::table('disponibilidades', function (Blueprint $table) {
            $table->time('intervalo_inicio')
                  ->nullable()
                  ->after('inicio');

            $table->time('intervalo_fim')
                  ->nullable()
                  ->after('intervalo_inicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disponibilidades', function (Blueprint $table) {
            $table->dropColumn([
                'intervalo_inicio',
                'intervalo_fim',
            ]);
        });
    }
};
