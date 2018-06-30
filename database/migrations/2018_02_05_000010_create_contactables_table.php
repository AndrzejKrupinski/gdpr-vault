<?php

use App\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contactables', function (Blueprint $table) {
            $table->uuid('person_id');
            $table->uuid('contactable_id');
            $table->string('contactable_type');

            $table->foreign('person_id')->references('id')->on('people');
            $table->index(['contactable_id', 'contactable_type'], 'contactable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contactables');
    }
}
