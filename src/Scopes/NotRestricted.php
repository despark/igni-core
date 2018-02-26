<?php

namespace Despark\Cms\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class NotRestricted implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function apply(Builder $builder, Model $model)
    {
        $request = request();

        if ($request->is('admin/*') && $request->get('preview_mode') != 1) {
            $builder->where(function ($query) use ($model) {
                $query->where($model->getTable() . '.is_restricted', '=', 0);
            });
        }
    }

}