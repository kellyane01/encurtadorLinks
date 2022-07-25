<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLinksCurtos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('links_curtos')) {
            Schema::create('links_curtos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('link_id')->references('id')->on('links')->onUpdate('cascade')->onDelete('cascade');;
                $table->string('codigo');
                $table->dateTime('data_expiracao');
                $table->enum('status', ['Ativo', 'Inativo'])->default('Ativo');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('links_curtos');
    }
}
