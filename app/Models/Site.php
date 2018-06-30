<?php

namespace App\Models;

class Site extends \Illuminate\Database\Eloquent\Model
{
    public $timestamps = false;

    protected $connection = 'users';

    protected $primaryKey = 'SiteID';

    protected $table = 'Sites';
}
