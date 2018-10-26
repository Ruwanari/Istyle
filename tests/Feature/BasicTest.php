<?php
namespace Tests\Feature;

use App\Stylist;
use App\Skill;
use App\JobType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BasicTest extends TestCase
{
    public function test_stylist_can_be_fetched_by_id()
    {
        $found_stylist = Stylist::find(1);
        $this->assertEquals($found_stylist->FirstName, "Luke");
    }

    public function test_stylists_can_be_fetched_by_name()
    {
        $found_stylist=Stylist::where('FirstName', 'LIKE', '%' .'L'. '%')->get();
        $this->assertEquals($found_stylist[0]->FirstName, "Luke");
    }

    public function test_stylist_can_be_fetched_by_location()
    {
        $found_stylist = Stylist::where('FirstName', 'LIKE', '%' .'L'. '%')->where('Location', '=', 'Newyork')->get();
        $this->assertEquals($found_stylist[0]->FirstName, "Luke");
    }

    public function test_stylist_can_be_fetched_by_rate()
    {
        $found_stylist = Stylist::find(1);
        $this->assertEquals($found_stylist->RatePerHour, 200);
    }

    public function test_skill_can_be_fetched()
    {
        $skill = Skill::find(1);
        $this->assertEquals($skill->Description, "Hair Colouring");
    }

    public function test_jobtype_can_be_fetched()
    {
        $jobtype = JobType::find(2);
        $this->assertEquals($jobtype->JobDescription, "Educator");
    }
}
