<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    protected $fillable=['name','user_id'];

    public function user_id(){
        return $this->hasOne('App\User','id','user_id');
    }
}
