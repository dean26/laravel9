<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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

            $table->text('content');
            $table->date('deadline')->nullable();
            $table->string('status')->nullable();

            $table->bigInteger('author_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('job_id')->unsigned()->nullable();

            $table->timestamps();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->foreign('author_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('job_id')
                ->references('id')->on('jobs')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'author_id', 'job_id']);
        });

        Schema::dropIfExists('tasks');
    }
};
