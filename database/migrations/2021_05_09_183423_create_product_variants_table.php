<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->references('id')->on('tremenheere_stocks')->cascadeOnDelete();
            $table->string('name');
            $table->string('img_path')->nullable();
            $table->decimal('price', 10,2);
            $table->integer('stock');
            $table->string('hardiness_zone')->nullable();
            $table->string('soil_type')->nullable();
            $table->string('light_aspect')->nullable();
            $table->integer('totalsales');
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
        Schema::dropIfExists('product_variants');
    }
}
