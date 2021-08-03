<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropagationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('propagations', function (Blueprint $table) {
            $table->id();
            $table->integer('site')->default(0);
            $table->string('name');
            $table->string('location');
            $table->integer('quantity');
            $table->string('method')->nullable();
            $table->boolean('cuttings')->default(0);
            $table->longtext('notes')->nullable();
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
        Schema::dropIfExists('propagations');
    }
}
