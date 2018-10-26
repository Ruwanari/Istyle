<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Stylist extends Model
{
    protected $table = "stylists";
    // many to many relationship with skills table
    public function skills()
    {
        return $this->belongsToMany('App\Skill', 'stylistskill');
    }
    //many to many relationship with jobtypes table
    public function jobTypes()
    {
        return $this->belongsToMany('App\JobType', 'stylistjobtype');
    }
}
