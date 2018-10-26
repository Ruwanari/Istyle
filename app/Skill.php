<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $table = "skill";

    //many to many with stylists table
    public function stylists()
    {
        return $this->belongsToMany('App\Stylist', 'stylistskill');
    }
}
