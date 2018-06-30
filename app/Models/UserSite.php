<?php

namespace App\Models;

class UserSite extends \Illuminate\Database\Eloquent\Model
{
    public $timestamps = false;

    protected $connection = 'users';

    protected $table = 'UserSites';

    protected $primaryKey = 'SiteID';
}
