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
        Schema::create('guides', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('type');
            $table->text('data')->nullable();

            $table->bigInteger('author_id')->unsigned()->nullable();
            $table->bigInteger('job_id')->unsigned()->nullable();

            $table->timestamps();
        });

        Schema::table('guides', function (Blueprint $table) {
            $table->foreign('author_id')
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
        Schema::table('guides', function (Blueprint $table) {
            $table->dropForeign(['author_id', 'job_id']);
        });

        Schema::dropIfExists('guides');
    }
};
