<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{

    public $timestamps=false;


    public function bootIfNotBooted()
    {
        parent::boot();

        static::creating( function ($model) {
            $model->setCreatedAt($model->freshTimestamp());
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    protected $fillable = [
        'account_id', 'movement_category_id', 'value', 'description'
    ];

    public  function account(){
        return $this->belongsTo('App\Account');
    }
}
