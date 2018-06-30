<?php

use App\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_details', function (Blueprint $table) {
            $table->uuid('id');
            $table->increments('id_search');
            $table->uuid('person_id');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('maiden_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->unsignedTinyInteger('sex')->default(0)->comment('https://en.wikipedia.org/wiki/ISO/IEC_5218');
            $table->unsignedTinyInteger('marital_status')->default(0);
            $table->timestamps();

            $table->index('id');
            $table->foreign('person_id')->references('id')->on('people');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_details');
    }
}
