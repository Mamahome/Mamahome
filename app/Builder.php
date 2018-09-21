<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Builder extends Model
{
   protected $table = 'builders';

   use LogsActivity;



protected $fillable = ['project_id',
'builder_name',
'builder_email',
'builder_contact_no ',
];


  protected static $logOnlyDirty = true; 
      protected static $causerId = 3;
      protected static $logName = "";

      protected static $logFillable = true;


   public function projectdetails()
    {
    	return $this->belongsTo("App\ProjectDetails");
    }
}
