<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserSitesTable extends Migration
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
        $this->schema()->create('UserSites', function (Blueprint $table) {
            $table->unsignedInteger('UserSiteID', true);
            $table->integer('UserID');
            $table->string('SiteID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('UserSites');
    }
}
