<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Symfony\Component\Process\Process;

class ReindexIndexes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /** @var array  */
    protected $config;

    /** @var array */
    protected $indexes;

    /** @var \Symfony\Component\Process\Process */
    protected $process;

    /**
     * Create a new job instance.
     *
     * @param  array|string  $indexes
     * @param  array  $config
     */
    public function __construct($indexes = [], $config = [])
    {
        $this->config = $config ?: config('sphinxsearch');
        $this->indexes = is_string($indexes) ? explode(',', $indexes) : $indexes;
        $this->process = new Process($this->getCommand());
    }

    /**
     * Return the command line to run.
     *
     * @return string
     */
    public function getCommand()
    {
        $indexes = $this->usingSpecificIndexes()
            ? implode(' ', $this->indexes)
            : '--all';

        return vsprintf('%s --config %s --rotate %s', [
            $this->config['indexer_path'],
            $this->config['config_path'],
            $indexes,
        ]);
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        logger('[sphinx] Reindexing data...', ['command' => $this->getCommand()]);

        $this->process->run();

        logger('[sphinx] Reindexing finished!', ['results' => $this->process->getOutput()]);
    }

    /**
     * Check if specific indexes should be used.
     *
     * @return bool
     */
    protected function usingSpecificIndexes()
    {
        return !empty($this->indexes);
    }
}
