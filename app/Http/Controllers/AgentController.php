<?php

namespace App\Http\Controllers;

use App\Models\audio_recording;
use App\Models\lead_sale;
use App\Models\remark;
use App\Models\chosen_addon;
use App\Models\lead_location;
use App\Models\numberdetail;
use App\Models\timing_duration;
use App\Models\verification_form;
use App\Models\choosen_number;
use App\Models\request_agent;
use App\Models\activation_form;
use App\Models\activation_document;
use App\Models\call_center;
use App\Models\channel_partner;
use App\Models\status_code;
use App\Models\User;
use App\Models\SMSFinalUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use App\Models\TargetAssignerUser;
// use Carbon\Carbon;
use Illuminate\Validation\Rule;
// use Illuminate\Support\Facades\Validator;


class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    // pendinguser
    public function pendinguser()
    {
        // return auth()->user()->role;
        if (auth()->user()->role == 'Admin') {
            // $plan = request_agent::where('status',0);
            $plan = request_agent::all();
        } else {
            $plan = request_agent::where('agent_code', auth()->user()->agent_code)->where('status', 0)->get();
        }
        return view('users.view-request-user-admin', compact('plan'));
    }
    public function approved(Request $request)
    {
        // return $request;
        if ($request->status == '1') {
            $data = request_agent::findorfail($request->userid);
            //
            $hashed_random_password = Str::random(8);

            $email = str_replace(' ', '-', $data->name);
            $final_email = $email . Str::random(2) . '@' . $data->agent_code . '.com';
            $d = User::where('email', $final_email)->first();
            if ($d) {
                return "User Already Exist, Please try again";
            }
            $data2 =   User::create([
                'name' => $data->name,
                'email' => $final_email,
                'secondary_email' => $data->email,
                'agent_code' => $data->agent_code,
                'role' => 'sale',
                'password' => Hash::make($hashed_random_password),
                'sl' => $hashed_random_password,
                'emirate' => 'Dubai',
                'profile' => 'default.png',
                'jobtype' => 'Fixed',
                'profile' => $data->profile,
                'cnic_front' => $data->cnic_front,
                'cnic_back' => $data->cnic_back,
                'phone' => $data->phone,
                // 'jobtype' => 'Fixed',
            ]);
            $data2->givePermissionTo('manage postpaid');
            $data2->givePermissionTo('manage sale');
            $data2->assignRole('sale');
            $data->status = 1;
            $data->save();
            // $to =
            $ntc = call_center::where('call_center_code', $data->agent_code)->first();
            if ($ntc) {
                $to = [
                    [
                        'email' => $ntc->notify_email, 'name' => 'Coordinator'
                    ]
                ];
            } else {
                $to = [
                    // [
                    //     'email' =>
                    //     'Aroojmalikam776@gmail.com',
                    //     'name' => 'Junaid',
                    // ],
                    [
                        'email' =>
                        'cc8alert@gmail.com',
                        'name' => 'CC8 Coordinator',
                    ]
                ];
            }
            $details = [
                'name' => $data2->name,
                'email' => $data2->email,
                'password' => $data2->sl,
                'agent_code' => $data2->agent_code,
            ];
            // \Mail::to($to)
            // ->cc(['salmanahmed334@gmail.com','isqintl@gmail.com'])
            // ->send(new \App\Models\Mail\InformToUser($details));

            //
            // return $details['lead_no'];
            // if ($details['agent_code'] == 'CC1') {
            //     $number = '923460854541,923487602506,923121337222';
            // }
            // elseif ($details['agent_code'] == 'CC2') {
            //     $number = '917827250250,923121337222';
            // }
            // elseif ($details['agent_code'] == 'CC3') {
            //     $number = '923312855103,923121337222';
            // }
            // elseif ($details['agent_code'] == 'CC4') {
            //     $number = '923121337222';
            // } elseif ($details['agent_code'] == 'CC5') {
            //     $number = '923333135199,971503658599,923121337222';
            // } elseif ($details['agent_code'] == 'CC6') {
            //     $number = '923058874773,923121337222';
            // } elseif ($details['agent_code'] == 'CC7') {
            //     $number = '923453627686,923121337222';
            // } elseif ($details['agent_code'] == 'CC8') {
            //     $number = '923352920757,971503658599,923121337222';
            // } elseif ($details['agent_code'] == 'CC9') {
            //     $number = '97143032128,923121337222';
            //     // $number = '923121337222';
            // } else {
            //     $number = '923121337222';
            // }
            $number = '923121337222,923123500256,923313678032';
            foreach (explode(',', $number) as $nm) {

                // $token = env('FACEBOOK_TOKEN');

                //

                $token = env('FACEBOOK_TOKEN');

                //

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://graph.facebook.com/v14.0/104929992273131/messages',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '{
                    "messaging_product": "whatsapp",
                    "to": "' . $nm . '",
                    "type": "template",
                    "template": {
                        "name": "account_generate",
                        "language": {
                            "code": "en_US"
                        },
                        "components": [
                            {
                                "type": "body",
                                "parameters": [
                                    {
                                        "type": "text",
                                        "text": "' . $details['name'] . '"
                                    },
                                    {
                                        "type": "text",
                                        "text": "' . $details['email'] . '"
                                    },
                                    {
                                        "type": "text",
                                        "text": "' . $details['password'] . '"
                                    },

                                ]
                            }
                        ]
                    }
                    }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $token
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                echo $response;
            }
            //
            return "1";
            // return "New Candidate ID has been generated, Please Contact IT Team";
            // $data2 =   User::create([
            //     'name' => $data->name,
            //     'email' => $data->email,
            //     'agent_code' => $data->agent_code,
            //     'role' => 'sale',
            //     'password' => Hash::make($data->password),
            //     'emirate' => 'Dubai',
            //     'profile' => 'default.png',
            //     'jobtype' => $data->jobtype,
            // ]);
            // $data2->givePermissionTo('manage postpaid');
            // $data2->givePermissionTo('manage sale');
            // $data2->assignRole('sale');
            // $data->status = 1;
            // $data->save();
            // return "1";
            // $data2 =
        } else {
            $data = request_agent::findorfail($request->userid);
            $data->status = '2';
            $data->save();
            return "2";
        }
    }
    //
    public function agent_lead()
    {
        // Route::get('mylead', function () {
        //
        $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name as status")
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
            ->whereIn(
                'lead_sales.status',
                ['1.05', '1.07', '1.08', '1.09', '1.10', '1.16', '1.17', '1.19', '1.20', '1.21', '1.03', '1.01', '1.15', '1.04', '1.06']
            )
            // ->where('lead_sales.status', '1.06')
            ->where('lead_sales.saler_id', auth()->user()->id)
            // ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->get();
        // $status = status_code::select('status_name','status_code')->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('agent.view-lead', compact('operation'));
        // });
    }
    public function ourlead()
    {
        // Route::get('mylead', function () {
        $myrole = auth()->user()->multi_agentcode;
        //
        $finalRole = auth()->user()->role;
        //
        $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->LeftJoin(
                'timing_durations',
                'timing_durations.lead_no',
                '=',
                'lead_sales.id'
            )
            ->Join(
                'users',
                'users.id',
                'lead_sales.saler_id'
            )
            ->Join(
                'status_codes',
                'status_codes.status_code',
                '=',
                'lead_sales.status'
            )
            ->whereIn(
                'lead_sales.status',
                ['1.05', '1.07', '1.08', '1.09', '1.10', '1.16', '1.17', '1.19', '1.21', '1.03', '1.01', '1.06']
            )
            ->when($myrole, function ($query) use ($myrole, $finalRole) {
                if ($finalRole == 'TeamLeader') {
                    return $query->where('users.teamleader', auth()->user()->id);
                } else if ($myrole == '1') {
                    // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                    //
                    return $query->where('users.agent_code', auth()->user()->agent_code);
                } else {
                    return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                }
                // else if($myrole == 'KHICordination'){
                //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                // }
                // else {
                //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                // }
            })
            // ->where('lead_sales.status', '1.06')
            // ->where('users.agent_code', auth()->user()->agent_code)
            ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)
            ->get();
        // $status = status_code::select('status_name','status_code')->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('agent.view-manager-lead', compact('operation'));
        // });
    }
    public function agent_lead_ajax(Request $request)
    {
        // Route::get('mylead', function () {
        //
        $status = $request->status;

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
            ->when($status, function ($query) use ($status) {
                //  whereIn('partner.id', ['204'])
                if ($status == '1.02') {
                    return $query->whereIn('lead_sales.status', ['1.02', '1.11']);
                } elseif ($status == 'reject') {
                    return $query->whereIn('lead_sales.status', ['1.04', '1.15']);
                } elseif ($status == '1.03') {
                    return $query->whereIn('lead_sales.status', ['1.03']);
                } elseif ($status == 'follow') {
                    return $query->whereIn('lead_sales.status', ['1.19', '1.20']);
                } elseif ($status == 'AssingToAct') {
                    return $query->whereIn('lead_sales.status', ['1.10']);
                } elseif ($status == '1.01') {
                    return $query->whereIn('lead_sales.status', ['1.01']);
                } elseif ($status == '1.06') {
                    return $query->whereIn('lead_sales.status', ['1.06']);
                } elseif ($status == '1.22') {
                    return $query->whereIn('lead_sales.status', ['1.22']);
                } else {
                    return $query->whereIn(
                        'lead_sales.status',
                        ['1.05', '1.07', '1.08', '1.09', '1.10', '1.16', '1.17', '1.19', '1.20', '1.21', '1.03', '1.01', '1.15', '1.04', '1.06', '1.13', '1.22']
                    );
                    // $query->where('vehicles.status', '!=', '8.07');
                }
            })
            // ->groupBy('lead_sales.id')
            // ->whereIn(
            //     'lead_sales.status',
            //     ['1.05', '1.07', '1.08', '1.09', '1.10', '1.16', '1.17', '1.19', '1.20', '1.21','1.03','1.01','1.15','1.04','1.06']
            // )
            // ->where('lead_sales.status', '1.06')
            // ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            // ->whereYear('lead_sales.created_at', Carbon::now()->year)
            ->where('lead_sales.saler_id', auth()->user()->id)
            ->orderby('lead_sales.updated_at', 'desc')
            // ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->get();
        // $status = status_code::select('status_name','status_code')->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('ajax.mylead', compact('operation'));
        // });
    }
    public function our_lead_ajax(Request $request)
    {
        // Route::get('mylead', function () {
        //
        $status = $request->status;
        $myrole = auth()->user()->multi_agentcode;
        $finalRole = auth()->user()->role;


        $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", "users.name as agent_name", "users.email as agent_email")
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
                'lead_sales.saler_id'
            )
            ->when($status, function ($query) use ($status) {
                //  whereIn('partner.id', ['204'])
                if ($status == '1.02') {
                    return $query->whereIn('lead_sales.status', ['1.02', '1.11']);
                } elseif ($status == 'reject') {
                    return $query->whereIn('lead_sales.status', ['1.04', '1.15']);
                } elseif ($status == '1.03') {
                    return $query->whereIn('lead_sales.status', ['1.03']);
                } elseif ($status == 'follow') {
                    return $query->whereIn('lead_sales.status', ['1.19', '1.20']);
                } elseif ($status == 'AssingToAct') {
                    return $query->whereIn('lead_sales.status', ['1.10']);
                } elseif ($status == '1.01') {
                    return $query->whereIn('lead_sales.status', ['1.01']);
                } elseif ($status == '1.22') {
                    return $query->whereIn('lead_sales.status', ['1.22']);
                } elseif ($status == '1.06') {
                    return $query->whereIn('lead_sales.status', ['1.06']);
                } elseif ($status == 'verified') {
                    return $query->whereIn(
                        'lead_sales.status',
                        ['1.05', '1.07', '1.08', '1.09', '1.10', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15',  '1.06', '1.22']
                    );
                } else {
                    return $query->whereIn(
                        'lead_sales.status',
                        ['1.05', '1.07', '1.08', '1.09', '1.10', '1.16', '1.17', '1.19', '1.20', '1.21', '1.03', '1.01', '1.15', '1.04', '1.06', '1.22']
                    );
                    // $query->where('vehicles.status', '!=', '8.07');
                }
            })
            // ->whereIn(
            //     'lead_sales.status',
            //     ['1.05', '1.07', '1.08', '1.09', '1.10', '1.16', '1.17', '1.19', '1.20', '1.21','1.03','1.01','1.15','1.04','1.06']
            // )
            ->when($myrole, function ($query) use ($myrole, $finalRole) {
                if ($finalRole == 'TeamLeader') {
                    return $query->where('users.teamleader', auth()->user()->id);
                } else if ($myrole == '1') {
                    // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                    //
                    return $query->where('users.agent_code', auth()->user()->agent_code);
                } else {
                    return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                }
                // else if($myrole == 'KHICordination'){
                //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                // }
                // else {
                //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                // }
            })
            // ->where('lead_sales.status', '1.06')
            // ->where('users.agent_code', auth()->user()->agent_code)
            // ->whereYear('lead_sales.created_at', Carbon::now()->year)

            ->orderby('lead_sales.updated_at', 'desc')
            // ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->get();
        // $status = status_code::select('status_name','status_code')->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('ajax.ourlead', compact('operation'));
        // });
    }
    public function mysharedlead()
    {
        // Route::get('mylead', function () {
        //
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
            // ->where('lead_sales.status', '1.06')
            ->where('lead_sales.share_with', auth()->user()->id)
            // ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.mylead', compact('operation'));
        // });
    }
    public function AgentSharedLead(Request $request)
    {
        // Route::get('mylead', function () {
        //
        // return $request->userid;
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
            // ->where('lead_sales.status', '1.06')
            ->where('lead_sales.saler_id', $request->userid)
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.mylead', compact('operation'));
        // });
    }
    public static function SharedCount($userid)
    {
        // Route::get('mylead', function () {
        //
        return $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name")
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
            // ->where('lead_sales.status', '1.06')
            ->where('lead_sales.saler_id', $userid)
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->get()->count();
        // $operation = lead_sale::wherestatus('1.01')->get();
        // return view('dashboard.mylead', compact('operation'));
        // });
    }
    public function CallCenterShared()
    {
        // Route::get('mylead', function () {
        //
        $operation = User::select('users.id', 'users.name')
            ->where('users.agent_code', auth()->user()->agent_code)
            ->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.our-shared-lead', compact('operation'));
        // });
    }
    public function agent_refollowup()
    {
        // Route::get('mylead', function () {
        //
        // return auth()->user()->role;
        if (auth()->user()->role == 'Cordination') {
            $id = 'Re Follow Up Lead';
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
                    'lead_sales.saler_id'
                )
                ->where('lead_sales.status', '1.19')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->get();
            return view('dashboard.view-all-lead', compact('operation', 'id'));
        } else {
            $id = 'Re Follow Up Lead';
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
                ->where('lead_sales.status', '1.19')
                ->where('lead_sales.saler_id', auth()->user()->id)
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->get();
            return view('dashboard.view-all-lead', compact('operation', 'id'));
        }
        // $operation = lead_sale::wherestatus('1.01')->get();

        // return view('dashboard.mylead', compact('operation'));
        // });
    }
    public function agent_non_verified()
    {
        // Route::get('mylead', function () {
        //
        // return auth()->user()->role;
        if (auth()->user()->role == 'Cordination') {
            $id = 'Non Verified Lead';
            $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
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
                    'lead_sales.saler_id'
                )
                ->where('lead_sales.status', '1.03')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->get();
            return view('dashboard.view-all-lead', compact('operation', 'id'));
        } else {
            $id = 'Non Verified Lead';
            $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
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
                ->LeftJoin(
                    'users',
                    'users.name',
                    'lead_sales.saler_id'
                )
                ->where('lead_sales.status', '1.03')
                ->where('lead_sales.saler_id', auth()->user()->id)
                ->get();
            return view('dashboard.view-all-lead', compact('operation', 'id'));
        }
        // $operation = lead_sale::wherestatus('1.01')->get();

        // return view('dashboard.mylead', compact('operation'));
        // });
    }
    //
    public function monthly_non_verified()
    {
        // Route::get('mylead', function () {
        //
        // return auth()->user()->role;

        $id = 'Non Verified Lead';
        $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
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
            ->LeftJoin(
                'users',
                'users.name',
                'lead_sales.saler_id'
            )
            ->where('lead_sales.status', '1.03')
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            // ->where('lead_sales.saler_id', auth()->user()->id)
            ->get();
        return view('dashboard.view-all-lead', compact('operation', 'id'));
        // $operation = lead_sale::wherestatus('1.01')->get();

        // return view('dashboard.mylead', compact('operation'));
        // });
    }
    //
    public function daily_non_verified()
    {
        // Route::get('mylead', function () {
        //
        // return auth()->user()->role;

        $id = 'Non Verified Lead';
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
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
            ->LeftJoin(
                'users',
                'users.name',
                'lead_sales.saler_id'
            )
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->where('lead_sales.status', '1.03')
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            // ->where('lead_sales.saler_id', auth()->user()->id)
            ->get();
        return view('dashboard.view-all-lead', compact('operation', 'id'));
        // $operation = lead_sale::wherestatus('1.01')->get();

        // return view('dashboard.mylead', compact('operation'));
        // });
    }
    //
    //
    public function daily_pending()
    {
        // Route::get('mylead', function () {
        //
        // return auth()->user()->role;

        $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to")
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
            ->whereIn(
                'lead_sales.status',
                [
                    '1.07'
                ]
            )
            ->groupBy('verification_forms.lead_no')
            ->whereDate('lead_sales.created_at', Carbon::today())
            // ->
            ->get();
        return view('dashboard.view-proceed-request-only', compact('operation'));

        // return view('dashboard.view-all-lead', compact('operation', 'id'));
        // $operation = lead_sale::wherestatus('1.01')->get();

        // return view('dashboard.mylead', compact('operation'));
        // });
    }
    public function monthly_followup()
    {
        // Route::get('mylead', function () {
        //
        // return auth()->user()->role;

        $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to")
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
            ->whereIn(
                'lead_sales.status',
                [
                    '1.19', '1.20', '1.21'
                ]
            )
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->groupBy('verification_forms.lead_no')
            ->get();
        return view('dashboard.view-proceed-request-only', compact('operation'));

        // return view('dashboard.view-all-lead', compact('operation', 'id'));
        // $operation = lead_sale::wherestatus('1.01')->get();

        // return view('dashboard.mylead', compact('operation'));
        // });
    }
    public function daily_followup()
    {
        // Route::get('mylead', function () {
        //
        // return auth()->user()->role;

        $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to")
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
            ->whereIn(
                'lead_sales.status',
                [
                    '1.19', '1.20', '1.21'
                ]
            )
            ->whereDate('lead_sales.updated_at', Carbon::today())
            ->groupBy('verification_forms.lead_no')
            ->get();
        return view('dashboard.view-proceed-request-only', compact('operation'));

        // return view('dashboard.view-all-lead', compact('operation', 'id'));
        // $operation = lead_sale::wherestatus('1.01')->get();

        // return view('dashboard.mylead', compact('operation'));
        // });
    }
    //
    public function verified_at_location(Request $request)
    {
        // return $request;
        if ($request->sim_type == 'New' && $request->reject_comment_new == '') {
            // return "s";
            // return $request;
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                // 'cname' => 'required|string|unique:lead_sales,customer_name',
                'cnumber' => 'required',
                'nation' => 'required',
                'age' => 'required|numeric',
                'sim_type' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                'plan_new' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
                'audio.*' => 'required',

            ]);
            if ($validator->fails()) {
                // return redirect()->back()
                //     ->withErrors($validator)
                //     ->withInput();
                return response()->json(['error' => $validator->errors()->all()]);
            }
            // if (empty($request->audio)) {
            //     // return "s";
            //     return response()->json(['error' => ['Please Attach Audio']]);
            //     // return response()->json(['error' => ]);
            //     // notify()->error('Please Submit Audio');
            //     // return redirect()->back()
            //     //     ->withInput();
            // }
            // if ($request->emirates != 'Sharjah') {

            //     $check_num = \App\Models\numberdetail::whereIn('number', [$request->selnumber])->whereIn('identity', ['SILSP2MW'])->first();
            //     if ($check_num) {
            //         // $channel = 'MWH';
            //         return response()->json(['error' => ['Documents' => ['Number only For Sharjah.']]], 200);
            //     }
            // }
            // if ($request->emirates != 'Sharjah' && $request->emirates != 'Ajman') {

            //     $check_num2 = \App\Models\numberdetail::whereIn('number', [$request->selnumber])->whereIn('identity', ['MWAJSH22JUL2-22'])->first();
            //     if ($check_num2) {
            //         // $channel = 'MWH';
            //         return response()->json(['error' => ['Documents' => ['Number only For Sharjah or Ajman.']]], 200);
            //     }
            // }
            // $planName = $request->plan_name;
            $planName = implode(',', $request->plan_new);
            $SelNumber = implode(",", $request->selnumber);
            $activation_charge = implode(",", $request->activation_charges_new);
            $activation_rate_new = implode(
                ",",
                $request->activation_rate_new
            );
            // return $request->emirate_id;
            // return $test = implode(",", $request->plan_new);
            $data = verification_form::create([
                'cust_id' => $request->lead_id,
                'lead_no' => $request->lead_id,
                'lead_id' => $request->lead_no,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_number' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'original_emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirate_location' => $request->emirates,
                'additional_documents' => $request->additional_documents,
                'verify_agent' => auth()->user()->id,
                // main
                'selected_number' => $SelNumber,
                'select_plan' => $planName,
                // 'contract_commitment' => $request->status,
                'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => 'Verified at location',
                'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $activation_charge,
                'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            foreach ($request->selnumber as $key => $val) {
                // return $val;
                $count = numberdetail::select("numberdetails.id")
                    ->where('numberdetails.number', $val)
                    ->count();
                if ($count > 0) {
                    $dn = numberdetail::select("numberdetails.id")
                        ->where('numberdetails.number', $val)
                        ->first();
                    //

                    //
                    $k = numberdetail::findorfail($dn->id);
                    $k->status = 'Hold';
                    $k->save();
                    // CHANGE LATER
                    $cn = choosen_number::select('choosen_numbers.id')
                        ->where('number_id', $dn->id)
                        ->first();
                    if ($cn) {
                        $cnn = choosen_number::findorfail($cn->id);
                        $cnn->status = '4';
                        $cnn->save();
                    }
                    // CHANGE LATER
                }
                // return $d->id;
                // return "number has been reserved";

            }
            // return $planName;

            // return $plan_name[];
            // $n = numberdetail::select("numberdetails.id")
            //     ->where('numberdetails.number', $val)
            //     ->first();
            // $k = numberdetail::findorfail($d->id);
            // $k->status = 'Reserved';
            // $k->save();
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            // foreach
            if ($lead_data->channel_type == 'TTF') {
                $channel_type = 'TTF';
            } else {
                $channel_type = 'ExpressDial';
                // $channel_type = $lead_data->channel_type;

            }

            // $check_num = \App\Models\numberdetail::where('number', $SelNumber)->where('identity', 'SILSP2MW')->first();
            // if ($check_num) {
            //     $channel_type = 'ExpressDial';
            // } else {
            //     $channel_type = 'ExpressDial';
            // }
            $d->update([
                'status' => '1.07',
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'area' => $request->area,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirates' => $request->emirates,
                'additional_document' => $request->additional_documents,
                // 'verify_agent' => auth()->user()->id,
                // main
                'selected_number' => $SelNumber,
                'select_plan' => $planName,
                // 'contract_commitment' => $request->status,
                'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_new,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $activation_charge,
                'pay_charges' => $activation_rate_new,
                // 'channel_type' => $channel_type,

            ]);
            $d = timing_duration::select('id')
                ->where('lead_no', $request->lead_id)
                ->first();
            $data  = timing_duration::findorfail($d->id);
            $data->lead_proceed_time = Carbon::now()->toDateTimeString();
            $data->verify_agent = auth()->user()->id;
            $data->save();


            $wp = \App\Models\User::select('agent_code')->where('users.id', $lead_data->saler_id)->first();
            $wp->agent_code;
            if ($wp->agent_code == 'CC3') {
                $wp_num = '919599020271';
            } else if ($wp->agent_code == 'CC2') {
                $wp_num = '917827250250';
            } else if ($wp->agent_code == 'CC8-POSTPAID') {
                $wp_num = '97148795753';
            } else if ($wp->agent_code == 'CC9') {
                $wp_num = '917838908219';
            }
            if (strpos($planName, ",") !== false) {
                // list($d, $l) = explode('.', $dm, 2);
                foreach (explode(',', $planName) as $key => $k) {
                    // $plan = \App\Models\plan::where('id',$k)->first();
                    $plan = \App\Models\plan::findorfail($k);
                    //  return $SelNumber[$key];
                    $plan_name[] = $plan->plan_name;
                    $data_gb[] = $plan->data;
                    // $plan_name = $plan->plan_name;
                    // $plan_name[] = $plan->plan_name;
                    // }
                    // foreach(explode(',', $SelNumber) as $k){
                    // $plan = \App\Models\plan::where('id',$k)->first();
                }
                foreach (explode(',', $SelNumber) as $key => $k) {
                    // $plan = \App\Models\plan::where('id',$k)->first();
                    //  $plan = \App\Models\plan::findorfail($k);
                    //  return $SelNumber[$key];
                    // $plan_name[] = $plan->plan_name;
                    // $data_gb[] = $plan->data;
                    // $plan_name = $plan->plan_name;
                    // $plan_name[] = $plan->plan_name;
                    // }
                    // foreach(explode(',', $SelNumber) as $k){
                    // $plan = \App\Models\plan::where('id',$k)->first();
                    $numberd = \App\Models\numberdetail::where('number', $k)->first();
                    $selected_number[] = $numberd->number;
                    $passcode[] = $numberd->passcode;
                }
                foreach (explode(',', $activation_charge) as $key => $k) {
                    // $plan = \App\Models\plan::where('id',$k)->first();
                    //  $plan = \App\Models\plan::findorfail($k);
                    //  return $SelNumber[$key];
                    // $plan_name[] = $plan->plan_name;
                    // $data_gb[] = $plan->data;
                    // $plan_name = $plan->plan_name;
                    // $plan_name[] = $plan->plan_name;
                    // }
                    // foreach(explode(',', $SelNumber) as $k){
                    // $plan = \App\Models\plan::where('id',$k)->first();
                    // $numberd = \App\Models\numberdetail::where('number', $k)->first();
                    // $selected_number[] = $numberd->number;
                    $ac[] = $k;
                }
                $tag = explode(',', $SelNumber);
                $count = count($tag);
                // $pay_status[] = $activation_rate_new[$key];
                // $plan_name['0'];
                // return $activation_charge;
                // $a = "https://api.whatsapp.com/send?text= *Verify at Location*  %0a Lead No: $lead_data->lead_no %0a Date: $lead_data->date_time %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a %0a %0a *Sim Type $lead_data->sim_type* %0a";
                // for ($i = 0; $i < $count; $i++) {
                //     $a .= "Number Selected: *$selected_number[$i]*  %0a PassCode = *$passcode[$i]* %0a Plan selected: *$plan_name[$i]* %0a  Activation: $ac[$i] %0a";
                // }
                // $a .= "%0a %0a %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: $lead_data->saler_name %0a";
                return response()->json(['success' => "Succesfully Verified"]);
            } else {
                // return $SelNumber;
                $plan = \App\Models\plan::findorfail($planName);
                $numberd = numberdetail::where('number', $SelNumber)->first();
                $plan_name = $plan->plan_name;
                $data_gb = $plan->data;
                $selected_number = $numberd->number;
                $passcode = $numberd->passcode;
                $pay_status = $activation_charge;
                // $a = "https://api.whatsapp.com/send?text= *Verify at Location*  %0a Lead No: $lead_data->lead_no %0a Date: $lead_data->date_time %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a %0a %0a *Sim Type $lead_data->sim_type* %0a Number Selected: *$selected_number*  %0a PassCode = *$passcode* %0a Plan selected: *$plan_name* %0a Activation: $pay_status  %0a %0a %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: $lead_data->saler_name %0a";
                // return response()->json(['success' => $a]);
                return response()->json(['success' => "Succesfully Verified"]);
            }

            //
            // Lead No.: 1234
            // Date:
            // New - Verified or Verify at Location
            // Customer Name: iftekhar
            // Customer Number 052-2221220

            // Sim Type: New or Mnp
            // Number Selected: 0503308580
            // Passcode : 000011
            // Plan selected: FE125 (Full plan)
            // Activation: Paid

            // Sim Type: New or Mnp
            // Number Selected: 0501234567
            // Passcode : 000022
            // Plan selected: FE125 (Full plan)
            // Activation: Paid

            // Gender: Male
            // Emirates location: Dubai
            // Nationality: Pakistan
            // Document: ID additional_documents
            // Language: English
            // Sales person: Salman

            // notify()->success('Sim Type New-Verified succesfully');
            // return redirect()->back()->withInput();
            // return redirect(route('verification.index'));
            // return "VIN";
            // return $d->customer_name;
            // $a = "https://api.whatsapp.com/send?text= ** Verified at Location **  %0a Lead No: $lead_data->lead_no %0a Date: $lead_data->date_time %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a %0a %0a ** Sim Type $lead_data->sim_type ** %0a Number Selected: ** $selected_number**  %0a PassCode = ** $passcode ** %0a Plan selected: ** $plan_name ** %0a Data : ** $data_gb GB ** %0a  Activation: $pay_status  %0a %0a %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: Salman %0a Verified";
            // return response()->json(['success' => $a]);


            // return $planName .'<br>'. $SelNumber . '<br>' . $activation_charge . '<br>' . $activation_rate_new;

        } else if ($request->sim_type == 'MNP' && $request->reject_comment_new == '' || $request->sim_type == 'Migration' && $request->reject_comment_new == '') {
            // return "s";
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                // 'cname' => 'required|string|unique:lead_sales,customer_name',
                // 'cnumber' => 'required|numeric',
                'nation' => 'required',
                'age' => 'required|numeric',
                'sim_type' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                'audio.*' => 'required',

                // 'plan_new' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
            ]);
            // $planName = $request->plan_name;
            // $planName = implode(',', $request->plan_new);
            // $SelNumber = implode(",", $request->selnumber);
            // $activation_charge = implode(",", $request->activation_charges_new);
            // $activation_rate_new = implode(",", $request->activation_rate_new);
            // return $request->emirate_id;
            // return $test = implode(",", $request->plan_new);
            $data = verification_form::create([
                'cust_id' => $request->lead_id,
                'lead_no' => $request->lead_id,
                'lead_id' => $request->lead_no,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_number' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'original_emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirate_location' => $request->emirates,
                'additional_documents' => $request->additional_documents,
                'verify_agent' => auth()->user()->id,
                // main
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->plan_mnp,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_mnp,
                'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $request->activation_charges_mnp,
                // 'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            //
            if ($validator->fails()) {
                // return redirect()->back()
                // ->withErrors($validator)
                // ->withInput();
                return response()->json(['error' => $validator->errors()->all()]);
            }
            //
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.07',
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'area' => $request->area,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirates' => $request->emirates,
                'additional_document' => $request->additional_documents,
                'select_plan' => $request->plan_mnp,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_mnp,
                'pay_status' => $request->activation_charges_mnp,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                // 'verify_agent' => auth()->user()->id,
                // main
                // 'selected_number' => $SelNumber,
                // 'select_plan' => $planName,
                // // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // // 'lead_no' => 'Lead No',
                // 'remarks' => $request->remarks_process_new,
                // // 'status' => '1.09',
                // // 'saler_name' => 'Sale',
                // 'pay_status' => $activation_charge,
                // 'pay_charges' => $activation_rate_new,
            ]);
            foreach ($request->audio as $key => $val) {
                if (!empty($request->audio)) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('audio')) {
                        // AzureCodeStart
                        $image2 = file_get_contents($file[$key]);
                        $originalFileName = time() . $file[$key]->getClientOriginalName();
                        $multi_filePath = 'audio' . '/' . $originalFileName;
                        \Storage::disk('azure')->put($multi_filePath, $image2);
                        // AzureCodeEnd
                        // LocalStorageCodeStart
                        $ext = date('d-m-Y-H-i');
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = $originalFileName;
                        $file[$key]->move('audio', $name);
                        $input['path'] = $name;
                        // LocalStorageCodeEnd
                    }
                    //     $data2 = meeting_std::create([
                    //         'meeting_id' => $meeting_id,
                    //         'meeting_title' => $request->course_title,
                    //         'std_id' => $val,
                    //         'status' => 1,
                    //     ]);
                    // } else {
                    //     echo "boom";
                    // }
                    $data = audio_recording::create([
                        // 'resource_name' => $request->resource_name,
                        'audio_file' => $name,
                        'username' => 'salman',
                        'lead_no' => $request->lead_id,
                        // 'teacher_id' => $request->teacher_id,
                        'status' => 1,
                    ]);
                }
            }
            $d = timing_duration::select('id')
                ->where('lead_no', $request->lead_id)
                ->first();
            $data  = timing_duration::findorfail($d->id);
            $data->lead_proceed_time = Carbon::now()->toDateTimeString();
            $data->verify_agent = auth()->user()->id;
            $data->save();

            // notify()->success('Verified succesfully');
            // return redirect()->back()->withInput();
            // return redirect(route('verification.index'));
            $a = "https://api.whatsapp.com/send?text= ** Verified at Location **  %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a Number Selected: $lead_data->selected_number %0a Plan selected: FE125 %0a Data : 4GB %0a  Activation: $lead_data->pay_status  %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: Salman %0a ";
            return response()->json(['success' => $a]);

            // return $planName .'<br>'. $SelNumber . '<br>' . $activation_charge . '<br>' . $activation_rate_new;

        } else if ($request->sim_type == 'Elife' && $request->reject_comment_new == '') {
            // return "s";
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                // 'cname' => 'required|string|unique:verification,customer_name',
                // 'cnumber' => 'required|numeric',
                'nation' => 'required',
                'age' => 'required|numeric',
                'sim_type' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                // 'plan_new' => 'required',
                // 'dob' => 'required',
                // 'audio.**' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
            ]);
            if ($validator->fails()) {
                // return redirect()->back()
                //     ->withErrors($validator)
                //     ->withInput();
                return response()->json(['error' => $validator->errors()->all()]);
            }
            // $planName = $request->plan_name;
            // $planName = implode(',', $request->plan_new);
            // $SelNumber = implode(",", $request->selnumber);
            // $activation_charge = implode(",", $request->activation_charges_new);
            // $activation_rate_new = implode(",", $request->activation_rate_new);
            // return $request->emirate_id;
            // return $test = implode(",", $request->plan_new);
            $data = verification_form::create([
                'cust_id' => $request->lead_id,
                'lead_no' => $request->lead_id,
                'lead_id' => $request->lead_no,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_number' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'original_emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirate_location' => $request->emirates,
                'additional_documents' => $request->additional_documents,
                'verify_agent' => auth()->user()->id,
                'dob' => $request->dob,
                // main
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->plan_elife,
                'number_commitment' => $request->elife_makani_number,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_elife,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $request->activation_charges_mnp,
                // 'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            //
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.07',
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirates' => $request->emirates,
                'additional_document' => $request->additional_documents,
                'dob' => $request->dob,
                // main
                'area' => $request->area,
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->plan_elife,
                'number_commitment' => $request->elife_makani_number,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_elife,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $request->activation_charges_mnp,
            ]);
            //
            $d = timing_duration::select('id')
                ->where('lead_no', $request->lead_id)
                ->first();
            $data  = timing_duration::findorfail($d->id);
            $data->lead_proceed_time = Carbon::now()->toDateTimeString();
            $data->verify_agent = auth()->user()->id;
            $data->save();
            foreach ($request->audio as $key => $val) {
                if (!empty($request->audio)) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('audio')) {
                        // AzureCodeStart
                        $image2 = file_get_contents($file[$key]);
                        $originalFileName = time() . $file[$key]->getClientOriginalName();
                        $multi_filePath = 'audio' . '/' . $originalFileName;
                        \Storage::disk('azure')->put($multi_filePath, $image2);
                        // AzureCodeEnd
                        // LocalStorageCodeStart
                        $ext = date('d-m-Y-H-i');
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = $originalFileName;
                        $file[$key]->move('audio', $name);
                        $input['path'] = $name;
                        // LocalStorageCodeEnd
                    }
                    //     $data2 = meeting_std::create([
                    //         'meeting_id' => $meeting_id,
                    //         'meeting_title' => $request->course_title,
                    //         'std_id' => $val,
                    //         'status' => 1,
                    //     ]);
                    // } else {
                    //     echo "boom";
                    // }
                    $data = audio_recording::create([
                        // 'resource_name' => $request->resource_name,
                        'audio_file' => $name,
                        'username' => 'salman',
                        'lead_no' => $request->lead_id,
                        // 'teacher_id' => $request->teacher_id,
                        'status' => 1,
                    ]);
                }
            }

            // notify()->success('Verified succesfully');
            // return redirect()->back()->withInput();
            // return redirect(route('verification.index'));

            $a = "https://api.whatsapp.com/send?text= ** Verified at Location **  %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a Number Selected: $lead_data->selected_number %0a Plan selected: FE125 %0a Data : 4GB %0a  Activation: $lead_data->pay_status  %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: Salman %0a";
            return response()->json(['success' => $a]);
            // return $planName .'<br>'. $SelNumber . '<br>' . $activation_charge . '<br>' . $activation_rate_new;

        } else if ($request->sim_type == 'HomeWifi' && $request->reject_comment_new == '') {
            // return "s";
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                // 'cname' => 'required|string|unique:verification,customer_name',
                // 'cnumber' => 'required|numeric',
                'nation' => 'required',
                'age' => 'required|numeric',
                'sim_type' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                // 'plan_new' => 'required',
                // 'dob' => 'required',
                // 'audio.**' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
            ]);
            if ($validator->fails()) {
                // return redirect()->back()
                //     ->withErrors($validator)
                //     ->withInput();
                return response()->json(['error' => $validator->errors()->all()]);
            }
            // $planName = $request->plan_name;
            // $planName = implode(',', $request->plan_new);
            // $SelNumber = implode(",", $request->selnumber);
            // $activation_charge = implode(",", $request->activation_charges_new);
            // $activation_rate_new = implode(",", $request->activation_rate_new);
            // return $request->emirate_id;
            // return $test = implode(",", $request->plan_new);
            $data = verification_form::create([
                'cust_id' => $request->lead_id,
                'lead_no' => $request->lead_id,
                'lead_id' => $request->lead_no,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_number' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'original_emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirate_location' => $request->emirates,
                'additional_documents' => $request->additional_documents,
                'verify_agent' => auth()->user()->id,
                'dob' => $request->dob,
                // main
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->plan_elife,
                'number_commitment' => $request->elife_makani_number,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_elife,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $request->activation_charges_mnp,
                // 'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            //
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.07',
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirates' => $request->emirates,
                'additional_document' => $request->additional_documents,
                'dob' => $request->dob,
                // main
                'area' => $request->area,
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->plan_elife,
                'number_commitment' => $request->elife_makani_number,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_elife,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $request->activation_charges_mnp,
            ]);
            //
            $d = timing_duration::select('id')
                ->where('lead_no', $request->lead_id)
                ->first();
            $data  = timing_duration::findorfail($d->id);
            $data->lead_proceed_time = Carbon::now()->toDateTimeString();
            $data->verify_agent = auth()->user()->id;
            $data->save();
            foreach ($request->audio as $key => $val) {
                if (!empty($request->audio)) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('audio')) {
                        $image2 = file_get_contents($file[$key]);
                        $originalFileName = time() . $file[$key]->getClientOriginalName();
                        $multi_filePath = 'audio' . '/' . $originalFileName;
                        \Storage::disk('azure')->put($multi_filePath, $image2);
                        // LocalStorageCodeStart
                        $ext = date('d-m-Y-H-i');
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = $originalFileName;

                        $file[$key]->move('audio', $name);
                        $input['path'] = $name;
                        // LocalStorageCodeEnd
                        // AzureCodeStart
                        // AzureCodeEnd
                    }
                    //     $data2 = meeting_std::create([
                    //         'meeting_id' => $meeting_id,
                    //         'meeting_title' => $request->course_title,
                    //         'std_id' => $val,
                    //         'status' => 1,
                    //     ]);
                    // } else {
                    //     echo "boom";
                    // }
                    $data = audio_recording::create([
                        // 'resource_name' => $request->resource_name,
                        'audio_file' => $name,
                        'username' => 'salman',
                        'lead_no' => $request->lead_id,
                        // 'teacher_id' => $request->teacher_id,
                        'status' => 1,
                    ]);
                }
            }

            // notify()->success('Verified succesfully');
            // return redirect()->back()->withInput();
            // return redirect(route('verification.index'));
            return response()->json(['success' => "Succesfully Verified"]);

            // $a = "https://api.whatsapp.com/send?text= ** Verified at Location **  %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a Number Selected: $lead_data->selected_number %0a Plan selected: FE125 %0a Data : 4GB %0a  Activation: $lead_data->pay_status  %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: Salman %0a";
            // return response()->json(['success' => $a]);
            // return $planName .'<br>'. $SelNumber . '<br>' . $activation_charge . '<br>' . $activation_rate_new;

        }
    }
    //
    public function verified_at_whatsapp(Request $request)
    {
        if ($request->sim_type == 'New' && $request->reject_comment_new == '') {
            // return "s";
            // return $request;
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                // 'cname' => 'required|string|unique:lead_sales,customer_name',
                'cnumber' => 'required',
                'nation' => 'required',
                'age' => 'required|numeric',
                'sim_type' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                'plan_new' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
                // 'whatsapp' => 'required',

            ]);
            if ($validator->fails()) {
                // return redirect()->back()
                //     ->withErrors($validator)
                //     ->withInput();
                return response()->json(['error' => $validator->errors()->all()]);
            }

            if ($file = $request->file('whatsapp')) {
                //convert image to base64
                $image = base64_encode(file_get_contents($request->file('whatsapp')));
                $image2 = file_get_contents($request->file('whatsapp'));
                // AzureCodeStart
                $originalFileName = time() . $file->getClientOriginalName();
                $multi_filePath = 'whatsapp' . '/' . $originalFileName;
                \Storage::disk('azure')->put($multi_filePath, $image2);
                // AzureCodeEnd
                //prepare request
                $mytime = Carbon::now();
                $ext =  $mytime->toDateTimeString();
                // $name = $ext . '-' . $file->getClientOriginalName();
                $whatsapp = $originalFileName;
                // $file->move('user-cnic', $name);
            } else {
                return response()->json(['error' => ['WhatsApp' => ['WhatsApp Document issue.']]], 200);
            }
            // if (empty($request->audio)) {
            //     // return "s";
            //     return response()->json(['error' => ['Please Attach Audio']]);
            //     // return response()->json(['error' => ]);
            //     // notify()->error('Please Submit Audio');
            //     // return redirect()->back()
            //     //     ->withInput();
            // }
            // if ($request->emirates != 'Sharjah') {

            //     $check_num = \App\Models\numberdetail::whereIn('number', [$request->selnumber])->whereIn('identity', ['SILSP2MW'])->first();
            //     if ($check_num) {
            //         // $channel = 'MWH';
            //         return response()->json(['error' => ['Documents' => ['Number only For Sharjah.']]], 200);
            //     }
            // }
            // if ($request->emirates != 'Sharjah' && $request->emirates != 'Ajman') {

            //     $check_num2 = \App\Models\numberdetail::whereIn('number', [$request->selnumber])->whereIn('identity', ['MWAJSH22JUL2-22'])->first();
            //     if ($check_num2) {
            //         // $channel = 'MWH';
            //         return response()->json(['error' => ['Documents' => ['Number only For Sharjah or Ajman.']]], 200);
            //     }
            // }
            // $planName = $request->plan_name;
            $planName = implode(',', $request->plan_new);
            $SelNumber = implode(",", $request->selnumber);
            $activation_charge = implode(",", $request->activation_charges_new);
            $activation_rate_new = implode(
                ",",
                $request->activation_rate_new
            );
            // return $request->emirate_id;
            // return $test = implode(",", $request->plan_new);
            $data = verification_form::create([
                'cust_id' => $request->lead_id,
                'lead_no' => $request->lead_id,
                'lead_id' => $request->lead_no,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_number' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'original_emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirate_location' => $request->emirates,
                'additional_documents' => $request->additional_documents,
                'verify_agent' => auth()->user()->id,
                // main
                'selected_number' => $SelNumber,
                'select_plan' => $planName,
                // 'contract_commitment' => $request->status,
                'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => 'Verified at location',
                'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $activation_charge,
                'pay_charges' => $activation_rate_new,
                'data' => $whatsapp,
                // 'device' => $request->status,
                // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            foreach ($request->selnumber as $key => $val) {
                // return $val;
                $count = numberdetail::select("numberdetails.id")
                    ->where('numberdetails.number', $val)
                    ->count();
                if ($count > 0) {
                    $dn = numberdetail::select("numberdetails.id")
                        ->where('numberdetails.number', $val)
                        ->first();
                    $k = numberdetail::findorfail($dn->id);
                    $k->status = 'Hold';
                    $k->save();
                    // CHANGE LATER
                    $cn = choosen_number::select('choosen_numbers.id')
                        ->where('number_id', $dn->id)
                        ->first();
                    if ($cn) {
                        $cnn = choosen_number::findorfail($cn->id);
                        $cnn->status = '4';
                        $cnn->save();
                    }
                    // CHANGE LATER
                }
                // return $d->id;
                // return "number has been reserved";

            }
            // return $planName;

            // return $plan_name[];
            // $n = numberdetail::select("numberdetails.id")
            //     ->where('numberdetails.number', $val)
            //     ->first();
            // $k = numberdetail::findorfail($d->id);
            // $k->status = 'Reserved';
            // $k->save();
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            // foreach
            if ($lead_data->channel_type == 'TTF') {
                $channel_type = 'ExpressDial';
            } else {
                // $channel_type = $lead_data->channel_type;
                $channel_type = 'ExpressDial';
            }
            $check_num = \App\Models\numberdetail::where('number', $SelNumber)->where('identity', 'SILSP2MW')->first();
            if ($check_num) {
                $channel_type = 'ExpressDial';
            } else {
                $channel_type = 'ExpressDial';
            }
            $d->update([
                'status' => '1.07',
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'area' => $request->area,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirates' => $request->emirates,
                'additional_document' => $request->additional_documents,
                // 'verify_agent' => auth()->user()->id,
                // main
                'selected_number' => $SelNumber,
                'select_plan' => $planName,
                // 'contract_commitment' => $request->status,
                'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_new,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $activation_charge,
                'pay_charges' => $activation_rate_new,
                'channel_type' => $channel_type,

            ]);
            $d = timing_duration::select('id')
                ->where('lead_no', $request->lead_id)
                ->first();
            $data  = timing_duration::findorfail($d->id);
            $data->lead_proceed_time = Carbon::now()->toDateTimeString();
            $data->verify_agent = auth()->user()->id;
            $data->save();


            $wp = \App\Models\User::select('agent_code')->where('users.id', $lead_data->saler_id)->first();
            $wp->agent_code;
            if ($wp->agent_code == 'CC3') {
                $wp_num = '919599020271';
            } else if ($wp->agent_code == 'CC2') {
                $wp_num = '917827250250';
            } else if ($wp->agent_code == 'CC8-POSTPAID') {
                $wp_num = '97148795753';
            } else if ($wp->agent_code == 'CC9') {
                $wp_num = '917838908219';
            }
            if (strpos($planName, ",") !== false) {
                // list($d, $l) = explode('.', $dm, 2);
                foreach (explode(',', $planName) as $key => $k) {
                    // $plan = \App\Models\plan::where('id',$k)->first();
                    $plan = \App\Models\plan::findorfail($k);
                    //  return $SelNumber[$key];
                    $plan_name[] = $plan->plan_name;
                    $data_gb[] = $plan->data;
                    // $plan_name = $plan->plan_name;
                    // $plan_name[] = $plan->plan_name;
                    // }
                    // foreach(explode(',', $SelNumber) as $k){
                    // $plan = \App\Models\plan::where('id',$k)->first();
                }
                foreach (explode(',', $SelNumber) as $key => $k) {
                    // $plan = \App\Models\plan::where('id',$k)->first();
                    //  $plan = \App\Models\plan::findorfail($k);
                    //  return $SelNumber[$key];
                    // $plan_name[] = $plan->plan_name;
                    // $data_gb[] = $plan->data;
                    // $plan_name = $plan->plan_name;
                    // $plan_name[] = $plan->plan_name;
                    // }
                    // foreach(explode(',', $SelNumber) as $k){
                    // $plan = \App\Models\plan::where('id',$k)->first();
                    $numberd = \App\Models\numberdetail::where('number', $k)->first();
                    $selected_number[] = $numberd->number;
                    $passcode[] = $numberd->passcode;
                }
                foreach (explode(',', $activation_charge) as $key => $k) {
                    // $plan = \App\Models\plan::where('id',$k)->first();
                    //  $plan = \App\Models\plan::findorfail($k);
                    //  return $SelNumber[$key];
                    // $plan_name[] = $plan->plan_name;
                    // $data_gb[] = $plan->data;
                    // $plan_name = $plan->plan_name;
                    // $plan_name[] = $plan->plan_name;
                    // }
                    // foreach(explode(',', $SelNumber) as $k){
                    // $plan = \App\Models\plan::where('id',$k)->first();
                    // $numberd = \App\Models\numberdetail::where('number', $k)->first();
                    // $selected_number[] = $numberd->number;
                    $ac[] = $k;
                }
                $tag = explode(',', $SelNumber);
                $count = count($tag);
                // $pay_status[] = $activation_rate_new[$key];
                // $plan_name['0'];
                // return $activation_charge;
                // $a = "https://api.whatsapp.com/send?text= *Verify at Location*  %0a Lead No: $lead_data->lead_no %0a Date: $lead_data->date_time %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a %0a %0a *Sim Type $lead_data->sim_type* %0a";
                // for ($i = 0; $i < $count; $i++) {
                //     $a .= "Number Selected: *$selected_number[$i]*  %0a PassCode = *$passcode[$i]* %0a Plan selected: *$plan_name[$i]* %0a  Activation: $ac[$i] %0a";
                // }
                // $a .= "%0a %0a %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: $lead_data->saler_name %0a";
                return response()->json(['success' => "Succesfully Verified"]);
            } else {
                // return $SelNumber;
                $plan = \App\Models\plan::findorfail($planName);
                $numberd = numberdetail::where('number', $SelNumber)->first();
                $plan_name = $plan->plan_name;
                $data_gb = $plan->data;
                $selected_number = $numberd->number;
                $passcode = $numberd->passcode;
                $pay_status = $activation_charge;
                // $a = "https://api.whatsapp.com/send?text= *Verify at Location*  %0a Lead No: $lead_data->lead_no %0a Date: $lead_data->date_time %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a %0a %0a *Sim Type $lead_data->sim_type* %0a Number Selected: *$selected_number*  %0a PassCode = *$passcode* %0a Plan selected: *$plan_name* %0a Activation: $pay_status  %0a %0a %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: $lead_data->saler_name %0a";
                // return response()->json(['success' => $a]);
                return response()->json(['success' => "Succesfully Verified"]);
            }

            //
            // Lead No.: 1234
            // Date:
            // New - Verified or Verify at Location
            // Customer Name: iftekhar
            // Customer Number 052-2221220

            // Sim Type: New or Mnp
            // Number Selected: 0503308580
            // Passcode : 000011
            // Plan selected: FE125 (Full plan)
            // Activation: Paid

            // Sim Type: New or Mnp
            // Number Selected: 0501234567
            // Passcode : 000022
            // Plan selected: FE125 (Full plan)
            // Activation: Paid

            // Gender: Male
            // Emirates location: Dubai
            // Nationality: Pakistan
            // Document: ID additional_documents
            // Language: English
            // Sales person: Salman

            // notify()->success('Sim Type New-Verified succesfully');
            // return redirect()->back()->withInput();
            // return redirect(route('verification.index'));
            // return "VIN";
            // return $d->customer_name;
            // $a = "https://api.whatsapp.com/send?text= ** Verified at Location **  %0a Lead No: $lead_data->lead_no %0a Date: $lead_data->date_time %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a %0a %0a ** Sim Type $lead_data->sim_type ** %0a Number Selected: ** $selected_number**  %0a PassCode = ** $passcode ** %0a Plan selected: ** $plan_name ** %0a Data : ** $data_gb GB ** %0a  Activation: $pay_status  %0a %0a %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: Salman %0a Verified";
            // return response()->json(['success' => $a]);


            // return $planName .'<br>'. $SelNumber . '<br>' . $activation_charge . '<br>' . $activation_rate_new;

        } else if ($request->sim_type == 'MNP' && $request->reject_comment_new == '' || $request->sim_type == 'Migration' && $request->reject_comment_new == '') {
            // return "s";
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                // 'cname' => 'required|string|unique:lead_sales,customer_name',
                // 'cnumber' => 'required|numeric',
                'nation' => 'required',
                'age' => 'required|numeric',
                'sim_type' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                'audio.*' => 'required',

                // 'plan_new' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
            ]);
            // $planName = $request->plan_name;
            // $planName = implode(',', $request->plan_new);
            // $SelNumber = implode(",", $request->selnumber);
            // $activation_charge = implode(",", $request->activation_charges_new);
            // $activation_rate_new = implode(",", $request->activation_rate_new);
            // return $request->emirate_id;
            // return $test = implode(",", $request->plan_new);
            $data = verification_form::create([
                'cust_id' => $request->lead_id,
                'lead_no' => $request->lead_id,
                'lead_id' => $request->lead_no,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_number' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'original_emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirate_location' => $request->emirates,
                'additional_documents' => $request->additional_documents,
                'verify_agent' => auth()->user()->id,
                // main
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->plan_mnp,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_mnp,
                'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $request->activation_charges_mnp,
                // 'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            //
            if ($validator->fails()) {
                // return redirect()->back()
                // ->withErrors($validator)
                // ->withInput();
                return response()->json(['error' => $validator->errors()->all()]);
            }
            //
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.07',
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'area' => $request->area,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirates' => $request->emirates,
                'additional_document' => $request->additional_documents,
                'select_plan' => $request->plan_mnp,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_mnp,
                'pay_status' => $request->activation_charges_mnp,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                // 'verify_agent' => auth()->user()->id,
                // main
                // 'selected_number' => $SelNumber,
                // 'select_plan' => $planName,
                // // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // // 'lead_no' => 'Lead No',
                // 'remarks' => $request->remarks_process_new,
                // // 'status' => '1.09',
                // // 'saler_name' => 'Sale',
                // 'pay_status' => $activation_charge,
                // 'pay_charges' => $activation_rate_new,
            ]);
            foreach ($request->audio as $key => $val) {
                if (!empty($request->audio)) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('audio')) {
                        // AzureCodeStart
                        $image2 = file_get_contents($file[$key]);
                        $originalFileName = time() . $file[$key]->getClientOriginalName();
                        $multi_filePath = 'audio' . '/' . $originalFileName;
                        \Storage::disk('azure')->put($multi_filePath, $image2);
                        // AzureCodeEnd
                        // LocalStorageCodeStart
                        $ext = date('d-m-Y-H-i');
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = $originalFileName;
                        $file[$key]->move('audio', $name);
                        $input['path'] = $name;
                        // LocalStorageCodeEnd
                    }
                    //     $data2 = meeting_std::create([
                    //         'meeting_id' => $meeting_id,
                    //         'meeting_title' => $request->course_title,
                    //         'std_id' => $val,
                    //         'status' => 1,
                    //     ]);
                    // } else {
                    //     echo "boom";
                    // }
                    $data = audio_recording::create([
                        // 'resource_name' => $request->resource_name,
                        'audio_file' => $name,
                        'username' => 'salman',
                        'lead_no' => $request->lead_id,
                        // 'teacher_id' => $request->teacher_id,
                        'status' => 1,
                    ]);
                }
            }
            $d = timing_duration::select('id')
                ->where('lead_no', $request->lead_id)
                ->first();
            $data  = timing_duration::findorfail($d->id);
            $data->lead_proceed_time = Carbon::now()->toDateTimeString();
            $data->verify_agent = auth()->user()->id;
            $data->save();

            // notify()->success('Verified succesfully');
            // return redirect()->back()->withInput();
            // return redirect(route('verification.index'));
            $a = "https://api.whatsapp.com/send?text= ** Verified at Location **  %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a Number Selected: $lead_data->selected_number %0a Plan selected: FE125 %0a Data : 4GB %0a  Activation: $lead_data->pay_status  %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: Salman %0a ";
            return response()->json(['success' => $a]);

            // return $planName .'<br>'. $SelNumber . '<br>' . $activation_charge . '<br>' . $activation_rate_new;

        } else if ($request->sim_type == 'Elife' && $request->reject_comment_new == '') {
            // return "s";
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                // 'cname' => 'required|string|unique:verification,customer_name',
                // 'cnumber' => 'required|numeric',
                'nation' => 'required',
                'age' => 'required|numeric',
                'sim_type' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                // 'plan_new' => 'required',
                // 'dob' => 'required',
                // 'audio.**' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
            ]);
            if ($validator->fails()) {
                // return redirect()->back()
                //     ->withErrors($validator)
                //     ->withInput();
                return response()->json(['error' => $validator->errors()->all()]);
            }
            // $planName = $request->plan_name;
            // $planName = implode(',', $request->plan_new);
            // $SelNumber = implode(",", $request->selnumber);
            // $activation_charge = implode(",", $request->activation_charges_new);
            // $activation_rate_new = implode(",", $request->activation_rate_new);
            // return $request->emirate_id;
            // return $test = implode(",", $request->plan_new);
            $data = verification_form::create([
                'cust_id' => $request->lead_id,
                'lead_no' => $request->lead_id,
                'lead_id' => $request->lead_no,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_number' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'original_emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirate_location' => $request->emirates,
                'additional_documents' => $request->additional_documents,
                'verify_agent' => auth()->user()->id,
                'dob' => $request->dob,
                // main
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->plan_elife,
                'number_commitment' => $request->elife_makani_number,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_elife,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $request->activation_charges_mnp,
                // 'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            //
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.07',
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirates' => $request->emirates,
                'additional_document' => $request->additional_documents,
                'dob' => $request->dob,
                // main
                'area' => $request->area,
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->plan_elife,
                'number_commitment' => $request->elife_makani_number,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_elife,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $request->activation_charges_mnp,
            ]);
            //
            $d = timing_duration::select('id')
                ->where('lead_no', $request->lead_id)
                ->first();
            $data  = timing_duration::findorfail($d->id);
            $data->lead_proceed_time = Carbon::now()->toDateTimeString();
            $data->verify_agent = auth()->user()->id;
            $data->save();
            foreach ($request->audio as $key => $val) {
                if (!empty($request->audio)) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('audio')) {
                        // AzureCodeStart
                        $image2 = file_get_contents($file[$key]);
                        $originalFileName = time() . $file[$key]->getClientOriginalName();
                        $multi_filePath = 'audio' . '/' . $originalFileName;
                        \Storage::disk('azure')->put($multi_filePath, $image2);
                        // AzureCodeEnd
                        // LocalStorageCodeStart
                        $ext = date('d-m-Y-H-i');
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = $originalFileName;
                        $file[$key]->move('audio', $name);
                        $input['path'] = $name;
                        // LocalStorageCodeEnd
                    }
                    //     $data2 = meeting_std::create([
                    //         'meeting_id' => $meeting_id,
                    //         'meeting_title' => $request->course_title,
                    //         'std_id' => $val,
                    //         'status' => 1,
                    //     ]);
                    // } else {
                    //     echo "boom";
                    // }
                    $data = audio_recording::create([
                        // 'resource_name' => $request->resource_name,
                        'audio_file' => $name,
                        'username' => 'salman',
                        'lead_no' => $request->lead_id,
                        // 'teacher_id' => $request->teacher_id,
                        'status' => 1,
                    ]);
                }
            }

            // notify()->success('Verified succesfully');
            // return redirect()->back()->withInput();
            // return redirect(route('verification.index'));

            $a = "https://api.whatsapp.com/send?text= ** Verified at Location **  %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a Number Selected: $lead_data->selected_number %0a Plan selected: FE125 %0a Data : 4GB %0a  Activation: $lead_data->pay_status  %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: Salman %0a";
            return response()->json(['success' => $a]);
            // return $planName .'<br>'. $SelNumber . '<br>' . $activation_charge . '<br>' . $activation_rate_new;

        } else if ($request->sim_type == 'HomeWifi' && $request->reject_comment_new == '') {
            // return "s";
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                // 'cname' => 'required|string|unique:verification,customer_name',
                // 'cnumber' => 'required|numeric',
                'nation' => 'required',
                'age' => 'required|numeric',
                'sim_type' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                // 'plan_new' => 'required',
                // 'dob' => 'required',
                // 'audio.**' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
            ]);
            if ($validator->fails()) {
                // return redirect()->back()
                //     ->withErrors($validator)
                //     ->withInput();
                return response()->json(['error' => $validator->errors()->all()]);
            }

            if ($file = $request->file('whatsapp')) {
                //convert image to base64
                $image = base64_encode(file_get_contents($request->file('whatsapp')));
                $image2 = file_get_contents($request->file('whatsapp'));
                // AzureCodeStart
                $originalFileName = time() . $file->getClientOriginalName();
                $multi_filePath = 'whatsapp' . '/' . $originalFileName;
                \Storage::disk('azure')->put($multi_filePath, $image2);
                // AzureCodeEnd
                //prepare request
                $mytime = Carbon::now();
                $ext =  $mytime->toDateTimeString();
                // $name = $ext . '-' . $file->getClientOriginalName();
                $whatsapp = $originalFileName;
                // $file->move('user-cnic', $name);
            } else {
                return response()->json(['error' => ['WhatsApp' => ['WhatsApp Document issue.']]], 200);
            }
            // $planName = $request->plan_name;
            // $planName = implode(',', $request->plan_new);
            // $SelNumber = implode(",", $request->selnumber);
            // $activation_charge = implode(",", $request->activation_charges_new);
            // $activation_rate_new = implode(",", $request->activation_rate_new);
            // return $request->emirate_id;
            // return $test = implode(",", $request->plan_new);
            $data = verification_form::create([
                'cust_id' => $request->lead_id,
                'lead_no' => $request->lead_id,
                'lead_id' => $request->lead_no,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_number' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'original_emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirate_location' => $request->emirates,
                'additional_documents' => $request->additional_documents,
                'verify_agent' => auth()->user()->id,
                'dob' => $request->dob,
                // main
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->plan_elife,
                'number_commitment' => $request->elife_makani_number,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_elife,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $request->activation_charges_mnp,
                'data' => $whatsapp,

                // 'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            //
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.07',
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirates' => $request->emirates,
                'additional_document' => $request->additional_documents,
                'dob' => $request->dob,
                // main
                'area' => $request->area,
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->plan_elife,
                'number_commitment' => $request->elife_makani_number,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_elife,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $request->activation_charges_mnp,
            ]);
            //
            $d = timing_duration::select('id')
                ->where('lead_no', $request->lead_id)
                ->first();
            $data  = timing_duration::findorfail($d->id);
            $data->lead_proceed_time = Carbon::now()->toDateTimeString();
            $data->verify_agent = auth()->user()->id;
            $data->save();
            foreach ($request->audio as $key => $val) {
                if (!empty($request->audio)) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('audio')) {
                        // AzureCodeStart
                        $image2 = file_get_contents($file[$key]);
                        $originalFileName = time() . $file[$key]->getClientOriginalName();
                        $multi_filePath = 'audio' . '/' . $originalFileName;
                        \Storage::disk('azure')->put($multi_filePath, $image2);
                        // AzureCodeEnd
                        // LocalStorageCodeStart
                        $ext = date('d-m-Y-H-i');
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = $originalFileName;
                        $file[$key]->move('audio', $name);
                        $input['path'] = $name;
                        // LocalStorageCodeEnd
                    }
                    //     $data2 = meeting_std::create([
                    //         'meeting_id' => $meeting_id,
                    //         'meeting_title' => $request->course_title,
                    //         'std_id' => $val,
                    //         'status' => 1,
                    //     ]);
                    // } else {
                    //     echo "boom";
                    // }
                    $data = audio_recording::create([
                        // 'resource_name' => $request->resource_name,
                        'audio_file' => $name,
                        'username' => 'salman',
                        'lead_no' => $request->lead_id,
                        // 'teacher_id' => $request->teacher_id,
                        'status' => 1,
                    ]);
                }
            }

            // notify()->success('Verified succesfully');
            // return redirect()->back()->withInput();
            // return redirect(route('verification.index'));

            $a = "https://api.whatsapp.com/send?text= ** Verified at Location **  %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a Number Selected: $lead_data->selected_number %0a Plan selected: FE125 %0a Data : 4GB %0a  Activation: $lead_data->pay_status  %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: Salman %0a";
            return response()->json(['success' => $a]);
            // return $planName .'<br>'. $SelNumber . '<br>' . $activation_charge . '<br>' . $activation_rate_new;

        }
    }
    // public function reprocess_CordinationLead($id){
    //     return $id;
    // }
    public function group_refollowup()
    {
        // Route::get('mylead', function () {
        //
        $id = 'Re Follow Up Lead';
        $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
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
            ->where('lead_sales.status', '1.20')
            ->where('users.agent_code', auth()->user()->agent_code)
            ->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.manage-cordination-lead', compact('operation', 'id'));

        // return view('dashboard.view-all-lead', compact('operation', 'id'));

        // return view('dashboard.mylead', compact('operation'));
        // });
    }
    public function active_lead($id)
    {
        $data = activation_form::select('activation_forms.*', 'audio_recordings.audio_file', 'plans.plan_name')
            ->LeftJoin('users', 'users.id', 'activation_forms.saler_id')
            ->LeftJoin('plans', 'plans.id', 'activation_forms.select_plan')
            ->LeftJoin('audio_recordings', 'audio_recordings.lead_no', 'activation_forms.lead_id')
            ->where('activation_forms.status', '1.02')
            ->where('activation_forms.lead_id', $id)
            ->first();
        $remarks =
            remark::select("remarks.*")
            // ->where("remarks.user_agent_id", auth()->user()->id)
            ->where("remarks.lead_id", $id)
            ->get();
        $documents = activation_document::where('lead_id', $id)->get();
        $users = \App\Models\User::whererole('sale')->get();
        return view('dashboard.view-active-lead', compact('data', 'users', 'remarks', 'documents'));
    }
    public function viewlead($id)
    {
        // Route::get('view-lead/{id}', function ($id) {
        // return $id;
        // return auth()->user()-role;
        if (auth()->user()->role == 'sale' || auth()->user()->role == 'NumberAdmin') {
            $countries = \App\Models\country_phone_code::all();
            $emirates = \App\Models\emirate::all();
            $plans = \App\Models\plan::wherestatus('1')->get();
            $elifes = \App\Models\elife_plan::wherestatus('1')->get();
            $addons = \App\Models\addon::wherestatus('1')->get();
            $users = \App\Models\User::whererole('sale')->get();
            // return $id;
            $data = lead_sale::findorfail($id);
            $itproducts = \App\Models\it_products::wherestatus('1')->get();
            $audios = audio_recording::wherelead_no($id)->get();
            $remarks =
                remark::select("remarks.*")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                ->where("remarks.lead_id", $id)
                ->get();
            // return "1";
            return view('agent.view-lead-main', compact('countries', 'emirates', 'plans', 'elifes', 'addons', 'users', 'data', 'itproducts', 'remarks', 'audios'));
        } else {
            $countries = \App\Models\country_phone_code::all();
            $emirates = \App\Models\emirate::all();
            $plans = \App\Models\plan::wherestatus('1')->get();
            $elifes = \App\Models\elife_plan::wherestatus('1')->get();
            $addons = \App\Models\addon::wherestatus('1')->get();
            $users = \App\Models\User::whererole('sale')->get();
            // return $id;
            $data = lead_sale::findorfail($id);
            $itproducts = \App\Models\it_products::wherestatus('1')->get();
            $audios = audio_recording::wherelead_no($id)->get();
            $remarks =
                remark::select("remarks.*")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                ->where("remarks.lead_id", $id)
                ->get();
            // return "1";
            return view('agent.view-lead-main', compact('countries', 'emirates', 'plans', 'elifes', 'addons', 'users', 'data', 'itproducts', 'remarks', 'audios'));
        }
        // })->name('view.lead');
    }
    public function editlead($id)
    {
        // Route::get('view-lead/{id}', function ($id) {
        // return $id;
        // return auth()->user()-role;
            $countries = \App\Models\country_phone_code::all();
            $emirates = \App\Models\emirate::all();
            $plans = \App\Models\plan::wherestatus('1')->get();
            $elifes = \App\Models\elife_plan::wherestatus('1')->get();
            $addons = \App\Models\addon::wherestatus('1')->get();
            $users = \App\Models\User::whererole('sale')->get();
            // return $id;
            $data = lead_sale::findorfail($id);
            $itproducts = \App\Models\it_products::wherestatus('1')->get();
            $audios = audio_recording::wherelead_no($id)->get();
            $remarks =
                remark::select("remarks.*")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                ->where("remarks.lead_id", $id)
                ->get();
        // return "1";
        $q = numberdetail::select("numberdetails.type")
        ->where("numberdetails.status",
            "Available"
        )
            // ->whereIn("numberdetails.channel_type", ['TTF','ExpressDial'])
            ->whereIn("numberdetails.channel_type", ['ConnectCC'])
            ->groupBy('numberdetails.type')

            ->get();
            return view('agent.edit-lead', compact('countries', 'emirates', 'plans', 'elifes', 'addons', 'users', 'data', 'itproducts', 'remarks', 'audios','q'));

        // })->name('view.lead');
    }
    public function viewleadactive($id)
    {
        // Route::get('view-lead/{id}', function ($id) {
        // return $id;
        // return auth()->user()-role;
        if (auth()->user()->role == 'sale' || auth()->user()->role == 'NumberAdmin') {
            $countries = \App\Models\country_phone_code::all();
            $emirates = \App\Models\emirate::all();
            $plans = \App\Models\plan::wherestatus('1')->get();
            $elifes = \App\Models\elife_plan::wherestatus('1')->get();
            $addons = \App\Models\addon::wherestatus('1')->get();
            $users = \App\Models\User::whererole('sale')->get();
            // return $id;
            $data = lead_sale::findorfail($id);
            $itproducts = \App\Models\it_products::wherestatus('1')->get();
            // $audios = audio_recording::wherelead_no($id)->get();
            $remarks =
                remark::select("remarks.*")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                ->where("remarks.lead_id", $id)
                ->get();
            // return "1";
            return view('dashboard.view-lead', compact('countries', 'emirates', 'plans', 'elifes', 'addons', 'users', 'data', 'itproducts', 'remarks'));
        } else {
            $countries = \App\Models\country_phone_code::all();
            $emirates = \App\Models\emirate::all();
            $plans = \App\Models\plan::wherestatus('1')->get();
            $elifes = \App\Models\elife_plan::wherestatus('1')->get();
            $addons = \App\Models\addon::wherestatus('1')->get();
            $users = \App\Models\User::whererole('sale')->get();
            // return $id;
            $data = activation_form::where('lead_id', $id)->first();
            $itproducts = \App\Models\it_products::wherestatus('1')->get();
            $audios = audio_recording::wherelead_no($id)->get();
            $remarks =
                remark::select("remarks.*")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                ->where("remarks.lead_id", $id)
                ->get();
            // return "1";
            return view('dashboard.view-lead-active', compact('countries', 'emirates', 'plans', 'elifes', 'addons', 'users', 'data', 'itproducts', 'remarks', 'audios'));
        }
        // })->name('view.lead');
    }
    public function ReFollowEdit($id)
    {
        $countries = \App\Models\country_phone_code::all();
        $emirates = \App\Models\emirate::all();
        $plans = \App\Models\plan::wherestatus('1')->get();
        $elifes = \App\Models\elife_plan::wherestatus('1')->get();
        $addons = \App\Models\addon::wherestatus('1')->get();
        $users = \App\Models\User::whererole('sale')->get();
        // return $id;
        $data = lead_sale::findorfail($id);
        $itproducts = \App\Models\it_products::wherestatus('1')->get();
        $remarks =
            remark::select("remarks.*")
            // ->where("remarks.user_agent_id", auth()->user()->id)
            ->where("remarks.lead_id", $id)
            ->get();
        // return "1";
        return view('dashboard.edit-follow-lead', compact('countries', 'emirates', 'plans', 'elifes', 'addons', 'users', 'data', 'itproducts', 'remarks'));
    }
    public function ReFollowEditpost(Request $request)
    {
        // return $request;
        $id = $request->leadid;
        if (
            $request->callbackitp != '' && $request->channel_type == 'ITProducts'
        ) {
            $data = lead_sale::findorfail($id);
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                'cname' => 'required|string',
                'cnumber' => 'required|string',
                'nation' => 'required',
                'age' => 'required|numeric|min:20|not_in:20',
                'simtype' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                // 'plan_new' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                'remarks_itp' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            // $planName = $request->plan_name;
            // $planName = implode(',', $request->plan_new);
            // $SelNumber = implode(",", $request->selnumber);
            // $activation_charge = implode(",", $request->activation_charges_new);
            // $activation_rate_new = implode(",", $request->activation_rate_new);
            // $test = implode(",", $request->plans);
            $data->update([
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->simtype,
                'gender' => $request->gender,
                'lead_type' => $request->lead_type,
                'channel_type' => $request->channel_type,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_number,
                'etisalat_number' => $request->etisalat_number,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'share_with' => $request->shared_with,
                'additional_document' => $request->additional_document,
                'saler_id' => auth()->user()->id,
                // main
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->package_id,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                'lead_no' => $request->leadnumber,
                'remarks' => $request->remarks_itp,
                'status' => '1.03',
                'saler_name' => auth()->user()->name,
                // 'pay_status' => $activation_charge,
                // 'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            remark::create([
                'remarks' => $request->remarks_itp,
                'lead_status' => '1.03',
                'lead_id' => $data->id,
                'lead_no' => $data->id, 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                'user_agent' => auth()->user()->name,
                'user_agent_id' => auth()->user()->id,
            ]);
            // timing_duration::create([
            //     'lead_no' => $data->id,
            //     'lead_generate_time' => Carbon::now()->toDateTimeString(),
            //     'sale_agent' => auth()->user()->id,
            //     'status' => '1.03',

            // ]);
            notify()->success('New IT Sale has been submitted succesfully');
            // return redirect()->back()->withInput();
            return redirect(route('lead.index'));
        } else if ($request->call_back_at_new != '' && $request->simtype == 'New') {
            $data = lead_sale::findorfail($id);
            // return
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                'cname' => 'required|string',
                'cnumber' => 'required|string',
                'nation' => 'required',
                'age' => 'required|numeric|min:20|not_in:20',
                'simtype' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                'plan_new' => 'required',
                // 'selnumber' => 'required|numeric',
                'activation_charges_new' => 'required',
                'activation_rate_new' => 'required',
                'remarks_process_new' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            // $planName = $request->plan_name;
            $planName = implode(
                ',',
                $request->plan_new
            );
            $SelNumber = implode(",", $request->selnumber);
            $activation_charge = implode(",", $request->activation_charges_new);
            $activation_rate_new = implode(",", $request->activation_rate_new);
            $test = implode(",", $request->plans);
            $data->update([
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->simtype,
                'gender' => $request->gender,
                'lead_type' => $request->lead_type,
                'channel_type' => $request->channel_type,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_number,
                'etisalat_number' => $request->etisalat_number,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'share_with' => $request->shared_with,
                'additional_document' => $request->additional_document,
                'saler_id' => auth()->user()->id,
                // main
                'selected_number' => $SelNumber,
                'select_plan' => $planName,
                // 'contract_commitment' => $request->status,
                'contract_commitment' => $request->contract_comm_mnp,
                'lead_no' => $request->leadnumber,
                'remarks' => $request->remarks_process_new,
                'status' => '1.03',
                'saler_name' => auth()->user()->name,
                'pay_status' => $activation_charge,
                'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            // remark::create([
            //     'remarks' => $request->remarks_process_new,
            //     'lead_status' => '1.03',
            //     'lead_id' => $data->id,
            //     'lead_no' => $data->id, 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
            //     'user_agent' => 'Sale',
            //     'user_agent_id' => auth()->user()->id,
            // ]);
            // timing_duration::create([
            //     'lead_no' => $data->id,
            //     'lead_generate_time' => Carbon::now()->toDateTimeString(),
            //     'sale_agent' => auth()->user()->id,
            //     'status' => '1.03',

            // ]);
            notify()->success('New Sale has been submitted succesfully');
            // return redirect()->back()->withInput();
            return redirect(url('admin/leads/follow'));
        } else if ($request->call_back_at_mnp != '' && $request->simtype == 'MNP' || $request->call_back_at_mnp == '' && $request->simtype == 'Migration') {
            // return $request->call_back_at_mnp;
            $data = lead_sale::findorfail($id);
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                'cname' => 'required|string',
                'cnumber' => 'required|string',
                'nation' => 'required',
                'age' => 'required|numeric|min:20|not_in:20',
                'simtype' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                // 'plan_new' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            // return $request->plans;
            // return $request;
            // return $request->plan_mnp;
            // $planName = $request->plan_name;
            // $planName = implode(',', $request->plan_new);
            // $SelNumber = implode(",", $request->selnumber);
            // $activation_charge = implode(",", $request->activation_charges_new);
            // $activation_rate_new = implode(",", $request->activation_rate_new);
            $test = implode(",", $request->plans);
            $data->update([
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->simtype,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_number,
                'etisalat_number' => $request->etisalat_number,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'lead_type' => $request->lead_type,
                'channel_type' => $request->channel_type,
                'share_with' => $request->shared_with,
                'additional_document' => $request->additional_document,
                'saler_id' => auth()->user()->id,
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->plan_mnp,
                'contract_commitment' => $request->status,
                'benefits' => $test,
                'lead_no' => $request->leadnumber,
                'remarks' => $request->remarks_process_mnp,
                'status' => '1.03',
                'saler_name' => auth()->user()->name,
                'pay_status' => $request->activation_charges_mnp,
                // 'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            remark::create([
                'remarks' => $request->remarks_process_mnp,
                'lead_status' => '1.03',
                'lead_id' => $data->id,
                'lead_no' => $data->id, 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                'user_agent' => 'Sale',
                'user_agent_id' => auth()->user()->id,
            ]);
            // timing_duration::create([
            //     'lead_no' => $data->id,
            //     'lead_generate_time' => Carbon::now()->toDateTimeString(),
            //     'sale_agent' => auth()->user()->id,
            //     'status' => '1.01',

            // ]);
            notify()->success('New Sale has been updated succesfully');
            // return redirect()->back()->withInput();
            return redirect(route('lead.index'));


            // return $planName . $SelNumber . $activation_charge . $activation_rate_new;

        } else if (
            $request->simtype == 'MNP' || $request->simtype == 'Migration'
        ) {
            $data = lead_sale::findorfail($id);
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                'cname' => 'required|string',
                'cnumber' => 'required|string',
                'nation' => 'required',
                'age' => 'required|numeric|min:20|not_in:20',
                'simtype' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                // 'plan_new' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
            ]);
            // return $request;
            // return $request->plan_mnp;
            // $planName = $request->plan_name;
            // $planName = implode(',', $request->plan_new);
            // $SelNumber = implode(",", $request->selnumber);
            // $activation_charge = implode(",", $request->activation_charges_new);
            // $activation_rate_new = implode(",", $request->activation_rate_new);
            $test = implode(",", $request->plans);
            $data->update([
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->simtype,
                'gender' => $request->gender,
                'lead_type' => $request->lead_type,
                'channel_type' => $request->channel_type,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_number,
                'etisalat_number' => $request->etisalat_number,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'share_with' => $request->shared_with,
                'additional_document' => $request->additional_document,
                // 'saler_id' => auth()->user()->id,
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->plan_mnp,
                'contract_commitment' => $request->status,
                'benefits' => $test,
                'lead_no' => $request->leadnumber,
                'remarks' => $request->remarks_process_mnp,
                'status' => '1.20',
                // 'saler_name' => auth()->user()->name,
                'pay_status' => $request->activation_charges_mnp,
                // 'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            remark::create([
                'remarks' => $request->remarks_process_mnp,
                'lead_status' => '1.20',
                'lead_id' => $data->id,
                'lead_no' => $data->id, 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                'user_agent' => 'Sale',
                'user_agent_id' => auth()->user()->id,
            ]);
            // timing_duration::create([
            //     'lead_no' => $data->id,
            //     'lead_generate_time' => Carbon::now()->toDateTimeString(),
            //     'sale_agent' => auth()->user()->id,
            //     'status' => '1.01',

            // ]);
            notify()->success('Lead has been Process succesfully');
            // return redirect()->back()->withInput();
            return redirect(route('lead.index'));


            // return $planName . $SelNumber . $activation_charge . $activation_rate_new;
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        } else if (
            $request->simtype == 'New'
        ) {
            // return "Zoom";
            $data = lead_sale::findorfail($id);
            // return $request->simtype;
            // return $request->remarks_process_new;
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                'cname' => 'required|string',
                'cnumber' => 'required|string',
                'nation' => 'required',
                'age' => 'required|numeric|min:20|not_in:20',
                'simtype' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                'plan_new' => 'required',
                // 'selnumber' => 'required|numeric',
                'activation_charges_new' => 'required',
                'activation_rate_new' => 'required',
                'remarks_process_new' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            // $planName = $request->plan_name;
            $planName = implode(
                ',',
                $request->plan_new
            );
            $SelNumber = implode(",", $request->selnumber);
            $activation_charge = implode(",", $request->activation_charges_new);
            $activation_rate_new = implode(",", $request->activation_rate_new);
            $test = implode(",", $request->plans);
            if (auth()->user()->role == 'Cordination') {
                $status = '1.21';
            } else {
                $status = '1.20';
            }
            $data->update([
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->simtype,
                'lead_type' => $request->lead_type,
                'channel_type' => $request->channel_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_number,
                'etisalat_number' => $request->etisalat_number,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'share_with' => $request->shared_with,
                'additional_document' => $request->additional_document,
                // 'saler_id' => auth()->user()->id,
                // main
                'selected_number' => $SelNumber,
                'select_plan' => $planName,
                // 'contract_commitment' => $request->status,
                'contract_commitment' => $request->contract_comm_mnp,
                'lead_no' => $request->leadnumber,
                'remarks' => $request->remarks_process_new,
                'status' => $status,
                // 'saler_name' => auth()->user()->name,
                'pay_status' => $activation_charge,
                'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            // remark::create([
            //     'remarks'=> $request->remarks_process_new,
            //     'lead_status' => $status,
            //     'lead_id' => $data->id,
            //     'lead_no' => $data->id,'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
            //     'user_agent' => 'Sale',
            //     'user_agent_id' => auth()->user()->id,
            // ]);
            //
            if (!empty($request->add_lat_lng)) {
                $name = explode(',', $request->add_lat_lng);
                $lat = $name[0];
                $lng = $name[1];
            } else {
                $lat = '';
                $lng = '';
            }

            $lead_location = lead_location::where('lead_id', $data->id)->first();
            if ($lead_location) {
                $lead_location->location_url = $request->add_location;
                $lead_location->lat = $lat;
                $lead_location->lng = $lng;
                $lead_location->assign_to = 136;
                $lead_location->save();
            } else {
                lead_location::create([
                    'lead_id' => $data->id,
                    'location_url' => $request->add_location,
                    'lat' => $lat,
                    'lng' => $lng,
                    'assign_to' => 136,
                    // 'number_allowed' => $request->num_allowed,
                    // 'duration' => $request->duration,
                    // 'revenue' => $request->revenue,
                    // 'free_minutes' => $request->free_min,
                    'status' => 1,
                ]);
            }

            //
            // timing_duration::create([
            //     'lead_no' => $data->id,
            //     'lead_generate_time' => Carbon::now()->toDateTimeString(),
            //     'sale_agent' => auth()->user()->id,
            //     'status' => '1.01',

            // ]);
            notify()->success('Lead has been Process succesfully');
            // return redirect()->back()->withInput();
            return redirect(route('lead.index'));


            // return $planName . $SelNumber . $activation_charge . $activation_rate_new;

        } else if ($request->channel_type == 'ITProducts') {
            // return $request;
            $data = lead_sale::findorfail($id);
            // if ($request->callbackitp != '' && $request->channel_type == 'ITProducts') {
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                'cname' => 'required|string',
                'cnumber' => 'required|string',
                'nation' => 'required',
                'age' => 'required|numeric|min:20|not_in:20',
                'simtype' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                // 'plan_new' => 'required',
                // // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                'remarks_itp' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            // return "s";
            // $planName = $request->plan_name;
            // $planName = implode(',', $request->plan_new);
            // $SelNumber = implode(",", $request->selnumber);
            // $activation_charge = implode(",", $request->activation_charges_new);
            // $activation_rate_new = implode(",", $request->activation_rate_new);
            // $test = implode(",", $request->plans);
            $data->update([
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->simtype,
                'gender' => $request->gender,
                'lead_type' => $request->lead_type,
                'channel_type' => $request->channel_type,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_number,
                'etisalat_number' => $request->etisalat_number,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'share_with' => $request->shared_with,
                'additional_document' => $request->additional_document,
                'saler_id' => auth()->user()->id,
                // main
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->package_id,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                'lead_no' => $request->leadnumber,
                'remarks' => $request->remarks_itp,
                'status' => '1.01',
                'saler_name' => auth()->user()->name,
                // 'pay_status' => $activation_charge,
                // 'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            remark::create([
                'remarks' => $request->remarks_itp,
                'lead_status' => '1.01',
                'lead_id' => $data->id,
                'lead_no' => $data->id, 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                'user_agent' => auth()->user()->name,
                'user_agent_id' => auth()->user()->id,
            ]);
            // timing_duration::create([
            //     'lead_no' => $data->id,
            //     'lead_generate_time' => Carbon::now()->toDateTimeString(),
            //     'sale_agent' => auth()->user()->id,
            //     'status' => '1.03',
            // return "bo";
            // ]);
            notify()->success('New IT Sale has been submitted succesfully');
            // return redirect()->back()->withInput();
            return redirect(route('lead.index'));
            // }
        } else if (
            $request->simtype == 'Elife'
        ) {
            // return "elife";
            $data = lead_sale::findorfail($id);
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                'cname' => 'required|string',
                'cnumber' => 'required|string',
                'nation' => 'required',
                'age' => 'required|numeric|min:20|not_in:20',
                'simtype' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                'plan_elife' => 'required',
                'remarks_process_elife' => 'required',
                // 'plan_new' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            // return "b";
            // $planName = $request->plan_name;
            // $planName = implode(',', $request->plan_new);
            // $SelNumber = implode(",", $request->selnumber);
            // $activation_charge = implode(",", $request->activation_charges_new);
            // $activation_rate_new = implode(",", $request->activation_rate_new);
            // $test = implode(",", $request->plans);
            // return $request->elife_plans;
            $elife_plans = implode(",", $request->elife_plans);
            $data->update([
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->simtype,
                'lead_type' => $request->lead_type,
                'channel_type' => $request->channel_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_number,
                'etisalat_number' => $request->etisalat_number,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'share_with' => $request->shared_with,
                'additional_document' => $request->additional_document,
                'saler_id' => auth()->user()->id,
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->plan_elife,
                'contract_commitment' => $request->contract_commitement_elife,
                'number_commitment' => $request->elife_makani_number,
                'benefits' => $elife_plans,
                'lead_no' => $request->leadnumber,
                'remarks' => $request->remarks_process_elife,
                'status' => '1.01',
                'saler_name' => auth()->user()->name,
                // 'pay_status' => $activation_charge,
                // 'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            remark::create([
                'remarks' => $request->remarks_process_elife,
                'lead_status' => '1.01',
                'lead_id' => $data->id,
                'lead_no' => $data->id,
                'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                'user_agent' => 'Sale',
                'user_agent_id' => auth()->user()->id,
            ]);
            timing_duration::create([
                'lead_no' => $data->id,
                'lead_generate_time' => Carbon::now()->toDateTimeString(),
                'sale_agent' => auth()->user()->id,
                'status' => '1.01',

            ]);
            $batch_id = $data->id;
            $teacher_id = $request->elife_package;
            $book_records = [];

            // Add needed information to book records
            if (!empty($teacher_id)) {
                foreach ($teacher_id as $key => $val) {
                    // return $val;
                    // return $key;
                    // foreach ($teacher_id as $book => $val) {
                    // Get the current time
                    // $now = Carbon::now();

                    // Formulate record that will be saved
                    $book_records[] = [
                        'lead_id' => $batch_id,
                        'addon_id' => $val,
                        'status' => 1,
                    ];
                }
            }
            chosen_addon::insert($book_records);



            notify()->success('New Sale has been submitted succesfully');
            // return redirect()->back()->withInput();
            return redirect(route('lead.index'));


            // return $planName . $SelNumber . $activation_charge . $activation_rate_new;

        }
    }
    public function reprocessgroup(Request $request)
    {
        // return $request;
        $ldate = date('h:i A');

        $validatedData = Validator::make($request->all(), [
            'add_location' => 'required|string',
            'start_date' => 'required',
            'start_time' => 'required|after:' . $ldate,

            // 'add_lat_lng' => 'required',
            // 'assing_to' => 'required'
            // 'lng' => 'required|numeric',
        ]);
        if ($validatedData->fails()) {
            // return redirect()->back()
            //     ->withErrors($validatedData)
            //     ->withInput();
            return response()->json(['error' => $validatedData->errors()->all()]);
        }
        //
        $choosen_date = $request->start_time;
        $carbon_date = Carbon::parse($choosen_date);
        $second_date  = $carbon_date->addHours(2);
        $lead_data = $d = lead_sale::findOrFail($request->lead_id);
        $d->update([
            'eti_lead_id' => NULL,
            'status' => '1.10',
            'appointment_from' => date('H:i:s', strtotime($choosen_date)),
            'appointment_to' => date('H:i:s', strtotime($second_date)),
        ]);
        $dd = verification_form::findOrFail($request->ver_id);
        $dd->update([
            'status' => '1.21',
            // 'assing_to' => '1',
            // 'cordination_by' => auth()->user()->id,
            // 'emirate_location' => $request->emirates,
        ]);
        if (!empty($request->add_lat_lng)) {

            $name = explode(',', $request->add_lat_lng);
            $lat = $name[0];
            $lng = $name[1];
        } else {
            $lat = '';
            $lng = '';
        }
        // $kp = lead_location::where('lead_id', $request->lead_id)->first();
        $kp = lead_location::where('lead_id', $request->lead_id)->first();
        if ($kp) {
            $kp->update([
                'assign_to' => $request->assing_to,
                'location_url' => $request->add_location,
                'lat' => $lat,
                'lng' => $lng,
            ]);
        } else {
            $kkk = lead_location::create([
                'lead_id' => $request->lead_id,
                'location_url' => $request->add_location,
                'lat' => $lat,
                'lng' => $lng,
                'assign_to' =>$request->assing_to,
                // 'number_allowed' => $request->num_allowed,
                // 'duration' => $request->duration,
                // 'revenue' => $request->revenue,
                // 'free_minutes' => $request->free_min,
                'status' => 1,
            ]);
        }
        // return "LocationLead";
        // var encodedURL = encodeURIComponent(some_url);
        //
        $a = "whatsapp://send?text=New  %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a Number Selected: $lead_data->selected_number %0a Plan selected: FE125 %0a Data : 4GB %0a  Activation: $lead_data->pay_status  %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: $lead_data->saler_name %0a New Lead Location https://maps.google.com?q=$lng,$lat %0a New Customer Location Re Process Follow up";
        return response()->json(['success' => $a]);
    }
    public function emirate_proceed_lead(Request $request)
    {
        // return $request;
        $ldate = date('h:i A');

        $validatedData = Validator::make($request->all(), [
            'add_location' => 'required|string',
            'eti_lead_id' => 'required|string',
            'start_date' => 'required',
            'start_time' => 'required|after:' . $ldate,

            // 'add_lat_lng' => 'required',
            // 'assing_to' => 'required'
            // 'lng' => 'required|numeric',
        ]);
        if ($validatedData->fails()) {
            // return redirect()->back()
            //     ->withErrors($validatedData)
            //     ->withInput();
            return response()->json(['error' => $validatedData->errors()->all()]);
        }
        //
        $choosen_date = $request->start_time;
        $carbon_date = Carbon::parse($choosen_date);
        $second_date  = $carbon_date->addHours(2);
        $lead_data = $d = lead_sale::findOrFail($request->lead_id);
        // $lead
        $d->update([
            'status' => '1.10',
            'eti_lead_id' => $request->eti_lead_id,
            'appointment_from' => date('H:i:s', strtotime($choosen_date)),
            'appointment_to' => date('H:i:s', strtotime($second_date)),
        ]);
        // return $d;
        $dd = verification_form::findOrFail($request->ver_id);
        $dd->update([
            'status' => '1.21',
            // 'assing_to' => '1',
            // 'cordination_by' => auth()->user()->id,
            // 'emirate_location' => $request->emirates,
        ]);
        if (!empty($request->add_lat_lng)) {

            $name = explode(',', $request->add_lat_lng);
            $lat = $name[0];
            $lng = $name[1];
        } else {
            $lat = '';
            $lng = '';
        }
        // $kp = lead_location::where('lead_id', $request->lead_id)->first();
        $kp = lead_location::where('lead_id', $request->lead_id)->first();
        if ($kp) {
            $kp->update([
                'assign_to' => $request->assing_to,
                'location_url' => $request->add_location,
                'lat' => $lat,
                'lng' => $lng,
            ]);
        } else {
            $kkk = lead_location::create([
                'lead_id' => $request->lead_id,
                'location_url' => $request->add_location,
                'lat' => $lat,
                'lng' => $lng,
                'assign_to' =>$request->assing_to,
                // 'number_allowed' => $request->num_allowed,
                // 'duration' => $request->duration,
                // 'revenue' => $request->revenue,
                // 'free_minutes' => $request->free_min,
                'status' => 1,
            ]);
        }

         $ntc = lead_sale::select('call_centers.notify_email', 'call_centers.numbers')
                    ->Join(
                        'users',
                        'users.id',
                        'lead_sales.saler_id'
                    )
                    ->Join(
                        'call_centers',
                        'call_centers.call_center_code',
                        'users.agent_code'
                    )
                    ->where('lead_sales.id', $request->lead_id)->first();
                //


                $link = route('view.lead', $request->lead_id);

                $details = [
                    'lead_id' => $lead_data->id,
                    'lead_no' => $lead_data->lead_no,
                    'customer_name' =>  $lead_data->customer_name,
                    'customer_number' => $lead_data->customer_number,
                    'selected_number' => $lead_data->selected_number,
                    'sim_type' => $lead_data->sim_type,
                    'numbers' => $ntc->numbers,
                    'link' => $link,
                    'eti_lead_id' => $lead_data->eti_lead_id,
                    // 'Plan' => $number,
                    // 'AlternativeNumber' => $alternativeNumber,
                ];
        // return "LocationLead";
       \App\Http\Controllers\WhatsAppController::CoordinationWhatsApp($details);

        // var encodedURL = encodeURIComponent(some_url);
        //
        $a = "whatsapp://send?text=New  %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a Number Selected: $lead_data->selected_number %0a Plan selected: FE125 %0a Data : 4GB %0a  Activation: $lead_data->pay_status  %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: $lead_data->saler_name %0a New Lead Location https://maps.google.com?q=$lng,$lat %0a New Customer Location Re Process Follow up";
        return response()->json(['success' => $a]);
    }
    // Route::get('verified', function () {
    //
    public function verified()
    {
        $id = 'Verification';
        if (auth()->user()->role == 'Manager' || auth()->user()->role == 'Admin' || auth()->user()->role == 'SuperAdmin') {
            $operation = lead_sale::select("verification_forms.*", "timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name")
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
                    'verification_forms',
                    'verification_forms.lead_no',
                    '=',
                    'lead_sales.id'
                )
                ->where('lead_sales.status', '1.07')
                // ->where('verification_forms.verify_agent', auth()->user()->id)
                ->get();
            // $operation = lead_sale::wherestatus('1.01')->get();
            return view('dashboard.view-all-lead', compact('operation', 'id'));
        } elseif (auth()->user()->role == 'elif-Verification') {
            $id = 'Verification';
            $operation = lead_sale::select("verification_forms.*", "timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name")
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
                    'verification_forms',
                    'verification_forms.lead_no',
                    '=',
                    'lead_sales.id'
                )
                ->where('lead_sales.status', '1.07')
                ->where('verification_forms.verify_agent', auth()->user()->id)
                ->get();
            // $operation = lead_sale::wherestatus('1.01')->get();
            return view('dashboard.view-all-lead', compact('operation', 'id'));
        } else {
            $id = 'Verification';
            $operation = lead_sale::select("verification_forms.*", "timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name")
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
                    'verification_forms',
                    'verification_forms.lead_no',
                    '=',
                    'lead_sales.id'
                )
                // ->where('verification_forms.status', '1.07')
                ->where('verification_forms.verify_agent', auth()->user()->id)
                ->get();
            // $operation = lead_sale::wherestatus('1.01')->get();
            return view('dashboard.view-all-lead', compact('operation', 'id'));
        }
    }
    public function non_verified()
    {
        $id = 'Non Verified Leads';
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
            ->where('lead_sales.status', '1.03')
            // ->where('lead_sales.pre_check_agent', auth()->user()->id)
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)

            ->get();
        return view('dashboard.view-all-lead', compact('operation', 'id'));
    }
    public function active_non_verified()
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        $id = 'Active Non Verified Leads';
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
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->where('lead_sales.status', '1.11')
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)

            // ->where('lead_sales.pre_check_agent', auth()->user()->id)
            ->get();
        $channel_partner = channel_partner::where('status', '1')->latest("updated_at")->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.view-operation-lead', compact('operation', 'channel_partner'));
        // return view('dashboard.view-all-lead', compact('operation', 'id'));

    }
    public function non_verified_daily()
    {
        $id = 'Non Verified Leads';
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
            ->where('lead_sales.status', '1.03')
            // ->where('lead_sales.pre_check_agent', auth()->user()->id)
            ->whereDate('lead_sales.updated_at', Carbon::today())
            ->get();
        return view('dashboard.view-all-lead', compact('operation', 'id'));
    }
    public function active_non_verified_daily()
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        $id = 'Active Non Verified Leads';
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
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->where('lead_sales.status', '1.11')
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('lead_sales.pre_check_agent', auth()->user()->id)
            ->get();
        $channel_partner = channel_partner::where('status', '1')->latest("updated_at")->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.view-operation-lead', compact('operation', 'channel_partner'));
        // return view('dashboard.view-all-lead', compact('operation', 'id'));

    }
    public function emirate_active_non_verified_daily()
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        $id = 'Active Non Verified Leads';
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
            ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            ->where('lead_sales.status', '1.11')
            ->whereDate('lead_sales.updated_at', Carbon::today())
            ->whereIn('lead_sales.channel_type', $mychannel)
            // ->where('lead_sales.pre_check_agent', auth()->user()->id)
            ->get();
        $channel_partner = channel_partner::where('status', '1')->latest("updated_at")->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.view-operation-lead', compact('operation', 'channel_partner'));
        // return view('dashboard.view-all-lead', compact('operation', 'id'));

    }
    public function active_non_verified_all()
    {
        $id = 'Active Non Verified Leads';
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
            ->whereIn('lead_sales.select_plan', ['112', '69'])
            ->where('lead_sales.status', '1.02')
            // ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            // ->whereyear('lead_sales.updated_at', Carbon::now()->year)

            // ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('lead_sales.pre_check_agent', auth()->user()->id)
            ->get();
        $channel_partner = channel_partner::where('status', '1')->latest("updated_at")->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.view-operation-lead', compact('operation', 'channel_partner'));
        // return view('dashboard.view-all-lead', compact('operation', 'id'));

    }
    public function carry_forward(Request $Request)
    {
        // return Carbon::now()->subMonth()->month;
        $id = 'Carry Forward';
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
                'lead_sales.saler_id'
            )
            ->whereIn('lead_sales.status', ['1.19', '1.20'])
            ->whereMonth('lead_sales.updated_at', Carbon::now()->subMonth()->month)
            //     // ->whereMonth('lead_sales.updated_at', Carbon::now()->month)

            // ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->where('users.agent_code', auth()->user()->agent_code)
            ->get();
        return view('dashboard.view-all-lead', compact('operation', 'id'));
        //  $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "lead_sales.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to")
        // // $user =  DB::table("subjects")->select('subject_name', 'id')
        // ->LeftJoin(
        //     'timing_durations',
        //     'timing_durations.lead_no',
        //     '=',
        //     'verification_forms.lead_no'
        // )
        //     ->LeftJoin(
        //         'remarks',
        //         'remarks.lead_no',
        //         '=',
        //         'verification_forms.lead_no'
        //     )
        //     ->LeftJoin(
        //         'status_codes',
        //         'status_codes.status_code',
        //         '=',
        //         'verification_forms.status'
        //     )
        //     ->LeftJoin(
        //         'lead_sales',
        //         'lead_sales.id',
        //         '=',
        //         'verification_forms.lead_no'
        //     )
        //     ->LeftJoin(
        //         'users',
        //         'users.id',
        //         '=',
        //         'lead_sales.saler_id'
        //     )
        //     ->LeftJoin(
        //         'lead_locations',
        //         'lead_locations.lead_id',
        //         '=',
        //         'lead_sales.id'
        //     )
        //     // ->LeftJoin(
        //     //         'lead_locations',
        //     //         'lead_locations.lead_id',
        //     //         '=',
        //     //         'lead_sales.id'
        //     //     )
        //         // ->where('verification_forms.status', '1.10')
        //     ->whereIn('lead_sales.status', ['1.19','1.20'])
        //     ->where('users.agent_code', auth()->user()->agent_code)
        //     // ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
        //     // ->where('verification_forms.assing_to', auth()->user()->id)
        //     // ->where('verification_forms.emirate_location', auth()->user()->emirate)
        //     ->groupBy('verification_forms.lead_no')
        //     ->get();
        // ->whereMonth('lead_sales.updated_at', Carbon::now()->subMonth()->month)
        // $operation = verification_form::wherestatus('1.10')->get();
        // return view('dashboard.view-proceed-request-only', compact('operation'));
    }
    public function my_carry_forward(Request $Request)
    {
        // return Carbon::now()->subMonth()->month;
        $id = 'Re Follow Up Lead';
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
            ->whereIn('lead_sales.status', ['1.19'])
            ->where('lead_sales.saler_id', auth()->user()->id)
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->get();
        return view('dashboard.view-all-lead', compact('operation', 'id'));
    }
    // })->name('admin.verify')->middleware('auth');
    public function verified_active(Request $request)
    {
        if ($request->sim_type == 'MNP') {

            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                // 'cname' => 'required|string|unique:lead_sales,customer_name',
                'cnumber' => 'required',
                'nation' => 'required',
                'age' => 'required|numeric',
                'sim_type' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                'plan_mnp' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
                'audio.*' => 'required',

            ]);
            if ($validator->fails()) {
                // return redirect()->back()
                //     ->withErrors($validator)
                //     ->withInput();
                return response()->json(['error' => $validator->errors()->all()]);
            }
            if (empty($request->audio)) {
                // return "s";
                return response()->json(['error' => ['Please Attach Audio']]);
                // return response()->json(['error' => ]);
                // notify()->error('Please Submit Audio');
                // return redirect()->back()
                //     ->withInput();
            }
            // $planName = $request->plan_name;
            $planName = $request->plan_mnp;
            $SelNumber = $request->selnumber;
            $activation_charge = $request->activation_charges_new;
            $activation_rate_new =
                $request->activation_rate_new;
            // return $request->emirate_id;
            // return $test = implode(",", $request->plan_new);
            // $data = verification_form::create([
            $lead_data = $data = verification_form::where('lead_no', $request->lead_id)->first();
            $data->update([
                'cust_id' => $request->lead_id,
                'lead_no' => $request->lead_id,
                'lead_id' => $request->lead_no,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_number' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'original_emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirate_location' => $request->emirates,
                'additional_documents' => $request->additional_documents,
                'verify_agent' => auth()->user()->id,
                // main
                'selected_number' => $SelNumber,
                'select_plan' => $planName,
                // 'contract_commitment' => $request->status,
                'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_new,
                'status' => '1.02',
                // 'saler_name' => 'Sale',
                'pay_status' => $activation_charge,
                'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            // foreach ($request->selnumber as $key => $val) {
            //     // return $val;
            //     $count = numberdetail::select("numberdetails.id")
            //     ->where('numberdetails.number', $val)
            //     ->count();
            //     if ($count > 0) {
            //         $dn = numberdetail::select("numberdetails.id")
            //         ->where('numberdetails.number', $val)
            //         ->first();
            //         $k = numberdetail::findorfail($dn->id);
            //         $k->status = 'Hold';
            //         $k->save();
            //         // CHANGE LATER
            //         $cn = choosen_number::select('choosen_numbers.id')
            //         ->where('number_id', $dn->id)
            //             ->first();
            //         if ($cn) {
            //             $cnn = choosen_number::findorfail($cn->id);
            //             $cnn->status = '4';
            //             $cnn->save();
            //         }
            //         // CHANGE LATER
            //     }
            //     // return $d->id;
            //     // return "number has been reserved";

            // }
            // $n = numberdetail::select("numberdetails.id")
            //     ->where('numberdetails.number', $val)
            //     ->first();
            // $k = numberdetail::findorfail($d->id);
            // $k->status = 'Reserved';
            // $k->save();
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            // $wp = \App\Models\User::select('role')->where('users.id', $lead_data->saler_id)->first();
            // if ($wp->role == 'TTF-SALE') {
            //     $status_code = '1.02';
            // } else {
            //     $status_code = '1.02';
            // }
            $status_code = '1.02';
            $d->update([
                'status' => $status_code,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_num,
                'area' => $request->area,
                // 'etisalat_number' => $request->status,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirates' => $request->emirates,
                'additional_document' => $request->additional_documents,
                // 'verify_agent' => auth()->user()->id,
                // main
                'selected_number' => $SelNumber,
                'select_plan' => $planName,
                // 'contract_commitment' => $request->status,
                'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_new,
                'verify_agent' => auth()->user()->id,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $activation_charge,
                'pay_charges' => $activation_rate_new,
            ]);
            // $d = timing_duration::select('id')
            //     ->where('lead_no', $request->lead_id)
            //     ->first();
            // $data  = timing_duration::findorfail($d->id);
            // $data->lead_proceed_time = Carbon::now()->toDateTimeString();
            // $data->verify_agent = auth()->user()->id;
            // $data->save();


            foreach ($request->audio as $key => $val) {
                if (!empty($request->audio)) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('audio')) {
                        // AzureCodeStart
                        $image2 = file_get_contents($file[$key]);
                        $originalFileName = time() . $file[$key]->getClientOriginalName();
                        $multi_filePath = 'audio' . '/' . $originalFileName;
                        \Storage::disk('azure')->put($multi_filePath, $image2);
                        // AzureCodeEnd
                        // LocalStorageCodeStart
                        $ext = date('d-m-Y-H-i');
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = $originalFileName;
                        $file[$key]->move('audio', $name);
                        $input['path'] = $name;
                        // LocalStorageCodeEnd
                    }
                    //     $data2 = meeting_std::create([
                    //         'meeting_id' => $meeting_id,
                    //         'meeting_title' => $request->course_title,
                    //         'std_id' => $val,
                    //         'status' => 1,
                    //     ]);
                    // } else {
                    //     echo "boom";
                    // }
                    $data = audio_recording::create([
                        // 'resource_name' => $request->resource_name,
                        'audio_file' => $name,
                        'username' => 'salman',
                        'lead_no' => $request->lead_id,
                        // 'teacher_id' => $request->teacher_id,
                        'status' => 1,
                    ]);
                }
            }
        } else {
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                // 'cname' => 'required|string|unique:lead_sales,customer_name',
                'cnumber' => 'required',
                'nation' => 'required',
                'age' => 'required|numeric',
                'sim_type' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                'plan_new' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
                'audio.*' => 'required',

            ]);
            if ($validator->fails()) {
                // return redirect()->back()
                //     ->withErrors($validator)
                //     ->withInput();
                return response()->json(['error' => $validator->errors()->all()]);
            }
            if (empty($request->audio)) {
                // return "s";
                return response()->json(['error' => ['Please Attach Audio']]);
                // return response()->json(['error' => ]);
                // notify()->error('Please Submit Audio');
                // return redirect()->back()
                //     ->withInput();
            }
            // $planName = $request->plan_name;
            $planName = implode(',', $request->plan_new);
            $SelNumber = implode(",", $request->selnumber);
            $activation_charge = implode(",", $request->activation_charges_new);
            $activation_rate_new = implode(
                ",",
                $request->activation_rate_new
            );
            // return $request->emirate_id;
            // return $test = implode(",", $request->plan_new);
            // $data = verification_form::create([
            $lead_data = $data = verification_form::where('lead_no', $request->lead_id)->first();
            $data->update([
                'cust_id' => $request->lead_id,
                'lead_no' => $request->lead_id,
                'lead_id' => $request->lead_no,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_number' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'original_emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirate_location' => $request->emirates,
                'additional_documents' => $request->additional_documents,
                'verify_agent' => auth()->user()->id,
                // main
                'selected_number' => $SelNumber,
                'select_plan' => $planName,
                // 'contract_commitment' => $request->status,
                'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_new,
                'status' => '1.02',
                // 'saler_name' => 'Sale',
                'pay_status' => $activation_charge,
                'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            // foreach ($request->selnumber as $key => $val) {
            //     // return $val;
            //     $count = numberdetail::select("numberdetails.id")
            //     ->where('numberdetails.number', $val)
            //     ->count();
            //     if ($count > 0) {
            //         $dn = numberdetail::select("numberdetails.id")
            //         ->where('numberdetails.number', $val)
            //         ->first();
            //         $k = numberdetail::findorfail($dn->id);
            //         $k->status = 'Hold';
            //         $k->save();
            //         // CHANGE LATER
            //         $cn = choosen_number::select('choosen_numbers.id')
            //         ->where('number_id', $dn->id)
            //             ->first();
            //         if ($cn) {
            //             $cnn = choosen_number::findorfail($cn->id);
            //             $cnn->status = '4';
            //             $cnn->save();
            //         }
            //         // CHANGE LATER
            //     }
            //     // return $d->id;
            //     // return "number has been reserved";

            // }
            // $n = numberdetail::select("numberdetails.id")
            //     ->where('numberdetails.number', $val)
            //     ->first();
            // $k = numberdetail::findorfail($d->id);
            // $k->status = 'Reserved';
            // $k->save();
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            // $wp = \App\Models\User::select('role')->where('users.id', $lead_data->saler_id)->first();
            // if ($wp->role == 'TTF-SALE') {
            //     $status_code = '1.02';
            // } else {
            //     $status_code = '1.02';
            // }
            $status_code = '1.02';
            $d->update([
                'status' => $status_code,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_num,
                'area' => $request->area,
                // 'etisalat_number' => $request->status,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirates' => $request->emirates,
                'additional_document' => $request->additional_documents,
                // 'verify_agent' => auth()->user()->id,
                // main
                'selected_number' => $SelNumber,
                'select_plan' => $planName,
                // 'contract_commitment' => $request->status,
                'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_new,
                'verify_agent' => auth()->user()->id,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $activation_charge,
                'pay_charges' => $activation_rate_new,
            ]);
            // $d = timing_duration::select('id')
            //     ->where('lead_no', $request->lead_id)
            //     ->first();
            // $data  = timing_duration::findorfail($d->id);
            // $data->lead_proceed_time = Carbon::now()->toDateTimeString();
            // $data->verify_agent = auth()->user()->id;
            // $data->save();


            foreach ($request->audio as $key => $val) {
                if (!empty($request->audio)) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('audio')) {
                        // AzureCodeStart
                        $image2 = file_get_contents($file[$key]);
                        $originalFileName = time() . $file[$key]->getClientOriginalName();
                        $multi_filePath = 'audio' . '/' . $originalFileName;
                        \Storage::disk('azure')->put($multi_filePath, $image2);
                        // AzureCodeEnd
                        // LocalStorageCodeStart
                        $ext = date('d-m-Y-H-i');
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = $originalFileName;
                        $file[$key]->move('audio', $name);
                        $input['path'] = $name;
                        // LocalStorageCodeEnd
                    }
                    //     $data2 = meeting_std::create([
                    //         'meeting_id' => $meeting_id,
                    //         'meeting_title' => $request->course_title,
                    //         'std_id' => $val,
                    //         'status' => 1,
                    //     ]);
                    // } else {
                    //     echo "boom";
                    // }
                    $data = audio_recording::create([
                        // 'resource_name' => $request->resource_name,
                        'audio_file' => $name,
                        'username' => 'salman',
                        'lead_no' => $request->lead_id,
                        // 'teacher_id' => $request->teacher_id,
                        'status' => 1,
                    ]);
                }
            }

            // $whatsapp = \App\Models\User::select('phone')
            // ->where('users.id',$lead_data->agent_code)
            // ->first();
            // if()
            // foreach (explode(',', $planName) as $k) {
            //     $plan = \App\Models\plan::where('id', $k)->first();
            // }
            // $plan_name = $plan->plan_name;
            // $data = $plan->data;
            // // notify()->success('Sim Type New-Verified succesfully');
            // // return redirect()->back()->withInput();
            // // return redirect(route('verification.index'));
            // // return "VIN";
            // // return $d->customer_name;
            // $a = "https://api.whatsapp.com/send?text= ** New-Verified **  %0a Lead No: $lead_data->lead_no %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a Number Selected: $lead_data->selected_number %0a Plan selected: $plan_name %0a Data : $data %0a  Activation: $lead_data->pay_status  %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: Salman %0a Verified";
            // return response()->json(['success' => $a]);
        }
        return response()->json(['success' => "Successfully Verified"]);
    }
    //
    public function update_time(Request $request)
    {
        $ldate = date('h:i A');

        //  date('m/d/y H:i:s', strtotime($request->start_date . $request->start_time));

        //
        $validator = Validator::make($request->all(), [ // <---
            'start_date' => 'required',
            'start_time' => 'required|after:' . $ldate,
        ]);
        if ($validator->fails()) {
            // return redirect()->back()
            //     ->withErrors($validator)
            //     ->withInput();
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $choosen_date = $request->start_time;
        $carbon_date = Carbon::parse($choosen_date);
        $second_date  = $carbon_date->addHours(2);
        $lead_data = $d = lead_sale::findOrFail($request->lead_id);
        $d->update([
            'appointment_from' => date('H:i:s', strtotime($choosen_date)),
            'appointment_to' => date('H:i:s', strtotime($second_date)),
        ]);
    }
    //
    public function verified_today(Request $request)
    {
        $ldate = date('h:i A');

        //  date('m/d/y H:i:s', strtotime($request->start_date . $request->start_time));

        //
        $validator = Validator::make($request->all(), [ // <---
            // 'title' => 'required|unique:posts|max:255',
            // 'body' => 'required',
            // 'cname' => 'required|string|unique:lead_sales,customer_name',
            'cnumber' => 'required',
            'nation' => 'required',
            'age' => 'required|numeric',
            'sim_type' => 'required',
            'gender' => 'required',
            'emirates' => 'required',
            'emirate_id' => 'required',
            'language' => 'required',
            'plan_new' => 'required',

            'start_date' => 'required',
            'start_time' => 'required|after:' . $ldate,
            // 'end_date' => 'required',
            // 'end_time' => 'required|after:start_time',
            // 'selnumber' => 'required|numeric',
            // 'activation_charges_new' => 'required',
            // 'activation_rate_new' => 'required',
            // 'remarks_process_new' => 'required',
            'audio.*' => 'required',

        ]);
        if ($validator->fails()) {
            // return redirect()->back()
            //     ->withErrors($validator)
            //     ->withInput();
            return response()->json(['error' => $validator->errors()->all()]);
        }
        if (empty($request->audio)) {
            // return "s";
            return response()->json(['error' => ['Please Attach Audio']]);
            // return response()->json(['error' => ]);
            // notify()->error('Please Submit Audio');
            // return redirect()->back()
            //     ->withInput();
        }
        // $planName = $request->plan_name;
        $planName = implode(',', $request->plan_new);
        $SelNumber = implode(",", $request->selnumber);
        $activation_charge = implode(",", $request->activation_charges_new);
        $activation_rate_new = implode(
            ",",
            $request->activation_rate_new
        );
        // return $request->emirate_id;
        // return $test = implode(",", $request->plan_new);
        $data = verification_form::create([
            'cust_id' => $request->lead_id,
            'lead_no' => $request->lead_id,
            'lead_id' => $request->lead_no,
            'customer_name' => $request->cname,
            'customer_number' => $request->cnumber,
            'nationality' => $request->nation,
            'age' => $request->age,
            'sim_type' => $request->sim_type,
            'gender' => $request->gender,
            'emirates' => $request->emirates,
            'emirate_number' => $request->emirate_num,
            // 'etisalat_number' => $request->status,
            'original_emirate_id' => $request->emirate_id,
            'language' => $request->language,
            'emirate_location' => $request->emirates,
            'additional_documents' => $request->additional_documents,
            'verify_agent' => auth()->user()->id,
            // main
            'selected_number' => $SelNumber,
            'select_plan' => $planName,
            // 'contract_commitment' => $request->status,
            'contract_commitment' => $request->contract_comm_mnp,
            // 'lead_no' => 'Lead No',
            'remarks' => $request->remarks_process_new,
            'status' => '1.09',
            // 'saler_name' => 'Sale',
            'pay_status' => $activation_charge,
            'pay_charges' => $activation_rate_new,
            // 'device' => $request->status,
            // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
            // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
            // 'commitment_period' => $request->status,
        ]);
        foreach ($request->selnumber as $key => $val) {
            // return $val;
            $count = numberdetail::select("numberdetails.id")
                ->where('numberdetails.number', $val)
                ->count();
            if ($count > 0) {
                $dn = numberdetail::select("numberdetails.id")
                    ->where('numberdetails.number', $val)
                    ->first();
                $k = numberdetail::findorfail($dn->id);
                $k->status = 'Hold';
                $k->save();
                // CHANGE LATER
                $cn = choosen_number::select('choosen_numbers.id')
                    ->where('number_id', $dn->id)
                    ->first();
                if ($cn) {
                    $cnn = choosen_number::findorfail($cn->id);
                    $cnn->status = '4';
                    $cnn->save();
                }
                // CHANGE LATER
            }
            // return $d->id;
            // return "number has been reserved";

        }
        // $n = numberdetail::select("numberdetails.id")
        //     ->where('numberdetails.number', $val)
        //     ->first();
        // $k = numberdetail::findorfail($d->id);
        // $k->status = 'Reserved';
        // $k->save();
        $lead_data = $d = lead_sale::findOrFail($request->lead_id);
        $wp = \App\Models\User::select('role')->where('users.id', $lead_data->saler_id)->first();
        if ($wp->role == 'TTF-SALE') {
            $status_code = '1.10';
        } else {
            $status_code = '1.07';
        }
        $choosen_date = $request->start_time;
        $carbon_date = Carbon::parse($choosen_date);
        $second_date  = $carbon_date->addHours(2);

        $d->update([
            'status' => $status_code,
            'customer_name' => $request->cname,
            'customer_number' => $request->cnumber,
            'nationality' => $request->nation,
            'age' => $request->age,
            'sim_type' => $request->sim_type,
            'gender' => $request->gender,
            'emirates' => $request->emirates,
            'emirate_num' => $request->emirate_num,
            // 'etisalat_number' => $request->status,
            'emirate_id' => $request->emirate_id,
            'language' => $request->language,
            'emirates' => $request->emirates,
            'additional_document' => $request->additional_documents,
            // 'verify_agent' => auth()->user()->id,
            // main
            'selected_number' => $SelNumber,
            'select_plan' => $planName,
            // 'contract_commitment' => $request->status,
            'contract_commitment' => $request->contract_comm_mnp,
            // 'lead_no' => 'Lead No',
            'remarks' => $request->remarks_process_new,
            'verify_agent' => auth()->user()->id,
            // 'status' => '1.09',
            // 'saler_name' => 'Sale',
            'pay_status' => $activation_charge,
            'pay_charges' => $activation_rate_new,
            'appointment_from' => date('H:i:s', strtotime($choosen_date)),
            'appointment_to' => date('H:i:s', strtotime($second_date)),
        ]);
        $d = timing_duration::select('id')
            ->where('lead_no', $request->lead_id)
            ->first();
        $data  = timing_duration::findorfail($d->id);
        $data->lead_proceed_time = Carbon::now()->toDateTimeString();
        $data->verify_agent = auth()->user()->id;
        $data->save();


        foreach ($request->audio as $key => $val) {
            if (!empty($request->audio)) {
                // return $request->audio;
                // return $request->file();
                if ($file = $request->file('audio')) {
                    // LocalStorageCodeStart
                    // AzureCodeStart
                    $image2 = file_get_contents($file[$key]);
                    $originalFileName = time() . $file[$key]->getClientOriginalName();
                    $multi_filePath = 'audio' . '/' . $originalFileName;
                    \Storage::disk('azure')->put($multi_filePath, $image2);
                    // AzureCodeEnd
                    $ext = date('d-m-Y-H-i');
                    $mytime = Carbon::now();
                    $ext =  $mytime->toDateTimeString();
                    // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                    $name = $originalFileName;
                    $file[$key]->move('audio', $name);
                    $input['path'] = $name;
                    // LocalStorageCodeEnd
                    // AzureCodeEnd
                }
                //     $data2 = meeting_std::create([
                //         'meeting_id' => $meeting_id,
                //         'meeting_title' => $request->course_title,
                //         'std_id' => $val,
                //         'status' => 1,
                //     ]);
                // } else {
                //     echo "boom";
                // }
                $data = audio_recording::create([
                    // 'resource_name' => $request->resource_name,
                    'audio_file' => $name,
                    'username' => 'salman',
                    'lead_no' => $request->lead_id,
                    // 'teacher_id' => $request->teacher_id,
                    'status' => 1,
                ]);
            }
        }

        // $whatsapp = \App\Models\User::select('phone')
        // ->where('users.id',$lead_data->agent_code)
        // ->first();
        // if()
        // foreach (explode(',', $planName) as $k) {
        //     $plan = \App\Models\plan::where('id', $k)->first();
        // }
        // $plan_name = $plan->plan_name;
        // $data = $plan->data;
        // // notify()->success('Sim Type New-Verified succesfully');
        // // return redirect()->back()->withInput();
        // // return redirect(route('verification.index'));
        // // return "VIN";
        // // return $d->customer_name;
        // $a = "https://api.whatsapp.com/send?text= ** New-Verified **  %0a Lead No: $lead_data->lead_no %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a Number Selected: $lead_data->selected_number %0a Plan selected: $plan_name %0a Data : $data %0a  Activation: $lead_data->pay_status  %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: Salman %0a Verified";
        // return response()->json(['success' => $a]);
        //
        $llc = lead_location::where('lead_id', $request->lead_id)->first();
        if ($llc) {
        } else {

            //
            lead_location::create([
                'lead_id' => $request->lead_id,
                'location_url' => 'https://maps.google?q=0,0',
                'lat' => 0,
                'lng' => 0,
                'assign_to' => 136,
                // 'number_allowed' => $request->num_allowed,
                // 'duration' => $request->duration,
                // 'revenue' => $request->revenue,
                // 'free_minutes' => $request->free_min,
                'status' => 1,
            ]);
        }
        //
        return response()->json(['success' => "Successfully Verified"]);
        //
    }
    public function not_answer(Request $request)
    {
        $d = timing_duration::select('id')
            ->where('lead_no', $request->lead_id)
            ->first();
        $data  = timing_duration::findorfail($d->id);
        // $data->lead_proceed_time = Carbon::now()->toDateTimeString();
        $data->verify_agent = '';
        $data->save();
        //
        // return $request;
        // return $data = $request->saler_id;
        remark::create([
            'remarks' => 'Not Answer',
            'source' => 'Not Answer Source',
            'lead_status' => '0',
            'lead_id' => $request->lead_id,
            'lead_no' => $request->lead_id,
            'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
            'user_agent' => auth()->user()->name,
            'user_agent_id' => auth()->user()->id,
        ]);
        $lead = lead_sale::find($request->lead_id);
        // return
        // $uk = \App\Models\User::find($lead->saler_id);
        // return auth()->user()->id;
        // $data =
        // remark::select("remarks.*")
        // ->where("remarks.user_agent_id", auth()->user()->id)
        // ->where("remarks.lead_id", $request->id)
        // ->get();
        // $remarks = 'Lead ID: ' . $request->id . ' => Message: ' . $request->remarks;
        // event(new TaskEvent($remarks, $request->saler_id, $request->id, $uk->agent_code));
        // @role('sale')
        // \App\Models\remarks_notification::create([
        //     'leadid' => $request->id,
        //     'userid' => auth()->user()->id,
        //     'remarks' => $request->remarks,
        //     'group_id' => $uk->agent_code,
        //     'notification_type' => 'Chat',
        //     'is_read' => '0',
        // ]);
        //
        // return $lead->id;
        $ntc = lead_sale::select('call_centers.notify_email')
            ->Join(
                'users',
                'users.id',
                'lead_sales.saler_id'
            )
            ->Join(
                'call_centers',
                'call_centers.call_center_code',
                'users.agent_code'
            )
            ->where('lead_sales.id', $lead->id)->first();
        //
        if ($ntc) {
            $to = [
                [
                    'email' =>
                    'Coordinationalert@gmail.com',
                    'name' => 'Junaid',
                ],
                [
                    'email' => $ntc->notify_email, 'name' => 'Coordinator'
                ]
            ];
        } else {
            $to = [
                [
                    'email' =>
                    'Coordinationalert@gmail.com',
                    'name' => 'Junaid',
                ],
                [
                    'email' =>
                    'cc8alert@gmail.com',
                    'name' => 'CC8 Coordinator',
                ]
            ];
        }
        //

        $details = [
            'lead_id' => $lead->id,
            'lead_no' => $lead->lead_no,
            'customer_name' => $lead->customer_name,
            'customer_number' => $lead->customer_number,
            'selected_number' => $lead->selected_number,
            'remarks' => $request->remarks . ' ' . ' Remarks By ' . auth()->user()->name . ' (' .  auth()->user()->email . ')',
            'saler_name' => $lead->saler_name,
            // 'Plan' => $number,
            // 'AlternativeNumber' => $alternativeNumber,
        ];
        // $details = "";
        $subject = "";

        \Mail::to($to)
            // ->cc(['salmanahmed334@gmail.com'])
            ->send(new \App\Models\Mail\RemarksUpdate($details, $subject));
        //
        return response()->json(['success' => "Thanks for Feedback, You can try again later Now"]);
    }
    //
    public function CheckLeadNonVerify(Request $request)
    {
        // return $request;
        $l = lead_sale::findorfail($request->lead_id);
        foreach (explode(',', $l->selected_number) as $key => $val) {
            // foreach ($l->selected_number as $key => $val) {
            $count = numberdetail::select("numberdetails.id")
                ->where('numberdetails.number', $val)
                ->count();
            if ($count > 0) {
                $dn = numberdetail::select("numberdetails.id")
                    ->where('numberdetails.number', $val)
                    ->where('numberdetails.status', 'Available')
                    ->first();
                if ($dn) {
                    return "1";
                    // echo "1" . '<br>';
                } else {
                    return "0";
                }
            }
        }
    }
    public function FetchAgent(Request $request)
    {
        // return $request;


        $call_center = User::select("users.id", "users.name")
            ->where('users.agent_code', $request->id)
            ->whereIn('users.role', ['Sale', 'NumberAdmin'])
            ->get();
        return view('dashboard.add-target-form', compact('call_center'));
        // $collection = collect($users);
        // $abcd =  $collection->pluck('name', 'id');
        // return response()->json($abcd, 200);
    }
    public function AddTargetAdmin(Request $request)
    {
        // return $request;
        $validatedData = Validator::make($request->all(), [
            // 'name' => 'required',
            'month' =>  [
                'required',
                Rule::unique('target_assigner_users')
                    ->where('user', $request->agent_name)
            ],
            // 'month' => 'required|unique:target_assigner_manager,month',
            'target' => 'required',
            'agent_name' => 'required',
            // 'num_allowed' => 'required',
            // 'duration' => 'required',
            // 'revenue' => 'required',
            // 'free_min' => 'required',
            // 'plan_category' => 'required'
        ]);
        // $k =



        if ($validatedData->fails()) {
            // return redirect()->back()
            //     ->withErrors($validatedData)
            //     ->withInput();
            return response()->json(['error' => $validatedData->errors()->all()]);
        }
        foreach ($request->agent_name as $key => $agent_name) {

            $z = TargetAssignerUser::where('month', $request->month)->where('user', $agent_name)->first();
            if (!$z) {


                $k = TargetAssignerUser::create([
                    // 'name' => $request->name,
                    'month' => $request->month,
                    'target' => $request['target'][$key],
                    'user' => $agent_name,
                    'status' => '1'
                ]);
            }
        }
        return response()->json(['success' => 'Added new records, please wait meanwhile we are redirecting you....!!!']);

        // return redirect(route('user-target.index'));
    }
    public function update_user_choice(Request $request)
    {
        // return $request;
        // $data = User::findorfail($request->userid);
        if ($request->status == 'remove') {
            $check = SMSFinalUser::where('userid', $request->userid)->first();
            if ($check) {
                $check->delete();
                return 2;
            }
        } else {
            $data = SMSFinalUser::create([
                'userid' => $request->userid,
                'status' => '1',
            ]);
            return "1";
        }
    }
    //
    public function lead_edit(lead_sale $leadsale, $id)
    {
        // @php( $countries = \App\country_phone_code::all())
        // @php( $emirates = \App\emirate::all())
        // @php( $plans = \App\plan::wherestatus('1')->get())
        // @php( $elifes = \App\elife_plan::wherestatus('1')->get())
        // @php( $addons = \App\addon::wherestatus('1')->get())
        // @php( $users = \App\user::whererole('sale')->get())
        //
        $countries = \App\Models\country_phone_code::all();
        $emirates = \App\Models\emirate::all();
        $plans = \App\Models\plan::wherestatus('1')->get();
        $elifes = \App\Models\elife_plan::wherestatus('1')->get();
        $addons = \App\Models\addon::wherestatus('1')->get();
        $users = \App\Models\User::whererole('sale')->get();
        // return $id;
        $data = lead_sale::findorfail($id);
        $itproducts = \App\Models\it_products::wherestatus('1')->get();
        $remarks =
            remark::select("remarks.*")
            // ->where("remarks.user_agent_id", auth()->user()->id)
            ->where("remarks.lead_id", $id)
            ->get();
        // return "1";
        return view('agent.edit-lead', compact('countries', 'emirates', 'plans', 'elifes', 'addons', 'users', 'data', 'itproducts', 'remarks'));
    }
}
