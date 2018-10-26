<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    protected $table = "jobtype";

    //many to many with stylists table
    public function stylists()
    {
        return $this->belongsToMany('App\Stylist', 'stylistjobtype');
    }
}
