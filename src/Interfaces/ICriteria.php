<?php

namespace Josebasmtz\MvcSrCore\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use \Illuminate\Database\Eloquent\Builder as EloquentBuilder;

interface ICriteria
{
    /**
     * @param Model|Builder|EloquentBuilder $model
     * @return Model|Builder|EloquentBuilder|null
     */
    public function apply(&$model);
}