<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code_title');
            $table->timestamps();
        });

        DB::table('states')->insert([
            [
                'name' => 'washington',
                'code_title' => 'RSW',
            ],
            [
                'name' => 'oregon',
                'code_title' => 'ORS',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('states');
    }
}
