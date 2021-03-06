<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChamadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chamados', function (Blueprint $table) {
            $table->bigIncrements('id');

            /* Campos obrigatórios*/

            // por enquanto está nullable mas depois de implementar a lógica
            // de autoincrement por ano aí pode ser obrigatório
            $table->integer('nro')->nullable();
            $table->string('assunto', 255);
            $table->enum('status', ['Triagem', 'Atribuído','Fechado']);

            /* Campos opcionais do chamado */
            $table->text('descricao')->nullable();
            $table->dateTime('fechado_em')->nullable();
            $table->enum('complexidade', ['baixa', 'média','alta'])->nullable();
            $table->json('extras')->nullable();

            # este campo de anotações deverá ficar visível somente para atendentes.
            $table->text('anotacoes')->nullable();

            /* Relacionamentos */
            $table->foreignId('fila_id')->constrained('filas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chamados');
    }
}
