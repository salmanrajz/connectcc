<?php

namespace App\Http\Controllers;

use App\main_data_user_assigned as AppMain_data_user_assigned;
use App\Models\numberdetail;
use App\Models\similar_number_generator;
use App\Models\verification_form;
use App\Models\choosen_number;
use App\Models\choosen_number_log;
use App\Models\lead_sale;
use App\Models\main_data_user_assigned;
use App\Models\number_assigner;
use App\Models\uploaderdatabank;
use App\Models\WhatsAppMnpBank;
use App\Models\WhatsAppMnpBanker;
use App\similar_number_generator as AppSimilar_number_generator;
use App\verification_form as AppVerification_form;
use App\WhatsAppMnpBanker as AppWhatsAppMnpBanker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Validator;

class NumberController extends Controller
{
    //
    public function region_lead(Request $request)
    {
        $operation = AppVerification_form::select("timing_durations.lead_generate_time", "lead_sales.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", "users.name as agent_name")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->LeftJoin(
                'timing_durations',
                'timing_durations.lead_no',
                '=',
                'verification_forms.lead_no'
            )
            ->LeftJoin(
                'remarks',
                'remarks.lead_no',
                '=',
                'verification_forms.lead_no'
            )
            ->LeftJoin(
                'status_codes',
                'status_codes.status_code',
                '=',
                'verification_forms.status'
            )
            ->LeftJoin(
                'lead_sales',
                'lead_sales.id',
                '=',
                'verification_forms.lead_no'
            )
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                'LIKE',
                'lead_sales.selected_number'
            )
            ->LeftJoin(
                'users',
                'users.id',
                '=',
                'lead_sales.saler_id'
            )
            ->LeftJoin(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'lead_sales.id'
            )
            ->where('numberdetails.region', $request->slug)
            ->groupBy('verification_forms.lead_no')
            ->get();
        // $operation = verification_form::wherestatus('1.10')->get();
        // return view('number.number-list-activation', compact('operation'));
        return view('dashboard.my-lead-junaid', compact('operation'));
    }
    //
    public function index(Request $request)
    {
        // return $request->slug;
        $subject = "Rent now best Country exclusive discount offer in";
        // $subject = array('milk contains sugar', 'sugar is white', 'sweet as sugar');
        $output = str_replace('Country', 'Pakistan', $subject);
        // return $output;
    }
    //
    public function my_generate_number(Request $request)
    {
        $k =  AppSimilar_number_generator::where('number', $request->number)->whereNull('remarks')->paginate();
        return view('dashboard.add-log', compact('k'));
    }
    public function my_call_log(Request $request)
    {
        $k = AppWhatsAppMnpBanker::select('whats_app_mnp_banker.number', 'main_data_user_assigners.id', 'main_data_user_assigners.number_id', 'main_data_user_assigners.status')
            ->Join(
                'main_data_user_assigners',
                'main_data_user_assigners.number_id',
                'whats_app_mnp_banker.id'
            )
            ->where('main_data_user_assigners.user_id', auth()->user()->id)
            ->where('is_status', '1')
            ->whereNull('main_data_user_assigners.status')
            ->Orderby('id', 'desc')
            ->groupBy('whats_app_mnp_banker.number')
            ->paginate();
        // $k =  number_assigner::where('user_id',auth()->user()->id)->paginate();
        return view('dashboard.add-log', compact('k'));
    }
    //
    public function submit_dnc_number(Request $request)
    {
        // return $request;
        // $k = number_assigner::findorfail($request->userid);
        // $k->remarks = $request->status;
        // $k->user_id = auth()->user()->id;
        // $k->save();
        $details = [
            'numbers' => '923123500256,923121337222',
            'dnc_number' => $request->list,
        ];
        $d  = \App\Models\dnd_aashir::create([
            // 'system_dnd','vicidial_dnd','yeastar_dnd','old_yeastar_dnd'
            'system_dnd' => $request->list,
            'vicidial_dnd' => $request->list,
            'yeastar_dnd' => $request->list,
            'old_yeastar_dnd' => $request->list,
        ]);
        if ($request->status == 'DNC') {

            return \App\Http\Controllers\WhatsAppController::DNCWhatsApp($details);
        }
        return back()->with('success', 'Add successfully.');
        // return 1;
    }
    //
    public function submit_feedback_number(Request $request)
    {
        // return $request;
        // $b = uploaderdatabank::select('uploaderdatabank.*')
        // ->Join(
        //     'main_data_manager_assigners',
        //     'main_data_manager_assigners.number_id',
        //     'uploaderdatabank.id'
        // )
        // ->where('uploaderdatabanks.number',$request->number)
        // // ->where('main_data_manager_assigners.manager_id', auth()->user()->id)
        // // ->where('status_1', '1')
        // 050-XXX-XX-312
        // // ->whereNull('main_data_manager_assigners.status')
        // // ->Orderby('id', 'desc')
        // ->first();
        // $b
        $k = AppMain_data_user_assigned::where('number_id', $request->number_id)->first();
        $k->status = $request->status;
        if ($request->status == 'DNC') {
            $k->mark_dnd = 1;
        }
        if ($request->status == 'soft_dnd') {
            $k->mark_soft_dnd = 1;
        }
        $k->user_id = auth()->user()->id;
        $k->save();
        $details = [
            'numbers' => '923121337222,923123500256',
            'dnc_number' => $request->number,
        ];
        if ($request->status == 'DNC') {
            \App\Http\Controllers\WhatsAppController::DNCWhatsApp($details);
        }
        return 1;
    }
    //
    public function generate_number(Request $request)
    {
        $s = '055,052,050,056';
        $s = explode(',', $s);
        $b = 1;
        $c = 1;
        $d = 1;
        $g = 1;
        $number_form = $request->slug;
        // $number_form = "0543535333";
        $a = substr($number_form, 0, 1);
        $b = substr($number_form, 1, 1);
        $c = substr($number_form, 2, 1);
        $d = substr($number_form, 3, 1);
        $e = substr($number_form, 4, 1);
        $f = substr($number_form, 5, 1);
        $g = substr($number_form, 6, 1);
        foreach ($s as $key) {

            // return $l = "Hey IM". $key;
            if ($key == '50') {
                // for ($i = 0; $i < 10; $i++) {
                $kk = 1001;
                for ($i = 10; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    //  '<br>';
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'userid' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                }
                // $l;
                // }
            } elseif ($key == '52') {
                // for ($i = 0; $i < 10; $i++) {
                $kk = 2001;
                for ($i = 10; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'userid' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            } elseif ($key == '54') {
                $kk = 3001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 10; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'userid' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            } elseif ($key == '55') {
                $kk = 4001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 10; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'userid' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            } elseif ($key == '56') {
                // for ($i = 0; $i < 10; $i++) {
                // $l;
                $kk = 4001;

                for ($i = 10; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'userid' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // }
            } elseif ($key == '58') {

                $kk = 5001;
                // for ($i = 0; $i < 10; $i++) {
                for ($i = 10; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'userid' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            }
        }
        //
        // similar2
        foreach ($s as $key) {

            // $l = "Hey IM". $key;
            if ($key == '50') {
                $kk = 6001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    //  '<br>';
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                }
                // $l;
                // }
            } elseif ($key == '52') {
                $kk = 7001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }

                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            } elseif ($key == '54') {
                $kk = 8001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'userid' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            } elseif ($key == '55') {
                $kk = 9001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 10; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            } elseif ($key == '56') {
                $kk = 10001;

                // for ($i = 0; $i < 10; $i++) {
                // $l;
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // }
            } elseif ($key == '58') {
                $kk = 11001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            }
        }
        // similar3
        foreach ($s as $key) {

            // $l = "Hey IM". $key;
            if ($key == '50') {
                $kk = 12001;
                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                }
                // $l;
                // }
            } elseif ($key == '52') {
                $kk = 13001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            } elseif ($key == '54') {
                $kk = 14001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            } elseif ($key == '55') {
                $kk = 15001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 10; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            } elseif ($key == '56') {
                $kk = 16001;

                // for ($i = 0; $i < 10; $i++) {
                // $l;
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // }
            } elseif ($key == '58') {
                $kk = 17001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            }
        }
        // similar4
        foreach ($s as $key) {

            // $l = "Hey IM". $key;
            if ($key == '50') {
                $kk = 18001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $c . $i .  $f . $g;

                    // $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                }
                // $l;
                // }
            } elseif ($key == '52') {
                $kk = 19001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $c . $i .  $f . $g;

                    // $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            } elseif ($key == '54') {
                $kk = 20001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $c . $i .  $f . $g;

                    // $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            } elseif ($key == '55') {
                $kk = 21001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 10; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $c . $i .  $f . $g;

                    // $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            } elseif ($key == '56') {
                $kk = 22001;

                // for ($i = 0; $i < 10; $i++) {
                // $l;
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $c . $i .  $f . $g;

                    // $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // }
            } elseif ($key == '58') {
                $kk = 23001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $c . $i .  $f . $g;

                    // $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            }
        }
        // similar 1xx4567
        foreach ($s as $key) {

            // $l = "Hey IM". $key;
            if ($key == '50') {
                $kk = 24001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $i . $d . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                }
                // $l;
                // }
            } elseif ($key == '52') {
                $kk = 25001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $i . $d . $e . $f . $g;
                    // $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            } elseif ($key == '54') {
                $kk = 26001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $i . $d . $e . $f . $g;
                    // $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            } elseif ($key == '55') {
                $kk = 27001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 10; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $i . $d . $e . $f . $g;
                    // $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            } elseif ($key == '56') {
                $kk = 28001;

                // for ($i = 0; $i < 10; $i++) {
                // $l;
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $i . $d . $e . $f . $g;
                    // $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // }
            } elseif ($key == '58') {
                $kk = 29001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $i . $d . $e . $f . $g;
                    // $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $kk++;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    //  =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            }
        }
        // similar 1234xx7
        foreach ($s as $key) {

            // $l = "Hey IM". $key;
            if ($key == '50') {
                $kk = 30001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $c . $d . $i . $g;

                    // $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                }
                // $l;
                // }
            } elseif ($key == '52') {
                $kk = 31001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $c . $d . $i . $g;
                    // $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            } elseif ($key == '54') {
                $kk = 320001;

                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $c . $d . $i . $g;
                    // $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            } elseif ($key == '55') {
                $kk = 33001;
                // for ($i = 0; $i < 10; $i++) {
                for ($i = 10; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $c . $d . $i . $g;
                    // $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            } elseif ($key == '56') {
                $kk = 34001;

                // for ($i = 0; $i < 10; $i++) {
                // $l;
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $c . $d . $i . $g;
                    // $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);

                        // $q = new abcd();
                        // $q->number = $str2;
                        // $q->create();
                    }
                    // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // }
            } elseif ($key == '58') {
                $kk = 35001;
                // for ($i = 0; $i < 10; $i++) {
                for ($i = 1; $i < 99; $i++) {
                    if ($i == 1) {
                        $i = '01';
                    } elseif ($i == 2) {
                        $i = '02';
                    } elseif ($i == 3) {
                        $i = '03';
                    } elseif ($i == 4) {
                        $i = '04';
                    } elseif ($i == 5) {
                        $i = '05';
                    } elseif ($i == 6) {
                        $i = '06';
                    } elseif ($i == 7) {
                        $i = '07';
                    } elseif ($i == 8) {
                        $i = '08';
                    } elseif ($i == 9) {
                        $i = '09';
                    } else {
                        $i = $i;
                    }
                    $str2 =  $key . $a . $b . $c . $d . $i . $g;
                    // $str2 =  $key . $a . $b . $i . $e . $f . $g;

                    // $str2 =  $key . $a . $b . $c . $d . $e .  $i;

                    // $str2 = '00' . $key . $i .  $c . $d . $e . $f . $g;
                    // $sql = "SELECT * from abcd where number = '$str2'";
                    // $r = query($sql);
                    // confirm($r);
                    // $count = row_count($r);
                    // echo '<br>';
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'user_id' => auth()->user()->id,
                        ]);
                    }
                    // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                }
                // $str2 =  $key . $i .  $c . $d . $e . $f . $g;
                // $l;
                // }
            }
        }
        //
        foreach ($s as $key) {

            // $l = "Hey IM". $key;
            if ($key == '50') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }
                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'userid' => auth()->user()->id,
                        ]);
                    }
                }
            }
            if ($key == '50') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'userid' => auth()->user()->id,
                        ]);
                    }
                }
            }
            if ($key == '50') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str4,
                        'user_id' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '50') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str5,
                        'user_id' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '50') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str6,
                        'user_id' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '50') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str7,
                        'user_id' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '50') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str8,
                        'user_id' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '52') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str2,
                        'user_id' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '52') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'userid' => auth()->user()->id,
                        ]);
                    }
                }
            }
            if ($key == '52') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str4,
                        'user_id' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '52') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str5,
                        'user_id' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '52') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str6,
                        'user_id' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '52') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str7,
                        'user_id' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '52') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str8,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '54') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str2,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '54') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'userid' => auth()->user()->id,
                        ]);
                    }
                }
            }
            if ($key == '54') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str4,
                            'userid' => auth()->user()->id,
                        ]);
                    }
                }
            }
            if ($key == '54') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str5,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '54') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str6,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '54') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str7,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '54') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str8,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '55') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str2,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '55') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'userid' => auth()->user()->id,
                        ]);
                    }
                }
            }
            if ($key == '55') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str4,
                            'userid' => auth()->user()->id,
                        ]);
                    }
                }
            }
            if ($key == '55') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str5,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '55') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str6,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '55') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str7,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '55') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str8,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '56') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str2,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '56') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'userid' => auth()->user()->id,
                        ]);
                    }
                }
            }
            if ($key == '56') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str4,
                            'userid' => auth()->user()->id,
                        ]);
                    }
                }
            }
            if ($key == '56') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str5,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '56') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str6,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '56') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str7,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '56') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str8,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '58') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str2,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '58') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str2,
                            'userid' => auth()->user()->id,
                        ]);
                    }
                }
            }
            if ($key == '58') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $count = similar_number_generator::where('generated_number', $str2)->where('number', $number_form)->count();
                    if ($count > 0) {
                    } else {

                        $q = similar_number_generator::create([
                            'number_id' => $number_form,
                            'number' => $number_form,
                            'generated_number' => $str4,
                            'userid' => auth()->user()->id,
                        ]);
                    }
                }
            }
            if ($key == '58') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str5,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '58') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str6,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '58') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str7,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
            if ($key == '58') {

                for ($i = 0; $i < 10; $i++) {
                    if ($i == 1) {
                        $i = '1';
                    } elseif ($i == 2) {
                        $i = '2';
                    } elseif ($i == 3) {
                        $i = '3';
                    } elseif ($i == 4) {
                        $i = '4';
                    } elseif ($i == 5) {
                        $i = '5';
                    } elseif ($i == 6) {
                        $i = '6';
                    } elseif ($i == 7) {
                        $i = '7';
                    } elseif ($i == 8) {
                        $i = '8';
                    } elseif ($i == 9) {
                        $i = '9';
                    } else {
                        $i = $i;
                    }
                    //sequence 4
                    // $str2 = '55'.$i . substr($str, 3);
                    // 7
                    $str2 =  $key . $a . $b . $c . $d . $e . $f . $i;
                    // 6
                    $str3 =  $key . $a . $b . $c . $d . $e . $i . $g;
                    // 5
                    $str4 =  $key . $a . $b . $c . $d . $i . $f . $g;
                    // 4
                    $str5 =  $key . $a . $b . $c . $i . $e . $f . $g;
                    // 3
                    $str6 =  $key . $a . $b . $i . $d . $e . $f . $g;
                    // 2
                    $str7 =  $key . $a . $i . $c . $d . $e . $f . $g;
                    // 1
                    if ($i == 0) {
                    } else {
                        $str8 =  $key . $i . $b . $c . $d . $e . $f . $g;
                    }

                    $q = similar_number_generator::create([
                        'number_id' => $number_form,
                        'number' => $number_form,
                        'generated_number' => $str8,
                        'userid' => auth()->user()->id,
                    ]);
                }
            }
        }
        //
        // return "z";
        return redirect()->route('my.generated.number', $number_form);
        // return $data = similar_number_generator::where('number',$number_form)->get();
    }
    //
    public function customer_number_checker(Request $request)
    {
        // return $request;
        $number = $request->number;
        $data = lead_sale::where('customer_number', $request->number)
            //  ->where(
            ->get();
        if ($data->count() > 0) {
            $count = $data->count();
            return view('ajax.check-info', compact('number', 'count'));
        }
        //  return "User Info Already Exist, Click <a href='"+{{route('home'}}+"'>Here</a> to see details";
    }
    //
    public function customer_number_dtl(Request $request)
    {
        // return $request->slug;
        $data = \App\Models\lead_sale::select('lead_sales.*', 'status_codes.status_name')->where('customer_number', $request->slug)
            ->Join(
                'status_codes',
                'status_codes.status_code',
                '=',
                'lead_sales.status'
            )
            // ->LeftJoin('plans', 'plans.id', 'lead_sales.select_plan')
            ->get();
        //  $data->remarks;

        // return $data->count();
        foreach ($data as $zkey => $item) {
            // echo $item->select_plan . '<br>';
            if (strpos($item->select_plan, ",") !== false) {
                // echo
                //     // list($d, $l) = explode('.', $dm, 2);
                foreach (explode(',', $item->select_plan) as $key => $k) {
                    // echo $k . '<br>';
                    $plan = \App\Models\plan::findorfail($k);
                    $plan_name[] = $plan->plan_name;

                    //         // // $data_gb[] = $plan->data;
                }
                $planName = implode(',', $plan_name);
                // return $plan_name;
                echo  $zkey . ": Remarks  =>"  . ' ' .  $item->remarks . ' <br> Status => ' . $item->status_name . ' <br> Selected Number => ' . $item->selected_number . ' <br> Selected Plan  =>' . $planName . ' <br> Last Update  =>' . $item->updated_at . '<br> <hr>';
            } else {
                // return $item->select_plan;
                $plan = \App\Models\plan::findorfail($item->select_plan);
                echo  $zkey . ": Remarks  =>"  . ' ' .  $item->remarks . ' <br> Status => ' . $item->status_name . ' <br> Selected Number => ' . $item->selected_number . ' <br> Selected Plan  =>' . $plan->plan_name . ' <br> Last Update  =>' . $item->updated_at . '<br> <hr>';
            }
        }
    }
    public function NumberTypeList(Request $request)
    {
        $operation = numberdetail::select('numberdetails.*')
            ->wherein('status', ['Available', 'Hold', 'Reserved'])
            ->GroupBy('identity')
            ->orderBy('created_at', 'asc')
            ->get();
        // return view('')
        return view('dashboard.NumberTypeList', compact('operation'));
    }
    public static function SharedAvailable($id, $status)
    {
        return $operation = numberdetail::select('numberdetails.*')
            ->where('identity', $id)
            ->where('status', $status)
            ->get()->count();
    }
    public function NumberTypeDtl(Request $request)
    {
        // return $request->id . '-' . $request->channel;
        if ($request->id == '1.02') {
            $id = 'Active';
            $operation = \App\activation_form::select('activation_forms.*', 'users.name as agent_name')
                ->LeftJoin(
                    'lead_sales',
                    'lead_sales.id',
                    'activation_forms.lead_id'
                )
                ->LeftJoin(
                    'users',
                    'users.id',
                    'lead_sales.saler_id'
                )
                ->Join(
                    'numberdetails',
                    'numberdetails.number',
                    'activation_forms.activation_selected_no'
                )
                ->where('numberdetails.identity', $request->channel)
                ->where('activation_forms.status', $request->id)
                // ->where('users.agent_code', auth()->user()->agent_code)
                // ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                ->get();
            return view('dashboard.view-all-active', compact('operation', 'id'));
        } else if ($request->id == '1.01') {
            $call_center = lead_sale::select("users.agent_code")
                // $user =  DB::table("subjects")->select('subject_name', 'id')

                ->Join(
                    'users',
                    'users.id',
                    '=',
                    'lead_sales.saler_id'
                )
                // ->Join(
                //     'numberdetails',
                //     'numberdetails.number',
                //     'lead_sales.selected_number'
                // )
                ->leftJoin("numberdetails", \DB::raw("FIND_IN_SET(numberdetails.number,lead_sales.selected_number)"), ">", \DB::raw("'0'"))
                ->where('numberdetails.identity', $request->channel)
                ->where('numberdetails.status', 'Reserved')
                // ->orderBy('lead_sales.updated_at', 'desc')
                ->GroupBy('users.agent_code')
                ->get();
            $status = 'Reserved';
            $channel = $request->channel;
            // $operation = lead_sale::wherestatus('1.01')->get();
            return view('dashboard.number-mygrplead', compact('status', 'channel', 'call_center'));
        } else if ($request->id == 'Hold') {
            $call_center = lead_sale::select("users.agent_code")
                // $user =  DB::table("subjects")->select('subject_name', 'id')

                ->Join(
                    'users',
                    'users.id',
                    '=',
                    'lead_sales.saler_id'
                )
                // ->Join(
                //     'numberdetails',
                //     'numberdetails.number',
                //     'lead_sales.selected_number'
                // )
                ->leftJoin("numberdetails", \DB::raw("FIND_IN_SET(numberdetails.number,lead_sales.selected_number)"), ">", \DB::raw("'0'"))
                ->where('numberdetails.identity', $request->channel)
                ->where('numberdetails.status', 'Hold')
                // ->orderBy('lead_sales.updated_at', 'desc')
                ->GroupBy('users.agent_code')
                ->get();
            $status = 'Hold';
            $channel = $request->channel;
            // $operation = lead_sale::wherestatus('1.01')->get();
            return view('dashboard.number-mygrplead', compact('status', 'channel', 'call_center'));
        }
    }
    public static function ReservedNumberCount($id, $channel, $status)
    {
        return $call_center = lead_sale::select("users.agent_code")
            ->Join(
                'users',
                'users.id',
                '=',
                'lead_sales.saler_id'
            )
            ->leftJoin("numberdetails", \DB::raw("FIND_IN_SET(numberdetails.number,lead_sales.selected_number)"), ">", \DB::raw("'0'"))
            ->where('numberdetails.identity', $channel)
            ->where('numberdetails.status', $status)
            // ->orderBy('lead_sales.updated_at', 'desc')
            // ->GroupBy('users.agent_code')
            ->where('users.agent_code', $id)
            ->count();
    }
    // publ
    public function ReservedNumberList(Request $request)
    {
        // return $request;
        $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->LeftJoin(
                'timing_durations',
                'timing_durations.lead_no',
                '=',
                'lead_sales.id'
            )
            ->Join(
                'status_codes',
                'status_codes.status_code',
                '=',
                'lead_sales.status'
            )
            ->Join(
                'users',
                'users.id',
                '=',
                'lead_sales.saler_id'
            )
            ->leftJoin("numberdetails", \DB::raw("FIND_IN_SET(numberdetails.number,lead_sales.selected_number)"), ">", \DB::raw("'0'"))
            ->where('numberdetails.identity', $request->channel)
            ->where('numberdetails.status', $request->status)
            ->where('users.agent_code', $request->agent_code)
            ->get();
        return view('ajax.ajax', compact('operation'));
    }
    //
    public function LoadNumber(Request $request)
    {
        // return $request->simtype;
        $agent_code = auth()->user()->agent_code;

        // foreach($data as $key => $d){
        //     // $dash[]['customers'] = array(
        //     //     'customer' . $key => array(
        //     //         'number' => $d->number,
        //     //     )
        //     // );
        //     $dash[] = array(
        //         'customers' => array(
        //            'customer' . $key => array(
        //                 'number' => $d->number,
        //             )
        //         )
        //     );
        //     // $dash[] =  $d->number;
        // }
        // return response()->json([
        //     $dash
        // ]);
        $slug = 'TTF';
        $simtype = $request->simtype;
        if (auth()->user()->agent_code == 'CC10') {

            $q = numberdetail::select("numberdetails.type")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                // ->where("numberdetails.id", $request->id)
                ->where("numberdetails.status", 'Available')
                ->wherein('numberdetails.channel_type', ['ExpressDial'])
                ->whereIn('numberdetails.type', ['Gold', 'Platinum', 'Silver'])
                ->groupBy('numberdetails.type')
                ->get();
        } else if (auth()->user()->agent_code == 'AAMT' || auth()->user()->agent_code == 'Aamt') {

            $q = numberdetail::select("numberdetails.type")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                // ->where("numberdetails.id", $request->id)
                ->where("numberdetails.status", 'Available')
                ->wherein('numberdetails.channel_type', ['ExpressDial'])
                ->whereIn('numberdetails.type', ['Standard'])
                ->groupBy('numberdetails.type')
                ->get();
        } else {
            // return "$";
            $q = numberdetail::select("numberdetails.type")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                // ->where("numberdetails.id", $request->id)
                ->where("numberdetails.status", 'Available')
                ->wherein('numberdetails.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp'])
                ->groupBy('numberdetails.type')
                ->get();
        }

        if ($request->ajax()) {
            //
            if (auth()->user()->role == 'ARF') {
                $data = numberdetail::select("numberdetails.number", "numberdetails.type", 'numberdetails.id', 'numberdetails.channel_type', 'numberdetails.identity')
                    // ->where("remarks.user_agent_id", auth()->user()->id)
                    // ->where("numberdetails.id", $request->id)
                    // ->where("numberdetails.type", $request->simtype)
                    ->where("numberdetails.status", 'Available')
                    ->where("numberdetails.channel_type", 'OCP1')
                    // ->groupBy('numberdetails.type')
                    ->get();
                //
                // $data = numberdetail::wherestatus('Available')->get();
                return view('number.number-dtl-fetch', compact('data'));
            }
            // elseif (auth()->user()->agent_code == 'AAMT' || auth()->user()->agent_code == 'CC10') {
            //     if ($request->simtype == 'our') {
            //         $data = numberdetail::select("numberdetails.number", "numberdetails.type", 'numberdetails.id', 'numberdetails.channel_type', 'numberdetails.identity', 'numberdetails.passcode')
            //         // ->where("remarks.user_agent_id", auth()->user()->id)
            //         // ->where("numberdetails.id", $request->id)
            //         ->where("numberdetails.call_center", $request->simtype)
            //         ->whereIn("numberdetails.channel_type", ['ExpressDial'])
            //         ->where("numberdetails.status", 'Available')
            //         ->latest()
            //         ->get();
            //     }
            //     else{
            //         $data = \App\numberdetail::select('numberdetails.id', 'numberdetails.number', 'numberdetails.channel_type', 'numberdetails.type', 'numberdetails.identity', \DB::raw('count(*) as total'))
            //         ->LeftJoin(
            //             'choosen_numbers',
            //             'choosen_numbers.number_id',
            //             'numberdetails.id'
            //         )
            //         // $data = numberdetail::select("numberdetails.number", "numberdetails.type", 'numberdetails.id', 'numberdetails.channel_type', 'numberdetails.identity')
            //         // ->where("remarks.user_agent_id", auth()->user()->id)
            //         // ->where("numberdetails.id", $request->id)
            //         ->where("numberdetails.type", $request->simtype)
            //         ->where("numberdetails.status", 'Available')
            //         ->where("numberdetails.channel_type", 'ExpressDial')
            //             // ->groupBy('numberdetails.type')
            //             // ->get();
            //         ->groupBy('numberdetails.number')->orderBy('numberdetails.id', 'asc')->get();
            //     }
            //     //
            //     // $data = numberdetail::wherestatus('Available')->get();
            //     // return view('number.number-dtl-fetch', compact('data'));
            // }
            else if ($request->simtype == 'All') {
                // return "Zoom";
                if (auth()->user()->region == 'Pak') {
                    $agent_code = auth()->user()->agent_code;

                    $data = numberdetail::select("numberdetails.number", "numberdetails.type", 'numberdetails.id', 'numberdetails.channel_type', 'numberdetails.identity')
                        ->where("numberdetails.status", 'Available')
                        ->whereIn("numberdetails.channel_type", ['TTF', 'ExpressDial', 'MWH', 'Ideacorp'])
                        ->where("numberdetails.call_center", 'Default')
                        ->when($agent_code, function ($q) use ($agent_code) {
                            if ($agent_code == 'Salman') {
                                return $q->whereNotIn('numberdetails.identity', ['SLPJUN1ED', 'GLDJUN1ED', 'PLTJUN1ED']);
                            } else if ($agent_code == 'CC10') {
                                return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                                // return $q->where('numberdetails.identity', 'EidSpecial');
                            } else if ($agent_code == 'AAMT' || $agent_code == 'Aamt') {
                                return $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial', 'Sil1Dec22ED', 'NYJAN1GLD22', 'NYJAN1SILV22'])
                                    // return $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial', 'Sil1Dec22ED'])
                                    ->whereIn("numberdetails.channel_type", ['ExpressDial']);;
                                // return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                                // return $q->where('numberdetails.identity', 'EidSpecial');
                            } else if ($agent_code == 'CC4') {
                                // return $q->whereNotIn('numberdetails.identity', ['JulyGLD1ED23K', 'JulySILV1ED23K']);

                                // return $q->whereNotIn('numberdetails.identity', ['May24EIDSGLDE3','May24EIDSilvE3']);
                                // return $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial', 'Sil1Dec22ED'])
                                // ->whereIn("numberdetails.channel_type", ['ExpressDial']);;
                                // return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                                // return $q->where('numberdetails.identity', 'EidSpecial');
                            } else {
                                return $q->whereIn("numberdetails.channel_type", ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                            }
                        })
                        // ->where('numberdetails.region','Pak')
                        // ->whereNull('numberdetails.region')
                        ->latest()
                        ->get();
                } else if (auth()->user()->agent_code == 'AAMT' || auth()->user()->agent_code == 'Aamt') {

                    $agent_code = auth()->user()->agent_code;
                    $data = \App\numberdetail::select('numberdetails.id', 'numberdetails.number', 'numberdetails.channel_type', 'numberdetails.type', 'numberdetails.identity', \DB::raw('count(*) as total'))
                        ->LeftJoin(
                            'choosen_numbers',
                            'choosen_numbers.number_id',
                            'numberdetails.id'
                        )
                        // $data = numberdetail::select("numberdetails.number","numberdetails.type",'numberdetails.id','numberdetails.channel_type','numberdetails.identity')
                        ->where("numberdetails.status", 'Available')
                        ->whereIn("numberdetails.channel_type", ['ExpressDial'])
                        ->where("numberdetails.call_center", 'Default')
                        ->whereNull('numberdetails.region')
                        // ->where('numberdetails.identity', '=!', 'Pak')
                        // ->when($id)
                        ->when($agent_code, function ($q) use ($agent_code) {
                            if ($agent_code == 'AAMT' || $agent_code == 'Aamt') {
                                return $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial', 'Sil1Dec22ED', 'NYJAN1GLD22', 'NYJAN1SILV22'])
                                    ->whereIn('numberdetails.type', ['Standard']);
                            } else if ($agent_code == 'CC4') {
                                // return $q->whereNotIn('numberdetails.identity', ['JulyGLD1ED23K', 'JulySILV1ED23K']);

                                // return $q->whereNotIn('numberdetails.identity', ['May24EIDSGLDE3','May24EIDSilvE3']);
                                // return $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial', 'Sil1Dec22ED'])
                                // ->whereIn("numberdetails.channel_type", ['ExpressDial']);;
                                // return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                                // return $q->where('numberdetails.identity', 'EidSpecial');
                            }
                        })
                        // ->where('numberdetails.region', 'Pak')
                        // ->limit(10)
                        // ->latest()
                        // ->get();
                        ->groupBy('numberdetails.number')->orderBy('numberdetails.updated_at', 'desc')->get();
                } else if (auth()->user()->agent_code == 'CC10') {

                    $agent_code = auth()->user()->agent_code;
                    $data = \App\numberdetail::select('numberdetails.id', 'numberdetails.number', 'numberdetails.channel_type', 'numberdetails.type', 'numberdetails.identity', \DB::raw('count(*) as total'))
                        ->LeftJoin(
                            'choosen_numbers',
                            'choosen_numbers.number_id',
                            'numberdetails.id'
                        )
                        // $data = numberdetail::select("numberdetails.number","numberdetails.type",'numberdetails.id','numberdetails.channel_type','numberdetails.identity')
                        ->where("numberdetails.status", 'Available')
                        ->whereIn("numberdetails.channel_type", ['ExpressDial'])
                        ->where("numberdetails.call_center", 'Default')
                        ->whereNull('numberdetails.region')
                        ->whereIn('numberdetails.type', ['Gold', 'Platinum', 'Silver'])

                        // ->where('numberdetails.identity', '=!', 'Pak')
                        // ->when($id)
                        ->when($agent_code, function ($q) use ($agent_code) {
                            if ($agent_code == 'CC10') {
                                return
                                    // $q->whereNotIn('numberdetails.identity','!=', 'August Num')
                                    $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial']);
                                // ->orwhere('numberdetails.identity','!=', 'EidSpecial');
                            }
                            if ($agent_code == 'CC2' || $agent_code == 'CC8') {
                                return
                                    // $q->whereNotIn('numberdetails.identity','!=', 'August Num')
                                    $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial']);
                                // ->orwhere('numberdetails.identity','!=', 'EidSpecial');
                            }
                        })
                        // ->where('numberdetails.region', 'Pak')
                        // ->limit(10)
                        // ->latest()
                        // ->get();
                        ->groupBy('numberdetails.number')->orderBy('numberdetails.updated_at', 'desc')->get();
                } else {
                    // return "z";
                    $agent_code = auth()->user()->agent_code;
                    $data = \App\numberdetail::select('numberdetails.id', 'numberdetails.number', 'numberdetails.channel_type', 'numberdetails.type', 'numberdetails.identity', \DB::raw('count(*) as total'))
                        ->LeftJoin(
                            'choosen_numbers',
                            'choosen_numbers.number_id',
                            'numberdetails.id'
                        )
                        // $data = numberdetail::select("numberdetails.number","numberdetails.type",'numberdetails.id','numberdetails.channel_type','numberdetails.identity')
                        ->where("numberdetails.status", 'Available')
                        ->whereIn("numberdetails.channel_type", ['TTF', 'ExpressDial', 'MWH', 'Ideacorp'])
                        ->where("numberdetails.call_center", 'Default')
                        ->whereNull('numberdetails.region')
                        // ->where('numberdetails.identity', '=!', 'Pak')
                        // ->when($id)
                        ->when($agent_code, function ($q) use ($agent_code) {
                            if ($agent_code == 'Salman') {
                                return $q->whereNotIn('numberdetails.identity', ['SLPJUN1ED', 'GLDJUN1ED', 'PLTJUN1ED']);
                            } elseif ($agent_code == 'CC10') {
                                return
                                    // $q->whereNotIn('numberdetails.identity','!=', 'August Num')
                                    $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial']);
                                // ->orwhere('numberdetails.identity','!=', 'EidSpecial');
                            } else if ($agent_code == 'CC4') {
                                // return $q->whereNotIn('numberdetails.identity', ['JulyGLD1ED23K', 'JulySILV1ED23K']);

                                // return
                                // $q->whereNotIn('numberdetails.identity','!=', 'August Num')
                                // $q->whereNotIn('numberdetails.identity', ['May24EIDSGLDE3','May24EIDSilvE3']);
                                // ->orwhere('numberdetails.identity','!=', 'EidSpecial');
                            }
                        })
                        // ->where('numberdetails.region', 'Pak')
                        // ->limit(10)
                        // ->latest()
                        // ->get();
                        ->groupBy('numberdetails.number')->orderBy('numberdetails.updated_at', 'desc')->get();
                }

                //
                // $data = numberdetail::wherestatus('Available')->get();
                // return view('number.number-dtl-fetch', compact('data'));
            } else if ($request->simtype == 'our') {
                $data = numberdetail::select("numberdetails.number", "numberdetails.type", 'numberdetails.id', 'numberdetails.channel_type', 'numberdetails.identity', 'numberdetails.passcode')
                    // ->where("remarks.user_agent_id", auth()->user()->id)
                    // ->where("numberdetails.id", $request->id)
                    ->where("numberdetails.call_center", $request->simtype)
                    ->whereIn("numberdetails.channel_type", ['TTF', 'ExpressDial', 'MWH', 'Ideacorp'])
                    ->where("numberdetails.status", 'Available')
                    ->latest()
                    ->get();
            } else {
                if (auth()->user()->region == 'Pak') {
                    $agent_code = auth()->user()->agent_code;

                    $data = \App\numberdetail::select('numberdetails.id', 'numberdetails.number', 'numberdetails.channel_type', 'numberdetails.type', 'numberdetails.identity', \DB::raw('count(*) as total'))
                        ->LeftJoin(
                            'choosen_numbers',
                            'choosen_numbers.number_id',
                            'numberdetails.id'
                        )
                        //  $data = numberdetail::select("numberdetails.number","numberdetails.type",'numberdetails.id','numberdetails.channel_type','numberdetails.identity')
                        // ->where("numberdetails.type", $request->simtype)
                        ->whereIn("numberdetails.channel_type", ['TTF', 'ExpressDial', 'MWH', 'Ideacorp'])
                        ->where("numberdetails.status", 'Available')
                        ->where("numberdetails.call_center", 'Default')
                        ->where('numberdetails.type', $request->simtype)
                        ->when($agent_code, function ($q) use ($agent_code) {
                            if ($agent_code == 'Salman') {
                                return $q->whereNotIn('numberdetails.identity', ['SLPJUN1ED', 'GLDJUN1ED', 'PLTJUN1ED']);
                            } else if ($agent_code == 'CC10') {
                                return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                                // return $q->where('numberdetails.identity', 'EidSpecial');
                            } else if ($agent_code == 'AAMT' || $agent_code == 'Aamt') {
                                return $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial', 'Sil1Dec22ED', 'NYJAN1GLD22', 'NYJAN1SILV22'])
                                    // return $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial', 'Sil1Dec22ED'])
                                    ->whereIn("numberdetails.channel_type", ['ExpressDial']);;
                                // return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                                // return $q->where('numberdetails.identity', 'EidSpecial');
                            } else if ($agent_code == 'CC4') {
                                // return $q->whereNotIn('numberdetails.identity', ['JulyGLD1ED23K', 'JulySILV1ED23K']);

                                // return $q->whereNotIn('numberdetails.identity', ['May24EIDSGLDE3','May24EIDSilvE3']);
                                // return $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial', 'Sil1Dec22ED'])
                                // ->whereIn("numberdetails.channel_type", ['ExpressDial']);;
                                // return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                                // return $q->where('numberdetails.identity', 'EidSpecial');
                            } else {
                                return $q->whereIn("numberdetails.channel_type", ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                            }
                        })
                        // ->where('numberdetails.region', 'Pak')
                        // ->latest()
                        // ->get();
                        ->groupBy('numberdetails.number')->orderBy('numberdetails.id', 'asc')->get();
                } else {

                    // return ;
                    $agent_code = auth()->user()->agent_code;
                    $data = \App\numberdetail::select('numberdetails.id', 'numberdetails.number', 'numberdetails.channel_type', 'numberdetails.type', 'numberdetails.identity', \DB::raw('count(*) as total'))
                        ->LeftJoin(
                            'choosen_numbers',
                            'choosen_numbers.number_id',
                            'numberdetails.id'
                        )
                        // $data = numberdetail::select("numberdetails.number","numberdetails.type",'numberdetails.id','numberdetails.channel_type','numberdetails.identity')
                        ->where("numberdetails.status", 'Available')
                        ->where("numberdetails.call_center", 'Default')
                        ->where("numberdetails.type", $request->simtype)
                        ->whereNull('numberdetails.region')
                        // ->where('numberdetails.identity', '=!', 'Pak')
                        // ->when($id)
                        ->when($agent_code, function ($q) use ($agent_code) {
                            if ($agent_code == 'Salman') {
                                return $q->whereNotIn('numberdetails.identity', ['SLPJUN1ED', 'GLDJUN1ED', 'PLTJUN1ED']);
                            } else if ($agent_code == 'CC10') {
                                return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                                // return $q->where('numberdetails.identity', 'EidSpecial');
                            } else if ($agent_code == 'AAMT' || $agent_code == 'Aamt') {
                                return $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial', 'Sil1Dec22ED', 'NYJAN1GLD22', 'NYJAN1SILV22'])
                                    // return $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial', 'Sil1Dec22ED'])
                                    ->whereIn("numberdetails.channel_type", ['ExpressDial']);;
                                // return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                                // return $q->where('numberdetails.identity', 'EidSpecial');
                            } else if ($agent_code == 'CC4' || $agent_code == 'CC8' || $agent_code == 'CC5') {
                                // return $q->whereNotIn('numberdetails.identity', ['JulyGLD1ED23K', 'JulySILV1ED23K']);
                                // return $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial', 'Sil1Dec22ED'])
                                // ->whereIn("numberdetails.channel_type", ['ExpressDial']);;
                                // return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                                // return $q->where('numberdetails.identity', 'EidSpecial');
                            } else {
                                return $q->whereIn("numberdetails.channel_type", ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                            }
                        })
                        // ->where('numberdetails.region', 'Pak')
                        // ->limit(10)
                        // ->latest()
                        // ->get();
                        ->groupBy('numberdetails.number')->orderBy('numberdetails.updated_at', 'desc')->get();
                }
            }
            // return $data;
            // $data = \App\numberdetail::select("numberdetails.number","numberdetails.type",'numberdetails.id','numberdetails.channel_type','numberdetails.identity')
            // ->LeftJoin(
            //     'channel_partners',
            //     'channel_partners.name',
            //     '=',
            //     'numberdetails.channel_type',
            //     // 'numberdetails.id'
            // )
            // ->where("numberdetails.status", 'Available')
            // // ->limit(8)
            // ->get();
            // $data = \App\User::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // onclick="BookNum('{{$item->id}}','{{route('ajaxRequest.BookNum')}}','{{$item->channel_type}}','{{$item->number}}','{{'home'}}')"
                    //    $btn = "<a href='javascript:void(0)' class='edit btn btn-primary btn-sm' onclick='BookNum('".trim($row['id'])."')'> View </a>";
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm" onclick="BookNum(' . trim($row['id']) . ',' . $row['number'] . ')">Book Number</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashboard.number', compact('q', 'slug', 'simtype'));
    }
    //
    public function LoadActiveFormMWH(Request $request)
    {
        // return "Zoom";
        // return $request;
        $number = $request->number;
        $channel_type = $request->channel_type;
        // $ = $request->channel_type;
        // $countries = \App\country_phone_code::all();
        $countries = \App\country_phone_code::all();
        $emirates = \App\emirate::all();
        if ($number == 'Manual') {
            $plans = \App\plan::select('plans.id', 'plans.plan_name')
                // ->Join(
                //     'numberdetails',
                //     'numberdetails.type',
                //     'plans.plan_category'
                // )
                // ->where('plans.status', '1')
                // ->where('numberdetails.number', $number)
                ->get();
        } else {
            $plans = \App\plan::select('plans.*')
                ->Join(
                    'numberdetails',
                    'numberdetails.type',
                    'plans.plan_category'
                )
                // ->where('plans.status','1')
                ->where('numberdetails.number', $number)->get();
        }
        // $plans = \App\plan::wherestatus('1')->get();
        return view('dashboard.add-activation-mwh', compact('countries', 'emirates', 'plans', 'number', 'channel_type'));
    }
    //
    public function ActivationFromPartner(Request $request)
    {
        // return $request;
        $validatedData = Validator::make($request->all(), [
            // 'plan_name' => 'required | string | unique',
            // 'plan_name' => 'required|string|unique:plans,plan_name',
            'activation_date' => 'required|date|before:tomorrow',
            'activation_sr_no' => 'required|numeric',
            // 'activation_service_order' => 'required|numeric',
            'activation_selected_no' => 'required|numeric|unique:activation_forms,activation_selected_no',
            // 'activation_sim_serial_no' => 'required',
            // 'activation_emirate_expiry' => 'required',
            // 'activation_sold' => 'required',
            // 'activation_sold_by' => 'required',
            // 'saler_id' => 'required',
            'cname' => 'required|string|max:100',
            'cnumber' => 'required',
            'emirate_id' => 'required',
            'emirates' => 'required',
            'gender' => 'required',
            'language' => 'required',
            'nation' => 'required',
            'plan_new' => 'required',
            'location' => 'required',
            'google_map' => 'required_if:location,google-map',
            'address' => 'required_if:location,location-address',
            // 'emirate_id_front' => 'required',
            // 'emirate_id_back' => 'required',
            // 'activation_screenshot' => 'required',
            'additional_document' => 'required',
            // 'documents.*' => 'required',
            // 'sr_photo.*' => 'required
        ]);
        if ($validatedData->fails()) {
            return response()->json(['error' => $validatedData->errors()->all()]);
        }
        $last = \App\Models\lead_sale::latest()->first();
        $getfirst = $last->id;
        $lead_date = Carbon::now()->toDateTimeString();
        $lead_no = auth()->user()->agent_code . '-' . $getfirst . '-' . Carbon::now()->format('M') . '-' . now()->year;


        $data = lead_sale::create([
            'customer_name' => $request->cname,
            'customer_number' => $request->cnumber,
            'area' => $request->area,
            'nationality' => $request->nation,
            // 'age' => $request->age,
            'sim_type' => $request->simtype,
            'gender' => $request->gender,
            'lead_type' => $request->lead_type,
            'channel_type' => $request->channel_type,
            'emirates' => $request->emirates,
            'emirate_num' => $request->emirate_number,
            'etisalat_number' => $request->etisalat_number,
            'emirate_id' => $request->emirate_id,
            'language' => $request->language,
            'share_with' => '0',
            'additional_document' => $request->additional_document,
            'saler_id' => auth()->user()->id,
            // main
            'selected_number' => $request->activation_selected_no,
            'select_plan' => $request->plan_new,
            'age' => '25',
            'contract_commitment' => 'default',
            'lead_no' => $lead_no,
            'remarks' => 'Lead By Guest Account',
            'status' => '1.02',
            'saler_name' => auth()->user()->name,
            'pay_status' => '0',
            'pay_charges' => '0',
            // 'device' => $request->status,
            'date_time' => $lead_date,
            'date_time_follow' => $lead_date,
            'appointment_from' => date('H:i:s', strtotime($lead_date)),
            'appointment_to' => date('H:i:s', strtotime($lead_date)),
            // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
            // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
            // 'commitment_period' => $request->status,
        ]);
        //
        $data2 = verification_form::create([
            'cust_id' => $data->id,
            'lead_no' => $data->lead_no,
            'lead_id' => $data->id,
            'customer_name' => $request->cname,
            'customer_number' => $request->cnumber,
            'nationality' => $request->nation,
            'age' => '25',
            'sim_type' => $request->sim_type,
            'gender' => $request->gender,
            'emirates' => $request->emirates,
            'emirate_number' => '000',
            // 'etisalat_number' => $request->status,
            'original_emirate_id' => $request->emirate_id,
            'language' => $request->language,
            'emirate_location' => $request->emirates,
            'additional_documents' => $request->additional_documents,
            'verify_agent' => auth()->user()->id,
            'dob' => '000',
            // main
            'selected_number' => $request->number,
            // 'selected_number' => $SelNumber,
            'select_plan' => $request->plan_new,
            // 'number_commitment' => $request->elife_makani_number,
            // 'contract_commitment' => $request->status,
            // 'contract_commitment' => $request->contract_comm_mnp,
            // 'lead_no' => 'Lead No',
            'remarks' => 'Guest Activation',
            // 'status' => '1.09',
            // 'saler_name' => 'Sale',
            'pay_status' => 'Free',
            // 'pay_charges' => $activation_rate_new,
            // 'device' => $request->status,
            // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
            // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
            // 'commitment_period' => $request->status,
        ]);
        //

        //
        $k = \App\activation_form::create([
            'cust_id' => $data->id,
            'lead_no' => $data->lead_no,
            'lead_id' => $data->id,
            'verification_id' => $data2->id,
            'customer_name' => $request->cname,
            'customer_number' => $request->cnumber,
            'age' => '25',
            'gender' => $request->gender,
            'nationality' => $request->nation,
            'language' => $request->language,
            'original_emirate_id' => $request->emirate_id,
            // 'emirate_number' => $request->customer_name,
            'additional_documents' => $request->additional_document,
            'sim_type' => $request->simtype,
            'number_commitment' => $request->numcommit,
            // 'contract_commitment' => $request->customer_name,
            'select_plan' => $request->plan_new,
            'benefits' => $request->customer_name,
            // 'benefits' => $request->customer_name,
            // 'total' => $request->customer_name,
            'emirate_location' => $request->emirates,
            'verify_agent' => $request->activation_sold_by,
            // 'remarks' => $request->customer_name,
            // 'pay_status' => $request->customer_name,
            // 'pay_charges' => $request->customer_name,
            'activation_date' => $request['activation_date'],
            'activation_sr_no' => $request->activation_sr_no,
            'activation_service_order' => $request->activation_sr_no,
            'pay_charges' => $request['activation_rate_new'],
            'pay_status' => $request['activation_charges_new'],
            'activation_selected_no' => $request['activation_selected_no'],
            'activation_sim_serial_no' => $request->activation_sim_serial_no,
            'activation_emirate_expiry' => Carbon::now(),
            'activation_sold_by' => auth()->user()->id,

            // 'emirate_id_front' => Session::get('front_image'),
            // 'emirate_id_back' => Session::get('back_image'),
            'emirate_id_front' => 'default.png',
            'emirate_id_back' => 'default.png',
            'activation_screenshot' => 'default.png',

            'saler_id' => auth()->user()->id,
            'channel_type' => $request->channel_type,
            // 'later' => $request->customer_name,
            // 'recording' => $request->customer_name,
            // 'assing_to' => $request->customer_name,
            // 'backoffice_by' => $request->customer_name,
            // 'cordination_by' => $request->customer_name,
            'date_time' => Carbon::now()->toDateTimeString(),
            'status' => '1.25',
            // 'selected_number' => $request->customer_name,
            // 'flexible_minutes' => $request->customer_name,
            // 'data' => $request->customer_name,
        ]);








        // Insert book records
        // activation_document::insert($book_records);
        // Session::forget('front_image');
        // Session::forget('back_image');
        // Session::forget('sr_no');
        $lead_data =  $d = lead_sale::findOrFail($data->id);
        $d->update([
            'status' => '1.25',
        ]);
        $d = verification_form::findOrFail($data2->id);
        $d->update([
            'status' => '1.25',
            'assing_to' => $request->assing_to,
            'cordination_by' => auth()->user()->id,
        ]);
        //
        // $number = \App\numberdetail::where('number',$request->number)->first();
        // $number->status = 'Active';
        // $number->save();
        //
        $count = numberdetail::select("numberdetails.id")
            ->where('numberdetails.number', $request->activation_selected_no)
            ->count();
        if ($count > 0) {
            $dn = numberdetail::select("numberdetails.id")
                ->where('numberdetails.number', $request->activation_selected_no)
                ->first();
            $k = numberdetail::findorfail($dn->id);
            $k->status = 'Active';
            $k->save();
            // CHANGE LATER
            $cn = choosen_number::select('choosen_numbers.id')
                ->where('number_id', $dn->id)
                ->where('user_id', auth()->user()->id)
                ->first();
            if ($cn) {
                $cnn = choosen_number::findorfail($cn->id);
                $cnn->status = '2';
                $cnn->save();
            }
            // CHANGE LATER
        }
        //


        // if(auth()->user()->role != 'sale')
        //
        notify()->success('New Activation has been created succesfully');
        return response()->json(['success' => 'Added new records, please wait meanwhile we are redirecting you....!!!']);
    }
    //
    public function LoadNumberMWH(Request $request)
    {
        // return $request->simtype;
        // foreach($data as $key => $d){
        //     // $dash[]['customers'] = array(
        //     //     'customer' . $key => array(
        //     //         'number' => $d->number,
        //     //     )
        //     // );
        //     $dash[] = array(
        //         'customers' => array(
        //            'customer' . $key => array(
        //                 'number' => $d->number,
        //             )
        //         )
        //     );
        //     // $dash[] =  $d->number;
        // }
        // return response()->json([
        //     $dash
        // ]);
        if (auth()->user()->role == 'ExpressGuest') {
            // $slug = 'ExpressDial';
            $mychannel = \App\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        } else {

            $slug = 'MWH';
        }
        $simtype = $request->simtype;
        $q = numberdetail::select("numberdetails.type")
            // ->where("remarks.user_agent_id", auth()->user()->id)
            // ->where("numberdetails.id", $request->id)
            ->where("numberdetails.status", 'Available')
            ->whereIn('numberdetails.channel_type', $mychannel)
            ->groupBy('numberdetails.type')
            ->get();
        if ($request->ajax()) {
            //

            if ($request->simtype == 'All') {
                $data = numberdetail::select("numberdetails.number", "numberdetails.type", 'numberdetails.id', 'numberdetails.channel_type', 'numberdetails.identity', 'numberdetails.passcode')
                    // ->where("remarks.user_agent_id", auth()->user()->id)
                    // ->where("numberdetails.id", $request->id)
                    // ->where("numberdetails.call_center", $request->simtype)
                    // ->wherein('numberdetails.channel_type', [$slug])
                    ->whereIn('numberdetails.channel_type', $mychannel)

                    ->where("numberdetails.status", 'Available')
                    ->latest()
                    ->get();
            } else if ($request->simtype == 'our') {
                $data = numberdetail::select("numberdetails.number", "numberdetails.type", 'numberdetails.id', 'numberdetails.channel_type', 'numberdetails.identity', 'numberdetails.passcode')
                    // ->where("remarks.user_agent_id", auth()->user()->id)
                    // ->where("numberdetails.id", $request->id)
                    ->where("numberdetails.call_center", $request->simtype)
                    // ->wherein('numberdetails.channel_type', [$slug])
                    ->whereIn('numberdetails.channel_type', $mychannel)

                    ->where("numberdetails.status", 'Available')
                    ->latest()
                    ->get();
            } else {

                $agent_code = auth()->user()->agent_code;
                $data = numberdetail::select("numberdetails.number", "numberdetails.type", 'numberdetails.id', 'numberdetails.channel_type', 'numberdetails.identity')
                    ->where("numberdetails.status", 'Available')
                    ->whereIn('numberdetails.channel_type', $mychannel)

                    // ->wherein('numberdetails.channel_type', [$slug])
                    // ->whereIn("numberdetails.channel_type", ['TTF','ExpressDial','MWH','Ideacorp'])
                    ->where("numberdetails.call_center", 'Default')
                    ->where("numberdetails.type", $request->simtype)
                    ->whereNull('numberdetails.region')
                    // ->where('numberdetails.identity', '=!', 'Pak')
                    // ->when($id)

                    // ->where('numberdetails.region', 'Pak')
                    // ->limit(10)
                    ->latest()
                    ->get();
            }
            // return $data;
            // $data = \App\numberdetail::select("numberdetails.number","numberdetails.type",'numberdetails.id','numberdetails.channel_type','numberdetails.identity')
            // ->LeftJoin(
            //     'channel_partners',
            //     'channel_partners.name',
            //     '=',
            //     'numberdetails.channel_type',
            //     // 'numberdetails.id'
            // )
            // ->where("numberdetails.status", 'Available')
            // // ->limit(8)
            // ->get();
            // $data = \App\User::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // onclick="BookNum('{{$item->id}}','{{route('ajaxRequest.BookNum')}}','{{$item->channel_type}}','{{$item->number}}','{{'home'}}')"
                    //    $btn = "<a href='javascript:void(0)' class='edit btn btn-primary btn-sm' onclick='BookNum('".trim($row['id'])."')'> View </a>";
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm" onclick="BookNum(' . trim($row['id']) . ',' . $row['number'] . ')">Book Number</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashboard.mwhnumber', compact('q', 'simtype', 'mychannel'));
    }
    //
    public function LoadMyNumber(Request $request)
    {
        // return "Zoom";
        $slug = 'TTF';
        $simtype = 'My';
        $q = numberdetail::select("numberdetails.type")
            // ->where("remarks.user_agent_id", auth()->user()->id)
            // ->where("numberdetails.id", $request->id)
            ->where("numberdetails.status", 'Available')
            ->wherein('numberdetails.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp'])
            ->groupBy('numberdetails.type')
            ->get();
        if ($request->ajax()) {
            //
            $data = numberdetail::select("numberdetails.*", "choosen_numbers.id as cid", "choosen_numbers.created_at as datetime")
                ->Join(
                    'choosen_numbers',
                    'choosen_numbers.number_id',
                    '=',
                    'numberdetails.id'
                )
                // ->where("","")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                ->where("choosen_numbers.status", 1)
                ->where("numberdetails.book_type", 0)
                ->whereIn("numberdetails.channel_type", ['TTF', 'ExpressDial', 'MWH', 'IdeaCorp'])
                ->where("choosen_numbers.user_id", auth()->user()->id)
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // onclick="BookNum('{{$item->id}}','{{route('ajaxRequest.BookNum')}}','{{$item->channel_type}}','{{$item->number}}','{{'home'}}')"
                    //    $btn = "<a href='javascript:void(0)' class='edit btn btn-primary btn-sm' onclick='BookNum('".trim($row['id'])."')'> View </a>";
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm" onclick="RevNum(' . trim($row['id']) . ',' . $row['number'] . ')">Revive Number</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashboard.number', compact('q', 'slug', 'simtype'));
    }
    public function LoadMWHNumber(Request $request)
    {
        // return "Zoom";
        // $slug = 'MWH';
        if (
            auth()->user()->role == 'ExpressGuest'
        ) {
            $mychannel = \App\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');

            // $slug = 'ExpressDial';
        } else {

            $slug = 'MWH';
        }
        $simtype = 'My';
        $q = numberdetail::select("numberdetails.type")
            // ->where("remarks.user_agent_id", auth()->user()->id)
            // ->where("numberdetails.id", $request->id)
            ->where("numberdetails.status", 'Available')
            ->whereIn('numberdetails.channel_type', $mychannel)
            // ->wherein('numberdetails.channel_type', ['MWH'])
            ->groupBy('numberdetails.type')
            ->get();
        if ($request->ajax()) {
            //
            $data = numberdetail::select("numberdetails.*", "choosen_numbers.id as cid", "choosen_numbers.created_at as datetime")
                ->Join(
                    'choosen_numbers',
                    'choosen_numbers.number_id',
                    '=',
                    'numberdetails.id'
                )
                // ->where("","")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                ->where("choosen_numbers.status", 1)
                ->where("numberdetails.book_type", 0)
                ->whereIn('numberdetails.channel_type', $mychannel)
                ->where("choosen_numbers.user_id", auth()->user()->id)
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // onclick="BookNum('{{$item->id}}','{{route('ajaxRequest.BookNum')}}','{{$item->channel_type}}','{{$item->number}}','{{'home'}}')"
                    //    $btn = "<a href='javascript:void(0)' class='edit btn btn-primary btn-sm' onclick='BookNum('".trim($row['id'])."')'> View </a>";
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm" onclick="RevNum(' . trim($row['id']) . ',' . $row['number'] . ')">UnBook Number</a>' . '<br>';
                    $btn .= '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm" onclick="MWHModal(' . trim($row['id']) . ",'"
                        . $row['number'] . "'" . ",'"
                        . $row['channel_type'] . "'" . ')">Active Number</a>';

                    return $btn;
                    // $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashboard.mwhnumber', compact('q', 'simtype', 'mychannel'));
    }
    //
    public function RevNumNew(Request $request)
    {
        // return $request;
        $d = numberdetail::findorfail($request->id);
        $d->status = 'Available';
        $d->save();
        $de = choosen_number::where('number_id', $request->id)->first();
        $de->delete();
        notify()->success('Number Succesfully Retrive');
        return 1;
        // }
        // notify()->error('Number Already Reserved');
        // return 0;

    }
    //
    public function LoadNumberForm(Request $request)
    {
        return view('dashboard.number');
    }
    public function BookNumLoad(Request $request)
    {
        // return $request;
        if (auth()->user()->agent_code == 'ARF') {
            $ct = 5;
        } elseif (auth()->user()->agent_code == 'CC3') {
            $ct = 3;
        } elseif (auth()->user()->role == 'MWHGUEST' || auth()->user()->role == 'ExpressGuest') {
            $ct = 5000;
        } else {
            $ct = 1;
        }
        // return json()->
        // return "2";
        // return ""
        $numberfinder = numberdetail::where('id', $request->id)->first();
        $data = numberdetail::select("numberdetails.id")
            // ->where("remarks.user_agent_id", auth()->user()->id)
            ->where("numberdetails.id", $request->id)
            ->where("numberdetails.status", 'Available')
            ->count();
        $dataNum = choosen_number::select("choosen_numbers.id")
            ->Join(
                'numberdetails',
                'numberdetails.id',
                '=',
                'choosen_numbers.number_id'
            )
            // ->where("remarks.user_agent_id", auth()->user()->id)
            ->where("choosen_numbers.user_id", auth()->user()->id)
            // ->where("numberdetails.status", 'Reserved')
            // ->where('numberdetails.book_type','0')
            ->whereIn('choosen_numbers.status', ['1', '2'])
            ->where("numberdetails.channel_type", $numberfinder->channel_type)
            ->whereDate('choosen_numbers.created_at', Carbon::today())
            ->count();
        if ($data < 1) {
            // notify()->error('Number Already Reserved');
            // return 0;
            return response()->json(['success' => "Number Already Reserved"]);
        } else if ($ct > $dataNum) {
            $d = numberdetail::findorfail($request->id);
            $d->status = 'Reserved';
            $d->save();
            $k = choosen_number::create([
                'number_id' => $request->id,
                'user_id' => auth()->user()->id,
                'status' => '1',
                'agent_group' => auth()->user()->agent_code,
                // 'ip_address' => Request::ip(),
                'date_time' => Carbon::now()->toDateTimeString(),
            ]);
            $log = choosen_number_log::create([
                // 'number'
                'number_id' => $request->id,
                'user_id' => auth()->user()->id,
                'agent_group' => auth()->user()->agent_code,
            ]);
            notify()->success('Number Succesfully Reserved');
            return 1;
        } else if ($dataNum >= $ct) {
            // "error";
            notify()->error('You Already Cross Limit, please revive old');
            return 2;
        }
    }
}
