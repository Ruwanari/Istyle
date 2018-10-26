<?php
namespace App\Http\Controllers;

use App\Stylist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Doctrine\DBAL\Driver\PDOConnection;

class StylistController extends Controller
{
    const VERSION = '1.0';

    //add a stylist
    public function addStylist(Request $request)
    {
        $stylist = new Stylist();
        $stylist->FirstName = $request->input('FirstName');
        $stylist->LastName = $request->input('LastName');
        $stylist->Location = $request->input('Location');
        $stylist->RatePerHour = $request->input('RatePerHour');
        $stylist->ContactNumber = $request->input('ContactNumber');
        $stylist->Email = $request->input('Email');
        $stylist->Password=$request->input('Password');
        $stylist->Gender = $request->input('Gender');
        $stylist->ImageUrl = $request->input('ImageUrl');
        $stylist->save();
        return response()->json(['stylist'=>$stylist], 200);
    }

    //get all stylists
    public function getAllStylists()
    {
        $stylists = \App\Stylist::with('skills', 'jobTypes')->get();
        $response = [
            'stylists'=>$stylists
        ];

        return response()->json($response, 200);
    }

    //get all skills
    public function getSkills()
    {
        $db = DB::connection()->getPdo();
        $db->setAttribute(PDOConnection::ATTR_ERRMODE, PDOConnection::ERRMODE_EXCEPTION);
        $db->setAttribute(PDOConnection::ATTR_EMULATE_PREPARES, true);

        $skills = \App\Skill::all();
        $response = [
            'skills'=>$skills
        ];

        // $queryResult = $db->prepare('call get_all_records()');
        // $queryResult->execute();
        // $results = $queryResult->fetchAll(PDOConnection::FETCH_ASSOC);
        // $queryResult->closeCursor();
        // //return $results;
        // $response = [
        //    'skills'=>$results
        //   ];

        return response()->json($response, 200);
    }

    //get all locations
    public function getLocations()
    {
        $locations = \App\Stylist::distinct()->orderBy('Location')->get(['Location']);
        $response = [
            'locations'=>$locations
        ];

        return response()->json($response, 200);
    }

    //get all rates
    public function getRates()
    {
        $rates = \App\Stylist::distinct()->orderBy('RatePerHour')->get(['RatePerHour']);
        $response = [
            'rates'=>$rates
        ];

        return response()->json($response, 200);
    }

    //get all job types
    public function getJobTypes()
    {
        // $jobTypes = \App\JobType::all();
        // $response = [
        //     'jobTypes'=>$jobTypes
        // ];

        // return response()->json($response, 200);

        $start = microtime(true);

        $result = Cache::remember('jobtypes', 10, function () {
            return \App\JobType::all();
        });

        $duration = (microtime(true) - $start) * 1000;
        \Log::info('with cache: '.$duration.'ms');
        
        //echo($duration);

        $response = [
            'jobTypes'=>$result
        ];

        return response()->json($response, 200);
    }

    

    public function searchStylist($firstname, $lastname)
    {
        $stylists = \App\Stylist::with('skills', 'jobTypes')->where('firstName', 'LIKE', '%' . $firstname . '%')
        ->orWhere('lastName', 'LIKE', '%'.$lastname.'%')->get();

        if (count($stylists) > 0) {
            $response = [
                'stylists'=>$stylists
            ];

            return response()->json($response, 200);
        } else {
            return response()->json(['messege'=>'No stylist'], 404);
        }
    }

    public function searchStylist2($keyname)
    {
        $db = DB::connection()->getPdo();
        $db->setAttribute(PDOConnection::ATTR_ERRMODE, PDOConnection::ERRMODE_EXCEPTION);
        $db->setAttribute(PDOConnection::ATTR_EMULATE_PREPARES, true);

        // $skills = \App\Skill::all();
        // $response = [
        //     'skills'=>$skills
        // ];

        $queryResult = $db->prepare('call search_stylist_by_name(?)');
        $queryResult->bindParam(1, $keyname);
        $queryResult->execute();
        $results = $queryResult->fetchAll(PDOConnection::FETCH_ASSOC);
        $queryResult->closeCursor();
        //return $results;
        $response = [
           'stylists'=>$results
          ];

        return response()->json($response, 200);
        // $stylists = \App\Stylist::with('skills','jobTypes')->where( 'firstName','LIKE','%' . $keyname . '%')
        // ->orWhere('lastName','LIKE','%'.$keyname.'%')->get();

        // if(count($stylists) > 0) {
        //     $response = [
        //         'stylists'=>$stylists
        //     ];
            
        //     return response()->json($response,200);
        // } else {

        //     return response()->json(['messege'=>'No stylist'],404);
        // }
    }

    //View the stylist profile
    public function viewProfile($id)
    {
        $stylist = \App\Stylist::with('skills', 'jobTypes')->find($id);
        $response = [
            'stylist'=>$stylist
        ];

        return response()->json($response, 200);
    }

    //Get the pictures from stylist gallery
    public function viewGallery($id)
    {
        $gallery = \App\Gallery::where('StylistId', $id)->select('ImageUrl')->get();
        $response = [
            'gallery'=>$gallery
        ];

        return response()->json($response, 200);
    }

    //Get the schedule of stylist
    public function viewSchedule($id)
    {
        $schedule = \App\Schedule::where('StylistId', $id)->select('Date', 'TimeSlot')->where('Date', '>=', date('Y-m-d'))->get();
        $response = [
            'schedule'=>$schedule
        ];

        return response()->json($response, 200);
    }

    //Filter the stylists
    public function filter(Request $request, Stylist $stylist)
    {
        $stylist =  \App\Stylist::
        join('stylistskill', 'stylists.id', '=', 'stylistskill.stylist_id')
        ->join('skill', 'stylistskill.skill_id', '=', 'skill.id')
        ->join('stylistjobtype', 'stylists.id', '=', 'stylistjobtype.stylist_id')
        ->join('jobtype', 'stylistjobtype.job_type_id', '=', 'jobtype.id')
        ->select('stylists.*', 'skill.Description', 'jobtype.JobDescription')
        ->groupBy('stylists.id')
        ->newQuery();
        
        // Search for a user based on their first name.
        if ($request->has('FirstName')) {
            $stylist->where('FirstName', 'LIKE', '%' .$request->input('FirstName'). '%');
        }
        // Search for a user based on their last name.
        if ($request->has('LastName')) {
            $stylist->where('LastName', 'LIKE', '%' .$request->input('LastName'). '%');
        }
        // Search for a user based on their location.
        if ($request->has('location')) {
            $stylist->where('Location', $request->input('location'));
        }
        // Search for a user based on their rate.
        if ($request->has('rate')) {
            $stylist->where('RatePerHour', '<=', $request->input('rate'));
        }
        // Search for a user based on their skill.
        if ($request->has('skill')) {
            $stylist->where('Description', $request->input('skill'));
        }
        // Search for a user based on their jobType.
        if ($request->has('jobType')) {
            $stylist->where('JobDescription', $request->input('jobType'));
        }
        // Search for a user based on their name.
        if ($request->has('firstLastName')) {
            $stylist->where('FirstName', 'LIKE', '%' .$request->input('firstLastName'). '%');
        }
        
        // Get the results and return them.
        return $stylist->get();
    }
}
?>

   
   