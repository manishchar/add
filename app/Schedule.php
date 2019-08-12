<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
	protected $table = 'schedules';
    protected $fillable = [
        'client_id','advertise_id','fromDate','toDate','videoUrl','videoLength','videoTotalLength','createdby','IsActive',
    ];

    public function client(){
        return $this->belongsTo('App\Client', 'client_id', 'id');
    }
    public function advertise(){
        return $this->belongsTo('App\Advertise', 'advertise_id', 'id');
    }

    
}
