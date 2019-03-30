<?php

namespace App\Pages\Implementation\Services\Grid\Sources;

use App\Grid\Sources\EloquentGridSource;
use App\Pages\Models\Page;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class EloquentPagesGridSource extends EloquentGridSource implements PagesGridSource {

    /**
     * {@inheritdoc}
     */
    protected function translateField(string $field): string {
        return $field;
    }

    /**
     * {@inheritdoc}
     */
    protected static function getNewQuery(): EloquentBuilder {
        return Page::query();
    }

}
