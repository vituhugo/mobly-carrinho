<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->float('valor_frete');
            $table->float('valor_total');

            $table->string('ordem_nome');
            $table->string('ordem_email');
            $table->string('ordem_telefone');
            $table->string('ordem_endereco');
            $table->string('ordem_cep');


            $table->string('entrega_endereco');
            $table->string('entrega_cep');

            $table->softDeletes();
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('ordens');
    }
}
