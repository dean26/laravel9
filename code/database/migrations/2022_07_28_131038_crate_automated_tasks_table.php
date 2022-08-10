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
        Schema::create('automated_tasks', function (Blueprint $table) {
            $table->id();

            $table->string('content');
            $table->tinyInteger('days');
            $table->bigInteger('item_id')->unsigned()->nullable();

            $table->timestamps();
        });

        Schema::table('automated_tasks', function (Blueprint $table) {
            $table->foreign('item_id')
                ->references('id')->on('items')
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
        Schema::table('automated_tasks', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::dropIfExists('automated_tasks');
    }
};
