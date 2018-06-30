<?php

use App\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsentablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consentables', function (Blueprint $table) {
            $table->uuid('consent_id');
            $table->uuid('consentable_id');
            $table->string('consentable_type');

            $table->foreign('consent_id')->references('id')->on('consents');
            $table->index(['consentable_id', 'consentable_type'], 'consentable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consentables');
    }
}
