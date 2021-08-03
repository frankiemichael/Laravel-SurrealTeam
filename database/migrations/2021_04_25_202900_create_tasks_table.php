<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->integer('creator_id')->references('id')->on('users');
            $table->string('name');
            $table->string('description');
            $table->string('priority');
            $table->datetime('deadline')->nullable();
            $table->datetime('weekly')->nullable();
            $table->time('daily')->nullable();
            $table->string('occurrence');
            $table->string('img_path')->nullable();
            $table->string('site')->nullable();
            $table->integer('completed')->default(false);
            $table->integer('pending')->default(1);
            $table->string('completedby')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('tasks');
    }
}
