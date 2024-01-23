<?php

namespace App\Http\Controllers;

use App\Events\TaskEvent;
use App\Models\lead_sale;
use App\Models\remark;
use App\Models\UserDevice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use OneSignal;


class ChatController extends Controller
{
    //
    public function ChatPost(Request $request)
    {
        // return $request;
        // return $data = $request->saler_id;
        remark::create([
            'remarks' => $request->remarks,
            'lead_status' => '0',
            'lead_id' => $request->id,
            'source' => 'Chat Box',
            'lead_no' => $request->id,
            'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
            'user_agent' => auth()->user()->name,
            'user_agent_id' => auth()->user()->id,
        ]);
        $lead = lead_sale::find($request->id);
        // return
        $uk = \App\Models\User::find($lead->saler_id);
        // return auth()->user()->id;
        $data =
            remark::select("remarks.date_time", 'users.name as user_agent', 'remarks.remarks')
            ->Join(
                'users',
                'users.id',
                'remarks.user_agent_id'
            )
            // ->where("remarks.user_agent_id", auth()->user()->id)
            ->where("remarks.lead_id", $request->id)
            ->get();
        $remarks = 'Lead ID: ' . $request->id . ' => Message: ' . $request->remarks;        // event(new TaskEvent($remarks, $request->saler_id, $request->id, $uk->agent_code));
        // @role('sale')
        \App\Models\remarks_notification::create([
            'leadid' => $request->id,
            'userid' => auth()->user()->id,
            'remarks' => $request->remarks,
            'group_id' => $uk->agent_code,
            'notification_type' => 'Chat',
            'is_read' => '0',
        ]);
        //
        // return $lead->id;
        $ntc = lead_sale::select('call_centers.notify_email', 'users.secondary_email', 'users.agent_code', 'call_centers.numbers', 'users.teamleader')
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
        $tl = \App\Models\User::where('id', $ntc->teamleader)->first();
        if ($tl) {
            $wapnumber = $tl->phone . ',' .  $ntc->numbers;
        } else {
            $wapnumber = $ntc->numbers;
        }
        // return $wapnumber;
        // $s = \App\Models\User::where('phone')
        // ->where('role','TeamLeader')
        // ->where('')->first();
        //
        $sms_data = "Lead No: " . $lead->lead_no;
        $sms_data .= "\nRemarks: " . $request->remarks;
        $sms_data .= "\nLink: " . route('view.lead', $lead->id);
        $sms_data .= "\nTime: " . \Carbon\Carbon::today()->now();
        //
        // ChatController::MySMSMachine($lead->id,$uk->agent_code, $sms_data);

        if ($ntc) {
            $to = [
                [
                    'email' =>
                    'Coordinationalert@gmail.com',
                    'name' => 'Junaid',
                ],
                // [
                //     'email' =>
                //     'Aroojmalikam776@gmail.com',
                //     'name' => 'Junaid',
                // ],
                // [
                //     'email' => $ntc->secondary_email, 'name' => 'Agent'
                // ],
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
        //
        $link = route('view.lead', $lead->id);
        $agent_code = $ntc->agent_code;
        // if($agent_code == 'CC3')
        //
        if ($lead->sim_type == 'HomeWifi') {
            $selected_number = 'HomeWifi';
        } else {
            $selected_number = $lead->selected_number;
        }
        $details = [
            'lead_id' => $lead->id,
            'lead_no' => $lead->lead_no,
            'customer_name' => $lead->customer_name,
            'customer_number' => $lead->customer_number,
            'selected_number' => $selected_number,
            'remarks' => $request->remarks . ' ' . ' Remarks By ' . auth()->user()->name . ' (' .  auth()->user()->email . ')',
            'remarks_final' => $request->remarks . ' ' . ' Remarks By ' . auth()->user()->name . ' (' .  auth()->user()->email . ')' . ' => Agent Name: ' . $lead->saler_name,
            'saler_name' => $lead->saler_name,
            'link' => $link,
            'agent_code' => $agent_code,
            'number' => $wapnumber,
            // 'Plan' => $number,
            // 'AlternativeNumber' => $alternativeNumber,
        ];
        // $details = "";
        $subject = "";

        // \Mail::to($to)
        // ->cc(['salmanahmed334@gmail.com'])
        // ->queue(new \App\Mail\RemarksUpdate($details, $subject));
        // ChatController::EmailToVerification($lead->id,$details);
        // ChatController::EmailToNewCord($lead->id,$details,$lead->emirates);
        ChatController::SendToWhatsApp($details);
        // if(auth()->user()->role != 'Emirate Coordinator'){
        ChatController::SendToWhatsAppCord($lead->id, $details);
        // }
        // $myod = UserDevice::where('user_id', $lead->saler_id)->orderBy('id', 'desc')->get();
        // // $myod = UserDevice::where('user_id', $lead->saler_id)->first();
        // $salmanID = UserDevice::where('user_id', 1)->first();
        // $player_id = array();
        // // $player_id[] = '336055ac-9a58-448c-883e-7f09de27b32d';
        // // $player_id[] = 'd83c51cf-9fd3-4ada-bf6b-e596dec84def';
        // foreach ($myod as $m) {
        //     $player_id[] = $m->os_player_id;
        // }
        // $player_id;
        // if ($myod) {
        //     $content = array(
        //         "en" => $request->remarks . ' ' . ' Remarks By ' . auth()->user()->name . ' (' .  auth()->user()->email . ')' . ' => Agent Name: ' . $lead->saler_name,
        //     );
        //     $heading = array(
        //         "en" => $lead->lead_no,
        //     );
        //     $fields = [
        //         // 'include_player_ids' => [
        //         // $myod->os_player_id,$salmanID->os_player_id, '336055ac-9a58-448c-883e-7f09de27b32d', 'd83c51cf-9fd3-4ada-bf6b-e596dec84def'
        //         // ],
        //         'include_player_ids' => $player_id,
        //         'data' => array(
        //             "id" => $lead->id,
        //             'type' => 'view_lead'
        //         ),
        //         'contents' => $content,
        //         'headings' => $heading
        //         // 'url' => route('view_lead_mobile_api', $lead->id),
        //     ];
        //     // $fields['include_player_ids'] = [$myod->os_player_id];
        //     // $message = 'hey!! this is test push yeah.!';
        //     // $message = $request->remarks;

        //     // $onesignal =  OneSignal::sendPush($fields);
        // }
        // ChatController::SMSToNewCord($lead->id,$details,$lead->emirates,$sms_data);
        // ChatController::MySMSMachine($lead->id,$uk->agent_code, $sms_data);



        // {{route('view.lead',$detail['lead_id'])}}
        // url to open lead";


        // if(auth()->user()->role != 'sale')
        // event(new MyEvent($remarks, $request->saler_id,$request->id,$uk->agent_code));
        // else
        return view('chat.chat', compact('data'));
    }
    //
    public function view_lead(Request $request)
    {
        $validator = Validator::make($request->all(), [ // <---
            // 'number' => 'required',
            // 'pid' => 'required',
            // 'type' => 'required',
            'id' => 'required',
            // 'date_time' => 'required',
            // 'userid' => 'required',
        ]);
        // return "zoom";
        // return $request->userid;
        // $customer_id =  $request->user()->id;


        if ($validator->fails()) {
            // here we return all the errors message
            // return response()->json(['errors' => $validator->errors()], 422);
            return [
                'ResponseCode' => '0',
                'ResponseMessage' => 'error',
                'ResponseData' => $validator->errors(),
            ];
        }
        $data = lead_sale::findorfail($request->id);
        return   $lead_data =   response()->json($data);
        // return
    }
    //
    public static function SendToWhatsApp($details)
    {

        $token = env('FACEBOOK_TOKEN');


        foreach (explode(',', $details['number']) as $nm) {


            //

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://graph.facebook.com/v14.0/166626349870802/messages',
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
            "name": "lead_update_not",
            "language": {
                "code": "en_US"
            },
            "components": [
                {
                    "type": "body",
                    "parameters": [
                        {
                            "type": "text",
                            "text": "' . $details['lead_no'] . '"
                        },
                        {
                            "type": "text",
                            "text": "' . $details['customer_name'] . '"
                        },
                        {
                            "type": "text",
                            "text": "' . $details['customer_number'] . '"
                        },
                        {
                            "type": "text",
                            "text": "' . $details['selected_number'] . '"
                        },
                        {
                            "type": "text",
                            "text": "' . $details['remarks_final'] . '"
                        },
                        {
                            "type": "text",
                            "text": "' . $details['link'] . '"
                        }

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
            // echo $response;
        }
    }
    public static function SendNewLeadMessage($details)
    {

        // return $details;

        $token = env('FACEBOOK_TOKEN');


        foreach (explode(',', $details['number']) as $nm) {


            //

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://graph.facebook.com/v14.0/166626349870802/messages',
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
            "name": "ver_me",
            "language": {
                "code": "en_US"
            },
            "components": [
                {
                    "type": "body",
                    "parameters": [
                        {
                            "type": "text",
                            "text": "' . $details['customer_number'] . '"
                        },
                        {
                            "type": "text",
                            "text": "' . $details['selected_number'] . '"
                        },
                        {
                            "type": "text",
                            "text": "' . $details['remarks_final'] . '"
                        },
                        {
                            "type": "text",
                            "text": "' . $details['language'] . '"
                        }

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
            // echo $response;
        }
    }
    //
    public static function SendWhatsAppDocs($details, $number)
    {
        $token = env('FACEBOOK_TOKEN');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/v14.0/166626349870802/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "messaging_product": "whatsapp",
                "recipient_type": "individual",
                "to": "923121337222",
                "type": "document",
                "document": {
                    "link": "https://prts-mppolice.nic.in/e-Courses/Content%20of%20English%20Typing.pdf",
                    "caption": "' . $details['lead_no'] . '\nCustomer Name: ' . $details['customer_name'] . '\nSelected Number: ' . $details['selected_number'] . '\nFind Attachment ☝️ : ' . '\nUrl: ' . $details['link'] . '"
                    }
                }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;
    }
    //
    public static function SendToWhatsAppFunction($details, $number)
    {

        $token = env('FACEBOOK_TOKEN');

        // return $details['lead_no'];

        foreach ($number as $nm) {


            //

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://graph.facebook.com/v14.0/166626349870802/messages',
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
            "name": "lead_update_not",
            "language": {
                "code": "en_US"
            },
            "components": [
                {
                    "type": "body",
                    "parameters": [
                        {
                            "type": "text",
                            "text": "' . $details['lead_no'] . '"
                        },
                        {
                            "type": "text",
                            "text": "' . $details['customer_name'] . '"
                        },
                        {
                            "type": "text",
                            "text": "' . $details['customer_number'] . '"
                        },
                        {
                            "type": "text",
                            "text": "' . $details['selected_number'] . '"
                        },
                        {
                            "type": "text",
                            "text": "' . $details['remarks_final'] . '"
                        },
                        {
                            "type": "text",
                            "text": "' . $details['link'] . '"
                        }

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
            // echo $response;
        }
    }
    public static function SendToWhatsAppCord($lead_id, $details)
    {

        $lead = lead_sale::select('lead_sales.saler_id', 'lead_sales.status', 'lead_sales.channel_type', 'lead_sales.emirates')->findorfail($lead_id);
        // //
        $startTime = \Carbon\Carbon::createFromFormat('H:i a', '06:00 AM');
        $endTime = \Carbon\Carbon::createFromFormat('H:i a', '04:00 PM');
        $Second = \Carbon\Carbon::createFromFormat('H:i a', '04:00 PM');
        $SecondEnd = \Carbon\Carbon::createFromFormat('H:i a', '11:59 PM');
        $currentTime = \Carbon\Carbon::now();
        if ($lead->channel_type == 'ConnectCC') {
            if ($lead->status != '1.01' || $lead->status != '1.04' || $lead->status != '1.15' || $lead->status != '1.03'  || $lead->status != '1.07' || $lead->status != '1.09') {
                // if ($currentTime->between($startTime, $endTime, true)) {
                //     // $number = '923245082322', '923249660466', '923367335361'
                //     //  923416712998
                //     // $user = \App\Models\User::whereIn('users.emirate', explode(',', $e))->get();


                //     // foreach($user as $u){
                //     //     $number = array($u->phone, '97143032014', '97143789200','923121337222');
                //         // $number = array('923121337222', '97143032014', '97143789200');
                //         // ChatController::SendToWhatsAppFunction($details,$number);
                //     // }
                // } else if ($currentTime->between($Second, $SecondEnd, true)) {
                //     $number = array('923249660466', '923027520611', '97143032014', '97143789200');
                //     ChatController::SendToWhatsAppFunction($details,$number);
                // }
                // $user_panel = \App\Models\User::select('users.phone', 'users.email')->Join(
                //     'assign_channels',
                //     'assign_channels.userid',
                //     'users.id'
                // )
                //     // ->whereRaw("find_in_set('" . $lead->emirates . "',users.emirate)")
                //     ->where('role', 'Emirate Coordinator')
                //     ->where('assign_channels.name', 'ConnectCC')
                //     ->where('users.id', auth()->user()->id)
                //     ->first();
                // if ($user_panel) {
                    $number = array('97167142777', '97167142705','97167142706','97167142707');
                    // $number = array('923416712998', '923245082322', '97143032014', '97143789200');
                    ChatController::SendToWhatsAppFunction($details, $number);
                // } else {

                //     $user = \App\Models\User::select('users.phone', 'users.email')->Join(
                //         'assign_channels',
                //         'assign_channels.userid',
                //         'users.id'
                //     )
                //         // ->whereRaw("find_in_set('" . $lead->emirates . "',users.emirate)")
                //         ->where('role', 'Emirate Coordinator')
                //         ->where('assign_channels.name', 'ExpressDial')
                //         ->get();
                //     foreach ($user as $u) {
                //         $number = array(
                //             $u->phone,
                //             '97167142705', '97167142706', '97167142707'
                //         );
                //         // $number = array('923416712998', '923245082322', '97143032014', '97143789200');
                //         ChatController::SendToWhatsAppFunction($details, $number);
                //     }
                // }
            }
        }
    }

    public static function EmailToVerification($lead_id, $details)
    {
        $lead = lead_sale::select('lead_sales.status')->findorfail($lead_id);
        if ($lead->status == '1.01' || $lead->status == '1.11' || $lead->status == '1.03' || $lead->status == '1.13') {
            $subject = "";
            $to = [
                [
                    // Email: verifcallmax@gmail.com
                    'email' =>
                    'verifcallmax@gmail.com',
                    'name' => 'Verification Team',
                ],
            ];
            \Mail::to($to)
                // ->cc(['salmanahmed334@gmail.com'])
                ->send(new \App\Mail\RemarksUpdate($details, $subject));
        }
    }
    //
    public static function EmailToNewCord($lead_id, $details, $emirates)
    {
        // $leadnumber = \App\Models\SMSFinalUser::select('users.phone', 'users.agent_code')
        // // ->Join('users', 'users.id', 's_m_s_final_users.userid')
        // ->where('s_m_s_final_users.userid', $lead->saler_id)
        //     ->first();
        // $leadnumber  = \App\Models\User::select('users.*')->where('id',$lead->saler_id)->first();
        // $coordinator = \App\Models\User::where('agent_code', $call_center)->where('role', 'manager')->first();
        // $maincord = \App\Models\User::where('role', 'MainCoordinator')->first();
        // $email_cord = \App\Models\User::where('email', )->first();

        $lead = lead_sale::select('lead_sales.saler_id', 'lead_sales.status', 'lead_sales.channel_type')->findorfail($lead_id);
        // //
        $startTime = \Carbon\Carbon::createFromFormat('H:i a', '06:00 AM');
        $endTime = \Carbon\Carbon::createFromFormat('H:i a', '04:00 PM');
        $Second = \Carbon\Carbon::createFromFormat('H:i a', '04:00 PM');
        $SecondEnd = \Carbon\Carbon::createFromFormat('H:i a', '11:59 PM');
        $currentTime = \Carbon\Carbon::now();
        if ($lead->channel_type == 'ExpressDial') {
            if ($lead->status != '1.01' || $lead->status != '1.04' || $lead->status != '1.15' || $lead->status != '1.03'  || $lead->status != '1.07' || $lead->status != '1.09') {
                if ($currentTime->between($startTime, $endTime, true)) {
                    $subject = "";
                    $to = [
                        [
                            'email' =>
                            'Aroojmalikam776@gmail.com',
                            'name' => 'Arooj',
                        ], [
                            // Email: verifcallmax@gmail.com
                            'email' =>
                            '16171519-142@uogsialkot.edu.pk',
                            'name' => 'Amara',
                        ],

                    ];
                    \Mail::to($to)
                        ->send(new \App\Mail\RemarksUpdate($details, $subject));
                } else if ($currentTime->between($Second, $SecondEnd, true)) {
                    $subject = "";
                    $to = [
                        [
                            'email' =>
                            'Aroojmalikam776@gmail.com',
                            'name' => 'Arooj',
                        ], [
                            // Email: verifcallmax@gmail.com
                            'email' =>
                            '16171519-142@uogsialkot.edu.pk',
                            'name' => 'Amara',
                        ],
                    ];
                    \Mail::to($to)
                        ->send(new \App\Mail\RemarksUpdate($details, $subject));
                }
            }
        } else if ($lead->channel_type == 'MWH') {
            if ($lead->status != '1.01' || $lead->status != '1.04' || $lead->status != '1.15' || $lead->status != '1.03'  || $lead->status != '1.07' || $lead->status != '1.09') {
                if ($currentTime->between($startTime, $endTime, true)) {
                    $subject = "";
                    $to = [
                        [
                            'email' =>
                            'rimxhamalik@gmail.com',
                            'name' => 'Rimsha',
                        ]
                    ];
                    \Mail::to($to)
                        ->send(new \App\Mail\RemarksUpdate($details, $subject));
                } else if ($currentTime->between($Second, $SecondEnd, true)) {
                    $subject = "";
                    $to = [
                        [
                            // Email: verifcallmax@gmail.com
                            'email' =>
                            'humanayyab96@gmail.com',
                            'name' => 'Huma',
                        ],
                    ];
                    \Mail::to($to)
                        ->send(new \App\Mail\RemarksUpdate($details, $subject));
                }
            }
        } else {
            if ($lead->status != '1.01' || $lead->status != '1.04' || $lead->status != '1.15' || $lead->status != '1.03'  || $lead->status != '1.07' || $lead->status != '1.09') {
                if ($currentTime->between($startTime, $endTime, true)) {
                    //
                    $subject = "";
                    $to = [
                        [
                            'email' =>
                            'rimxhamalik@gmail.com',
                            'name' => 'Rimsha',
                        ],
                        [
                            // Email: verifcallmax@gmail.com
                            'email' =>
                            'humanayyab96@gmail.com',
                            'name' => 'Huma',
                        ],
                    ];
                    \Mail::to($to)
                        ->send(new \App\Mail\RemarksUpdate($details, $subject));
                    //
                } else if ($currentTime->between($Second, $SecondEnd, true)) {
                    //
                    $subject = "";
                    $to = [
                        [
                            // Email: verifcallmax@gmail.com
                            'email' =>
                            'humanayyab96@gmail.com',
                            'name' => 'Huma',
                        ],

                    ];
                    \Mail::to($to)
                        ->send(new \App\Mail\RemarksUpdate($details, $subject));
                    //


                }
            }
        }
    }
    public static function SMSToNewCord($lead_id, $details, $emirates, $sms_data)
    {
        // $leadnumber = \App\Models\SMSFinalUser::select('users.phone', 'users.agent_code')
        // // ->Join('users', 'users.id', 's_m_s_final_users.userid')
        // ->where('s_m_s_final_users.userid', $lead->saler_id)
        //     ->first();
        // $leadnumber  = \App\Models\User::select('users.*')->where('id',$lead->saler_id)->first();
        // $coordinator = \App\Models\User::where('agent_code', $call_center)->where('role', 'manager')->first();
        // $maincord = \App\Models\User::where('role', 'MainCoordinator')->first();
        // $email_cord = \App\Models\User::where('email', )->first();

        $lead = lead_sale::select('lead_sales.saler_id', 'lead_sales.status', 'lead_sales.channel_type')->findorfail($lead_id);
        // //
        $startTime = \Carbon\Carbon::createFromFormat('H:i a', '06:00 AM');
        $endTime = \Carbon\Carbon::createFromFormat('H:i a', '04:00 PM');
        $Second = \Carbon\Carbon::createFromFormat('H:i a', '04:00 PM');
        $SecondEnd = \Carbon\Carbon::createFromFormat('H:i a', '11:59 PM');
        $currentTime = \Carbon\Carbon::now();
        // return $lead->channel_type;
        if ($lead->channel_type == 'ExpressDial') {
            if ($lead->status != '1.01' || $lead->status != '1.04' || $lead->status != '1.15' || $lead->status != '1.03'  || $lead->status != '1.07' || $lead->status != '1.09') {
                if ($currentTime->between($startTime, $endTime, true)) {
                    $nz = array('923245082322', '923249660466', '923367335361');
                    // $nz[] = ['923245082322', '923249660466', '923367335361', '923422708646'];
                    foreach ($nz as $n) {
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'https://sms.montymobile.com/API/SendSMS?username=DIALUPIT&apiId=tqv9qsQt&json=True&destination=' . $n . '&source=DIALUP-CC&text=' . urlencode($sms_data));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $result = curl_exec($ch);
                        if (curl_errno($ch)) {
                            echo 'Error:' . curl_error($ch);
                        }
                        curl_close($ch);
                    }
                } elseif ($currentTime->between($Second, $SecondEnd, true)) {
                    $nz = array('923245082322', '923249660466', '923367335361');
                    // $nz[] = ['923245082322', '923249660466', '923367335361', '923422708646'];
                    foreach ($nz as $n) {
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'https://sms.montymobile.com/API/SendSMS?username=DIALUPIT&apiId=tqv9qsQt&json=True&destination=' . $n . '&source=DIALUP-CC&text=' . urlencode($sms_data));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $result = curl_exec($ch);
                        if (curl_errno($ch)) {
                            echo 'Error:' . curl_error($ch);
                        }
                        curl_close($ch);
                    }
                }
            }
        } else if ($lead->channel_type == 'MWH') {
            if ($lead->status != '1.01' || $lead->status != '1.04' || $lead->status != '1.15' || $lead->status != '1.03'  || $lead->status != '1.07' || $lead->status != '1.09') {
                if ($currentTime->between($startTime, $endTime, true)) {
                    $nz = array('923021720006', '923451115328');
                    // $nz[] = ['923245082322', '923249660466', '923367335361', '923422708646'];
                    foreach ($nz as $n) {
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'https://sms.montymobile.com/API/SendSMS?username=DIALUPIT&apiId=tqv9qsQt&json=True&destination=' . $n . '&source=DIALUP-CC&text=' . urlencode($sms_data));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $result = curl_exec($ch);
                        if (curl_errno($ch)) {
                            echo 'Error:' . curl_error($ch);
                        }
                        curl_close($ch);
                    }
                } elseif ($currentTime->between($Second, $SecondEnd, true)) {
                    $nz = array('923021720006', '923256194347');
                    // $nz = array('923245082322', '923249660466', '923367335361');
                    // $nz[] = ['923245082322', '923249660466', '923367335361', '923422708646'];
                    foreach ($nz as $n) {
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'https://sms.montymobile.com/API/SendSMS?username=DIALUPIT&apiId=tqv9qsQt&json=True&destination=' . $n . '&source=DIALUP-CC&text=' . urlencode($sms_data));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $result = curl_exec($ch);
                        if (curl_errno($ch)) {
                            echo 'Error:' . curl_error($ch);
                        }
                        curl_close($ch);
                    }
                }
            }
        } else {
            // TTF
            if ($lead->status != '1.01' || $lead->status != '1.04' || $lead->status != '1.15' || $lead->status != '1.03'  || $lead->status != '1.07' || $lead->status != '1.09') {
                if ($currentTime->between($startTime, $endTime, true)) {
                    $nz = array('923367335361');
                    foreach ($nz as $n) {
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'https://sms.montymobile.com/API/SendSMS?username=DIALUPIT&apiId=tqv9qsQt&json=True&destination=' . $n . '&source=DIALUP-CC&text=' . urlencode($sms_data));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $result = curl_exec($ch);
                        if (curl_errno($ch)) {
                            echo 'Error:' . curl_error($ch);
                        }
                        curl_close($ch);
                    }
                } elseif ($currentTime->between($Second, $SecondEnd, true)) {
                    //
                    $nz = array('923367335361', '923221377230');
                    foreach ($nz as $n) {
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'https://sms.montymobile.com/API/SendSMS?username=DIALUPIT&apiId=tqv9qsQt&json=True&destination=' . $n . '&source=DIALUP-CC&text=' . urlencode($sms_data));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $result = curl_exec($ch);
                        if (curl_errno($ch)) {
                            echo 'Error:' . curl_error($ch);
                        }
                        curl_close($ch);
                    }
                    //
                }
            }

            //
        }
    }
    // $subject = "";
    // $to = [
    //     [
    //         // Email: verifcallmax@gmail.com
    //         'email' =>
    //         '16171519-142@uogsialkot.edu.pk',
    //         'name' => 'Amara',
    //     ],
    //     [
    //         'email' =>
    //         'rimxhamalik@gmail.com',
    //         'name' => 'Rimsha',
    //     ],
    //     [
    //         // Email: verifcallmax@gmail.com
    //         'email' =>
    //         'humanayyab96@gmail.com',
    //         'name' => 'Huma',
    //     ],
    //     [
    //         'email' =>
    //         'Aroojmalikam776@gmail.com',
    //         'name' => 'Arooj',
    //     ],
    //     [
    //         'email' =>
    //         'parhakooo@gmail.com',
    //         'name' => 'Salman',
    //     ],
    // ];
    // \Mail::to($to)
    //     // ->cc(['salmanahmed334@gmail.com'])
    // ->send(new \App\Mail\RemarksUpdate($details, $subject));


    //
    public static function MySMSMachine($lead_id, $call_center, $sms_data)
    {
        // return $call_center;
        if ($call_center == 'CC9' || $call_center == 'CC2' || $call_center == 'CC10') {
            // $number = array('7838908219', '7827250250');
            $lead = lead_sale::select('lead_sales.saler_id')->findorfail($lead_id);
            $leadnumber = \App\Models\SMSFinalUser::select('users.phone', 'users.agent_code')
                ->Join('users', 'users.id', 's_m_s_final_users.userid')
                ->where('s_m_s_final_users.userid', $lead->saler_id)
                ->first();
            $coordinator = \App\Models\User::where('agent_code', $call_center)->where('role', 'Cordination')->first();
            $manager = \App\Models\User::where('agent_code', $call_center)->where('role', 'Manager')->first();

            // //
            $startTime = \Carbon\Carbon::createFromFormat('H:i a', '09:00 AM');
            $endTime = \Carbon\Carbon::createFromFormat('H:i a', '04:00 PM');
            $Second = \Carbon\Carbon::createFromFormat('H:i a', '04:00 PM');
            $SecondEnd = \Carbon\Carbon::createFromFormat('H:i a', '01:00 AM');
            $currentTime = \Carbon\Carbon::now();

            if ($currentTime->between($startTime, $endTime, true)) {
                // dd('In Between');
                $maincord = \App\Models\User::where('role', 'MainCoordinator')->first();
                $emirate_cord = \App\Models\User::where('role', 'Emirate Coordinator')->where('emirate', $lead->emirates)->whereIn('email', ['amararoshandin@cord.com', 'rimshamalik@cord.com'])->first();
                // return "IN BETWEEN";
                $number = array();
                $number[] = $maincord->phone;
                $number[] = $emirate_cord->phone;
                $ch = curl_init();
                // $number = array('923121337222', '923422708646');
                foreach ($number as $numb) {
                    curl_setopt($ch, CURLOPT_URL, 'https://sms.montymobile.com/API/SendSMS?username=DIALUPIT&apiId=tqv9qsQt&json=True&destination=' . $numb . '&source=ACE&text=' . urlencode($sms_data));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                    $result = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'Error:' . curl_error($ch);
                    }
                }
                curl_close($ch);
            }
            if ($currentTime->between($Second, $SecondEnd, true)) {
                // dd('In Between');
                $maincord = \App\Models\User::where('role', 'MainCoordinator')->first();
                $emirate_cord = \App\Models\User::where('role', 'Emirate Coordinator')->where('emirate', $lead->emirates)->whereIn('email', ['aroojfatima@cord.com', 'humaasghar@cord.com'])->first();
                // return "IN BETWEEN";
                $number = array();
                $number[] = $maincord->phone;
                $number[] = $emirate_cord->phone;
                $ch = curl_init();
                // $number = array('923121337222', '923422708646');
                foreach ($number as $numb) {
                    curl_setopt($ch, CURLOPT_URL, 'https://sms.montymobile.com/API/SendSMS?username=DIALUPIT&apiId=tqv9qsQt&json=True&destination=' . $numb . '&source=ACE&text=' . urlencode($sms_data));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                    $result = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'Error:' . curl_error($ch);
                    }
                }
                curl_close($ch);
            }
            // $ch = curl_init();
            // //
            // $number = array();
            // $number[] = $leadnumber->phone;
            // $number[] = $coordinator->phone;
            // $number[] = $manager->phone;
            // // $number[] = $maincord->phone;
            // foreach ($number as $numb) {
            //     // curl_setopt($ch, CURLOPT_URL, 'https://sms.montymobile.com/API/SendSMS?username=DIALUPIT&apiId=tqv9qsQt&json=True&destination=' . $numb . '&source=ACE&text=' . urlencode($sms_data));
            //     curl_setopt($ch, CURLOPT_URL, 'https://www.fast2sms.com/dev/bulkV2?authorization=6wzISMUfNnu4hp2e8xoViC1EBK5D9qLrWYRFyljmkZ3d0HGtgaxtUH4XIqOMdhAEVle3j9CKR5grmvSa&route=v3&sender_id=TXTIND&message='. urlencode($sms_data) .'&language=english&flash=0&numbers=' . $numb);
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            //     $result = curl_exec($ch);
            //     if (curl_errno($ch)) {
            //         echo 'Error:' . curl_error($ch);
            //     }
            // }
            // curl_close($ch);
        } else {


            $lead = lead_sale::select('lead_sales.saler_id')->findorfail($lead_id);
            $leadnumber = \App\Models\SMSFinalUser::select('users.phone', 'users.agent_code')
                ->Join('users', 'users.id', 's_m_s_final_users.userid')
                ->where('s_m_s_final_users.userid', $lead->saler_id)
                ->first();
            $coordinator = \App\Models\User::where('agent_code', $call_center)->where('role', 'manager')->first();
            $maincord = \App\Models\User::where('role', 'MainCoordinator')->first();
            // //
            $startTime = \Carbon\Carbon::createFromFormat('H:i a', '09:00 AM');
            $endTime = \Carbon\Carbon::createFromFormat('H:i a', '04:00 PM');
            $Second = \Carbon\Carbon::createFromFormat('H:i a', '04:00 PM');
            $SecondEnd = \Carbon\Carbon::createFromFormat('H:i a', '01:00 AM');
            $currentTime = \Carbon\Carbon::now();

            if ($currentTime->between($startTime, $endTime, true)) {
                // dd('In Between');
                $maincord = \App\Models\User::where('role', 'MainCoordinator')->first();
                $emirate_cord = \App\Models\User::where('role', 'Emirate Coordinator')->where('emirate', $lead->emirates)->whereIn('email', ['amararoshandin@cord.com', 'rimshamalik@cord.com'])->first();
                // return "IN BETWEEN";
                $number = array();
                $number[] = $maincord->phone;
                $number[] = $emirate_cord->phone;
                $ch = curl_init();
                // $number = array('923121337222', '923422708646');
                foreach ($number as $numb) {
                    curl_setopt($ch, CURLOPT_URL, 'https://sms.montymobile.com/API/SendSMS?username=DIALUPIT&apiId=tqv9qsQt&json=True&destination=' . $numb . '&source=ACE&text=' . urlencode($sms_data));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                    $result = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'Error:' . curl_error($ch);
                    }
                }
                curl_close($ch);
            }
            if ($currentTime->between($Second, $SecondEnd, true)) {
                // dd('In Between');
                $maincord = \App\Models\User::where('role', 'MainCoordinator')->first();
                $emirate_cord = \App\Models\User::where('role', 'Emirate Coordinator')->where('emirate', $lead->emirates)->whereIn('email', ['aroojfatima@cord.com', 'humaasghar@cord.com'])->first();
                // return "IN BETWEEN";
                $number = array();
                $number[] = $maincord->phone;
                $number[] = $emirate_cord->phone;
                $ch = curl_init();
                // $number = array('923121337222', '923422708646');
                foreach ($number as $numb) {
                    curl_setopt($ch, CURLOPT_URL, 'https://sms.montymobile.com/API/SendSMS?username=DIALUPIT&apiId=tqv9qsQt&json=True&destination=' . $numb . '&source=ACE&text=' . urlencode($sms_data));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                    $result = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'Error:' . curl_error($ch);
                    }
                }
                curl_close($ch);
            }
            $number = array();
            $number[] = $leadnumber->phone;
            $number[] = $coordinator->phone;
            // $number[] = $maincord->phone;
            $ch = curl_init();
            // $number = array('923121337222', '923422708646');
            foreach ($number as $numb) {
                curl_setopt($ch, CURLOPT_URL, 'https://sms.montymobile.com/API/SendSMS?username=DIALUPIT&apiId=tqv9qsQt&json=True&destination=' . $numb . '&source=ACE&text=' . urlencode($sms_data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
            }
            curl_close($ch);
        }
    }
}
