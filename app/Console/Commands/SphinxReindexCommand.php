<?php

namespace App\Console\Commands;

use App\Jobs\ReindexIndexes;
use Illuminate\Console\Command;

class SphinxReindexCommand extends Command
{
    protected $description = 'Reindex Sphinx\'s indexes';

    protected $signature = 'sphinx:reindex {indexes? : One or more indexes separated by commas}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ReindexIndexes::dispatch($this->argument('indexes'));
    }
}
