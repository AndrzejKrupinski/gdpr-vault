<?php

namespace App\Models;

trait HasTags
{
    use \Spatie\Tags\HasTags;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getTagsAttribute()
    {
        return $this->tags()
            ->get()
            ->map(function ($tag) {
                return $tag->getTranslations('name', app()->getLocale());
            })
            ->flatten();
    }
}
