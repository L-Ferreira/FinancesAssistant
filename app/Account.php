<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public $timestamps=false;


    public function bootIfNotBooted()
    {
        parent::boot();

        static::creating( function ($model) {
            $model->setCreatedAt($model->freshTimestamp());
        });
    }


//    public function setUpdatedAt($value)
//    {
//        // Do nothing.
//    }

    protected $fillable = [
        'account_type_id', 'code', 'start_balance', 'date', 'description'
    ];

    public function user(){
        return $this->belongsTo('App\User','owner_id');
    }
}
