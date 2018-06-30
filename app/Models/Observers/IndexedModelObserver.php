<?php

namespace App\Models\Observers;

use App\Jobs\ReindexIndexes;
use App\Models\Model;

class IndexedModelObserver
{
    /**
     * Return name of the model's index.
     *
     * @param  \App\Models\Model  $model
     * @return string
     */
    protected static function getIndexName(Model $model)
    {
        return str_finish($model->getTable(), '_index');
    }

    /**
     * Reindex indexes based on the saved model.
     *
     * @param  \App\Models\Model  $model
     */
    public function saved(Model $model)
    {
        ReindexIndexes::dispatch(self::getIndexName($model));
    }
}
