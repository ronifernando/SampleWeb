<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prepaid extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function orders()
    {
        return $this->morphMany('App\Order', 'orderable');
    }
}
