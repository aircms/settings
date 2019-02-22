<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('alias')->nullable(false);
            $table->string('name')->nullable(false);
            $table->string('hint')->nullable(false);
            $table->string('type')->nullable(false);
            $table->text('pre_setting')->nullable(false);
            $table->integer('order')->default(0);
            $table->text('value')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
