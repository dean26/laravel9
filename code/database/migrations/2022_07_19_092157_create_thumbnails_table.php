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
        Schema::create('thumbnails', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('path');
            $table->bigInteger('file_id')->unsigned()->nullable();

            $table->timestamps();
        });

        Schema::table('thumbnails', function (Blueprint $table) {
            $table->foreign('file_id')
                ->references('id')->on('files')
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
        Schema::table('thumbnails', function (Blueprint $table) {
            $table->dropForeign(['file_id']);
        });

        Schema::dropIfExists('thumbnails');
    }
};
