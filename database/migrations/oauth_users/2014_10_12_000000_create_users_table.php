<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
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
        $this->schema()->create('Users', function (Blueprint $table) {
            $table->unsignedInteger('UserID', true);
            $table->integer('ClientID');
            $table->string('UserLogin');
            $table->string('UserName');
            $table->string('UserEmail');
            $table->string('UserHash');
            $table->boolean('IsVerified')->default(true);
            $table->boolean('Status')->default(true);
            $table->boolean('PersonalDataAccess')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('Users');
    }
}
