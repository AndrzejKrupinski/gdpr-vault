<?php

namespace App\Database\Migrations;

use Illuminate\Database\Migrations\Migration as BaseMigration;
use Illuminate\Support\Facades\Schema;

class Migration extends BaseMigration
{
    /**
     * @return \Illuminate\Database\Schema\Builder
     */
    protected function schema()
    {
        return Schema::connection($this->getConnection());
    }
}
