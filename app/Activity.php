<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
     protected $fillable = [
        'CandidateId',
        'OrgID',
        'JobID',
        'message',
        'Status',
        'CreatedBy',
        'UpdatedBy',
    ];
     
   public function user(){
    	return $this->belongsTo('App\User', 'CreatedBy', 'id')->select('name');
    }
     
      protected $table = 'candidate_activity';
}
