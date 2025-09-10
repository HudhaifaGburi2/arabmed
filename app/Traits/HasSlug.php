<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $source = $model->title_en ?? $model->title_ar ?? $model->name_en ?? $model->name_ar ?? Str::random(8);
                $slug = Str::slug(Str::limit($source, 100, ''));
                // very basic uniqueness handling; can be enhanced per model context
                $original = $slug;
                $i = 1;
                while ($model->newQuery()->where('slug', $slug)->exists()) {
                    $slug = $original.'-'.(++$i);
                }
                $model->slug = $slug;
            }
        });
    }
}
