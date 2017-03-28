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
            $table->string('secondname');
            $table->string('firstname');
            $table->string('middlename');
            $table->string('nickname');
            $table->date('birthday');
            $table->text('address');
            $table->text('work');
            $table->string('passport');
            $table->string('code');
            $table->text('other');
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
