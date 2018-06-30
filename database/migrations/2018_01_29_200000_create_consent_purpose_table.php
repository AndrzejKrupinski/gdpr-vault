<?php

use App\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsentPurposeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consent_purpose', function (Blueprint $table) {
            $table->uuid('consent_id');
            $table->uuid('purpose_id');

            $table->foreign('consent_id')->references('id')->on('consents')->onDelete('cascade');;
            $table->foreign('purpose_id')->references('id')->on('purposes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consent_purpose');
    }
}
