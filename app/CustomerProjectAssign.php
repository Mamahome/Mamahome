<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerProjectAssign extends Model
{
      public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }
}