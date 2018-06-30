<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSitesTable extends Migration
{
    /** @var string */
    protected $connection = 'users';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('Sites', function (Blueprint $table) {
            $table->unsignedInteger('SiteID', true);
            $table->integer('ClientID');
            $table->string('SiteName');
            $table->string('SiteKey');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('Sites');
    }
}
