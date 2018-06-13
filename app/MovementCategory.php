<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovementCategory extends Model
{
    public $timestamps=false;


    public function bootIfNotBooted()
    {
        parent::boot();

        static::creating( function ($model) {
            $model->setCreatedAt($model->freshTimestamp());
        });
    }

}
