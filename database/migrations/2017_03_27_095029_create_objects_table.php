<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('secondname')->nullable();
            $table->string('firstname')->nullable();
            $table->string('middlename')->nullable();
            $table->string('nickname')->nullable();
            $table->date('birthday')->nullable();
            $table->text('address')->nullable();
            $table->text('work')->nullable();
            $table->string('passport')->nullable();
            $table->string('code')->nullable();
            $table->text('other')->nullable();
            $table->string('source');
            $table->timestamps();
            $table->text('forsearch');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('objects');
    }
}
