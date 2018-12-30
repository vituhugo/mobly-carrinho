<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->integer('estoque');
            $table->string('slug');
            $table->text('descricao');
            $table->string('imagem');
            $table->float('preco');

            $table->softDeletes();
            $table->timestamps();
        });

        if (DB::getDefaultConnection() !== 'sqlite') {
            DB::statement('ALTER TABLE produtos ADD FULLTEXT full(nome, descricao)');
        } // Evita que o PHPUNIT trave.
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('produtos');
    }
}
