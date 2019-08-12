<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rolemaster extends Model
{
     protected $fillable = [
        'RoleName','CreatedBy','UpdatedBy',
    ];


    public function childRole(){
    	return $this->belongsTo('App\Rolemaster', 'parent_id', 'id')->select('Rolemaster.RoleName');
    }
}
