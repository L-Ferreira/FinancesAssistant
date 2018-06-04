<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public $timestamps=false;

    protected $fillable = [
        'account_type_id', 'code', 'start_balance', 'date', 'description'
    ];
}
