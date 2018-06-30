<?php

use App\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consents', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('person_id');
            $table->uuid('content_id');
            $table->boolean('confirmed')->default(false)->comment('Whether the consent has been confirmed');
            $table->text('meta')->nullable();
            $table->timestamps();
            $table->date('expired_at')->nullable()->comment('How long person may be processed within consent');
            $table->softDeletes();

            $table->primary('id');
            $table->foreign('person_id')->references('id')->on('people');
            $table->foreign('content_id')->references('id')->on('contents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consents');
    }
}
