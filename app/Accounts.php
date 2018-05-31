<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accounts extends Model
{
    public $timestamps=false;

    protected $fillable = [
        'account_type_id', 'code', 'start_balance', 'date', 'description'
    ];
}
