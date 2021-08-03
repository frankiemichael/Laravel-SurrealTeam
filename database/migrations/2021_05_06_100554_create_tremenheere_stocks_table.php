<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTremenheereStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tremenheere_stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->unsigned()->nullable()->references('name')->on('categories');
            $table->string('name');
            $table->string('slug');
            $table->longText('description')->nullable();
            $table->string('img_path')->nullable();
            $table->decimal('price', 10,2);
            $table->integer('stock')->default(0)->nullable();
            $table->string('hardiness_zone')->nullable();
            $table->string('soil_type')->nullable();
            $table->string('light_aspect')->nullable();
            $table->integer('totalsales')->default(0);
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
        Schema::dropIfExists('tremenheere_stocks');
    }
}
