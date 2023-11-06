<?php

namespace App\Http\Controllers;

use App\Models\country_phone_code;
use App\Models\emirate;
use App\Models\numberdetail;
use App\Models\plan;
use App\Models\User;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    //
    public function addnewleads(Request $request){
        // return "Zoom";
        $countries = country_phone_code::select('name')->get();
        $emirates = emirate::select('name')->get();
        $users = User::select('id','name')->where('role','Sale')->get();
        $plans = plan::where('status',1)->get();
        $q = numberdetail::select("numberdetails.type")
        ->where("numberdetails.status", "Available")
            // ->whereIn("numberdetails.channel_type", ['TTF','ExpressDial'])
            ->whereIn("numberdetails.channel_type", ['ConnectCC'])
            ->groupBy('numberdetails.type')

            ->get();
        return view('agent.new-lead',compact('countries', 'emirates','users','plans','q'));
    }
    //
}
