<?php

namespace App\Http\Controllers;

use App\Models\dnc_checker_log;
use App\Models\dnclist;
use App\Models\dummy_data_test;
use App\Models\elife_bulker;
use App\Models\elife_log;
use App\Exports\whatsapp_export;
use App\Imports\elife_bulk;
use Illuminate\Http\Request;
use App\Imports\NumberImport;
use App\Imports\AreaUploader;
use App\Imports\EtiTest;
use App\Imports\mnp_bulk;
use App\Imports\NumberUpdate;
use App\Imports\UpdateDNC;
use App\Imports\UpdatePass;
use App\Imports\UpdateSR;
use App\Imports\UploadRegionNumber;
use App\Models\ain_data_manager_assigner;
use App\Models\main_data_user_assigned;
use App\Models\mnp_data;
use App\number_assigner;
use App\uploaderdatabank;
use App\Models\User;
use App\Models\whatsapp_number;
use App\Models\WhatsAppMnpBank;
use Maatwebsite\Excel\Facades\Excel;
use Storage;


class ImportExcelController extends Controller
{
    //
    public function dnc_checker(Request $request)
    {
        // return "Zoom";
        return view('dashboard.dnc-list');
    }
    //
    public function whatsapp_checker(Request $request)
    {
        // return "Zoom";
        return view('dashboard.whatsapp-list');
    }
    //
    public function whatsapp_session(Request $request)
    {
        return "zoom";
    }
    //
    public function dnc_validate(Request $request)
    {
        // return "Zoom";
        // return view('dashboard.dnc-validate');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://20.84.63.80:4000/sessions/add',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'id=DXB&isLegacy=false',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $l = $response;
        // $l = json_decode($response);
        return json_decode($response, true);
    }
    //
    public function dnc_checker_new(Request $request)
    {
        return view('agent.dnc-list-new');
    }
    public function dnc_checker_number(Request $request)
    {
        // return "0";
        return view('agent.dnc-list-number');
    }
    //
    public function dnc_checker_personal(Request $request)
    {
        return view('agent.dnc-list-personal');
    }
    //
    public function send_whatsapp_bulk(Request $request)
    {
        return view('agent.send-whatsapp-bulk');
    }
    //

    //
    public function send_whatsapp_bulk_test(Request $request)
    {
        // return $request;
        // return $message = $request->message;
        $similar_number = $request->similar_number;
        $agent_whatsapp_number = $request->agent_whatsapp;

        foreach ($tags = explode(PHP_EOL, $request->list) as $k => $key) {
            // return $key;
            // return $key;
            $count = count($tags);
            if ($count > 10) {
                return "Only 10 Messages Allowed at a time Sorry !!!";
            }
            \App\Http\Controllers\WhatsAppController::PromoWhatsApp($similar_number, $agent_whatsapp_number, $key);
            // return $response;
        }
        // return "zoom";
    }
    //
    public function reset_whatsapp_sender(Request $request)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.maytapi.com/api/' . trim($request->product_id) . '/' . trim($request->phone_id) . '/redeploy',
            // CURLOPT_URL => 'https://api.maytapi.com/api/327bae3d-ec31-4501-a7ce-dd082535f7d2/20810/redeploy',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'x-maytapi-key: ' . $request->token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
    //
    public function dnc_add_number(Request $request)
    {
        return view('agent.dnc-add-number');
    }
    //
    public function dnc_add_number_agent(Request $request)
    {
        return view('agent.dnc-add-number-agent');
    }
    //
    public function dnc_checker_rehan(Request $request)
    {
        return view('agent.dnc-list-rehan');
    }
    //
    public function dnc_checker_list_new(Request $request)
    {


        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {

            $region_name = $_SERVER["HTTP_CF_IPCOUNTRY"];
            $user_country = $_SERVER["HTTP_CF_IPCOUNTRY"];
            $ipaddress = $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            // $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}"));
            $details = $ipaddress;
        } else {
            $ipaddress =   $request->ip();
            $details = $ipaddress;

            // $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}"));
            // $user_country =   $details->country;
            // $region_name =   $details->region;
        }
        if (auth()->user()->agent_code == 'CC6' || auth()->user()->agent_code == 'CC07') {
            // return $ipaddress;

            // if($ipaddress != '137.59.221.156' || $ipaddress != '162.158.189.38' || $ipaddress != '2400:adc3:10d:4100:4e1:b2af:9dd5:c8ff') {
            if ($ipaddress != '137.59.221.156') {
                $details = json_encode($details);
                $q = dnc_checker_log::create(
                    [
                        'ip_address' => $ipaddress,
                        'FullAd' => $details,
                        'lat' => $request->myLat,
                        'lng' => $request->myLon,
                        'userid' => auth()->user()->id,
                    ]
                );
                return redirect()->back()
                    ->withErrors('Only 100 Numbers allowed at a time | يُسمح فقط بـ 300 رقمًا في المرة الواحدة')
                    ->withInput();
            }
        }
        // return $ipaddress;
        $list = $request->list;


        // $zzz = str_replace(PHP_EOL, ',', $request->list);
        // return $request->list;
        foreach ($tags = explode(PHP_EOL, $request->list) as $k => $key) {
            // return count($k);
            $count = count($tags);
            if ($count > 5000) {
                return redirect()->back()
                    ->withErrors('Only 100 Numbers allowed at a time | يُسمح فقط بـ 300 رقمًا في المرة الواحدة')
                    ->withInput();
                // return "Only 10 Messages Allowed at a time Sorry !!!";
            }
            // if(!empty($k)){
            $expr = '/^(?:\+971|971)(?:2|3|4|6|7|9|50|56|54|51|52|55|57|58)[0-9]{7}$/m';
            // $expr2 = '';
            $regex = '/^[0-9]{10}+$/';

            // $id = '0522221220';
            if (!empty($key)) {

                // $key = '971522221220';
                if (preg_match($expr, trim($key), $match) == 1 || preg_match($regex, trim($key), $match) == 1) {
                    // echo '###' . $match[0];
                    // if ($validator->fails()) {
                    // }
                }
                // elseif () {

                // }
                // else if()
                else {
                    // echo "what....?";
                    return redirect()->back()
                        ->withErrors('Invalid Number, Kindly use with Country Code: Example: 97152221230')
                        ->withInput();
                }
            }
            // return $key;
            $data[] = array(

                'receiver' => trim($key),
                'message' => 'TEST',

            );
            // $data[]['receiver'] = $key;
            // $data[]['message'] = 'Hello World';
            // echo $k . ' ' . $key . '</br>';
            // }

        }
        // return $data;
        // $data[]['receiver'] = $request->list;
        //  $data = array(
        //     ['receiver' => "923121337222", 'message' => "HelloWorld"],
        //     ['receiver' => "923422708646", 'message' => "HelloWorld"]
        // );
        $data_string = json_encode($data);
        // // // $data = '923121337222,923442708646';
        $curl = curl_init();
        $data_string;

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'http://20.84.63.80:4000/chats/testBulk?id=DXB',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS => $data_string,
        //     // CURLOPT_POSTFIELDS =>'[
        //     //     {
        //     //         "receiver": "923121337222",
        //     //         "message": "Hi bro, how are you?"
        //     //     },
        //     //     {
        //     //         "receiver": "9234227086461",
        //     //         "message": "I\'m fine, thank you."
        //     //     }
        //     // ]',
        //     CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/json'
        //     ),
        // ));

        // $response = curl_exec($curl);

        // curl_close($curl);
        // $b = json_decode($response, true);//here the json string is decoded and returned as associative array
        // $fl = $b['message'];
        // $sr = $b['success'];
        // // if($sr == false){
        // //     return $message = $b['message'];
        // // }
        // // return $response;
        // if(!isset($b['data']['errors'])){
        //     return redirect()->back()
        //         ->withErrors('No Number Found')
        //         ->withInput();
        // }
        foreach ($data as $d) {
            $d = $d['receiver'];
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'http://40.71.59.120:1337/' . $d . '/SendMsgToUser');
            // curl_setopt($ch, CURLOPT_URL, 'http://localhost:1337/'.$d.'/SendMsgToUser');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "Hello Fucking World");

            $headers = array();
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $z[] = $result = curl_exec($ch);
            $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($info == 500) {
                return redirect()->back()
                    ->withErrors('Invalid Number, No Numbers found for Calling - ')
                    ->withInput();
            }

            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
        }
        // return $z;
        // return json_decode($z);
        // return $result;
        // foreach($result as $z){
        // return $z['jid'];
        // }
        // return json_decode($result, true); //here the json string is decoded and returned as associative array
        // return $result;
        //  $z = $b['data']['errors'];
        $session_id  = rand(1, 122);

        // foreach($z[1]);
        // return $z[1];
        foreach ($z as $k) {
            // return $k;
            // return $k;
            $k =  json_decode($k, true);
            // return $k[''];
            if ($k['exists'] == true) {
                $pr[] = preg_replace('/@s.whatsapp.net/', ',', $k['jid']);
            }
            //  $z = explode('@',$k);
            //  foreach($z as $k){
            //      echo $k . '<br>';
            //  }
            // $l =  preg_replace('/971/', '0', $k, 3);
        }
        foreach ($pr as $p) {
            // return $p;
            // echo $p . '<br>';
            $z = str_replace(',', ' ', $p);
            // $z =
            // echo $z . '<br>';
            $l =  preg_replace('/971/', '0', $z, 3);
            // }

            // return "Zoom";
            // return $request;
            // $list = $request->list;
            // foreach(explode(PHP_EOL, $list) as $l){
            // return $l;
            // echo 'New Number ' . $l . '</br>';
            // dnclist::where('number')->
            $k = trim($l);
            dnclist::where('number', '=', $k)->first();
            if (dnclist::where('number', '=', $k)->first()) {
                // echo "DNC NUMBER " . ',' . $k . ',' . preg_replace('/0/', '971', $k, 1);
                // echo '<br>';
            } else {
                // echo "VALID NUMBER " . ',' . $k . ',' . preg_replace('/0/', '971', $k, 1);
                // echo "Valid Number " . ',' . $k;
                // echo '<br>';
                // $session_checker = whatsapp_number::where('session_id',$session_id)
                // ->where('userid',auth()->user()->id)->first();
                // if (!$session_checker) {
                $data = whatsapp_number::create([
                    'number' => $k,
                    'country_number' => preg_replace('/0/', '971', $k, 1),
                    'userid' => auth()->user()->id,
                    'session_id' => $session_id,
                ]);
                // }
            }
        }
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $region_name = $_SERVER["HTTP_CF_IPCOUNTRY"];
            $user_country = $_SERVER["HTTP_CF_IPCOUNTRY"];
            $ipaddress = $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            // $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}"));
            $details = $ipaddress;
        } else {
            $ipaddress =   $request->ip();
            $details = $ipaddress;

            // $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}"));
            // $user_country =   $details->country;
            // $region_name =   $details->region;
        }
        $details = json_encode($details);
        $q = dnc_checker_log::create(
            [
                'ip_address' => $ipaddress,
                'FullAd' => $details,
                'lat' => $request->myLat,
                'lng' => $request->myLon,
                'userid' => auth()->user()->id,
            ]
        );
        // return $q;
        return redirect()->route('MyWhatsApp', $session_id);
        // return $data;
        // return $response;
        // return json_decode($response);
        // $rr =  $response;
    }
    public function dnc_checker_number_new(Request $request)
    {
        $list = $request->list;


        // $zzz = str_replace(PHP_EOL, ',', $request->list);
        // return $request->list;
        foreach ($tags = explode(PHP_EOL, $request->list) as $k => $key) {
            // return count($k)
            $count = count($tags);
            if ($count > 100) {
                return redirect()->back()
                    ->withErrors('Only 100 Numbers allowed at a time | يُسمح فقط بـ 300 رقمًا في المرة الواحدة')
                    ->withInput();
                // return "Only 10 Messages Allowed at a time Sorry !!!";
            }
            // if(!empty($k)){
            $expr = '/^(?:\+971|971)(?:2|3|4|6|7|9|50|56|54|51|52|55|57|58)[0-9]{7}$/m';
            // $expr2 = '';
            $regex = '/^[0-9]{10}+$/';

            // $id = '0522221220';
            if (!empty($key)) {

                // $key = '971522221220';
                if (preg_match($expr, trim($key), $match) == 1 || preg_match($regex, trim($key), $match) == 1) {
                    // echo '###' . $match[0];
                    // if ($validator->fails()) {
                    // }
                }
                // elseif () {

                // }
                // else if()
                else {
                    // echo "what....?";
                    return redirect()->back()
                        ->withErrors('Invalid Number, Kindly use with Country Code: Example: 97152221230')
                        ->withInput();
                }
            }
            //
            // $z = str_replace(',', ' ', $p);
            // $z =
            // echo $z . '<br>';
            $l =  preg_replace('/971/', '0', $key, 3);
            // }

            // return "Zoom";
            // return $request;
            // $list = $request->list;
            // foreach(explode(PHP_EOL, $list) as $l){
            // return $l;
            // echo 'New Number ' . $l . '</br>';
            // dnclist::where('number')->
            $k = trim($l);
            $session_id  = rand(1, 122);

            // dnclist::where('number', '=', $k)->first();
            if (dnclist::where('number', '=', $k)->first()) {
                // echo "DNC NUMBER " . ',' . $k . ',' . preg_replace('/0/', '971', $k, 1);
                // echo '<br>';
            } else {
                // echo "VALID NUMBER " . ',' . $k . ',' . preg_replace('/0/', '971', $k, 1);
                // echo "Valid Number " . ',' . $k;
                // echo '<br>';
                // $session_checker = whatsapp_number::where('session_id',$session_id)
                // ->where('userid',auth()->user()->id)->first();
                // if (!$session_checker) {
                $data = whatsapp_number::create([
                    'number' => $k,
                    'country_number' => preg_replace('/0/', '971', $k, 1),
                    'userid' => auth()->user()->id,
                    'session_id' => $session_id,
                ]);
                // }
            }
        }
        // return "0";
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $region_name = $_SERVER["HTTP_CF_IPCOUNTRY"];
            $user_country = $_SERVER["HTTP_CF_IPCOUNTRY"];
            $ipaddress = $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}"));
        } else {
            $ipaddress =   $request->ip();
            $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}"));
            // $user_country =   $details->country;
            // $region_name =   $details->region;
        }
        $details = json_encode($details);
        $q = dnc_checker_log::create(
            [
                'ip_address' => $ipaddress,
                'FullAd' => $details,
                'lat' => $request->myLat,
                'lng' => $request->myLon,
                'userid' => auth()->user()->id,
            ]
        );
        // return $q;
        return redirect()->route('MyWhatsApp', $session_id);
        //

        // return $key;
        // $data[] = array(

        //         'receiver' => trim($key),
        //         'message' => 'TEST',

        // );
        // $data[]['receiver'] = $key;
        // $data[]['message'] = 'Hello World';
        // echo $k . ' ' . $key . '</br>';
        // }

        // }
        // return $data;
        // $data[]['receiver'] = $request->list;
        //  $data = array(
        //     ['receiver' => "923121337222", 'message' => "HelloWorld"],
        //     ['receiver' => "923422708646", 'message' => "HelloWorld"]
        // );
        // $data_string = json_encode($data);
        // // // $data = '923121337222,923442708646';
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'http://20.84.63.80:4000/chats/testBulk?id=DXB',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS => $data_string,
        //     // CURLOPT_POSTFIELDS =>'[
        //     //     {
        //     //         "receiver": "923121337222",
        //     //         "message": "Hi bro, how are you?"
        //     //     },
        //     //     {
        //     //         "receiver": "9234227086461",
        //     //         "message": "I\'m fine, thank you."
        //     //     }
        //     // ]',
        //     CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/json'
        //     ),
        // ));

        // $response = curl_exec($curl);

        // curl_close($curl);
        // $b = json_decode($response, true);//here the json string is decoded and returned as associative array
        // $fl = $b['message'];
        // $sr = $b['success'];
        // // if($sr == false){
        // //     return $message = $b['message'];
        // // }
        // // return $response;
        // if(!isset($b['data']['errors'])){
        //     return redirect()->back()
        //         ->withErrors('No Number Found')
        //         ->withInput();
        // }
        //  $z = $b['data']['errors'];
        // $session_id  = rand(1, 122);

        // // foreach($z[1]);
        // // return $z[1];
        // foreach($z as $k){
        //     // return $k;
        //     $pr[] = preg_replace('/@s.whatsapp.net/', ',', $k);
        //     //  $z = explode('@',$k);
        //     //  foreach($z as $k){
        //     //      echo $k . '<br>';
        //     //  }
        //     // $l =  preg_replace('/971/', '0', $k, 3);
        // }
        // foreach($data as $p){
        // echo $p . '<br>';
        //     $z = str_replace(',', ' ', $p);
        //     // $z =
        //     // echo $z . '<br>';
        //     $l =  preg_replace('/971/', '0', $z, 3);
        //     // }

        //     // return "Zoom";
        //     // return $request;
        //     // $list = $request->list;
        //     // foreach(explode(PHP_EOL, $list) as $l){
        //     // return $l;
        //     // echo 'New Number ' . $l . '</br>';
        //     // dnclist::where('number')->
        //     $k = trim($l);
        //     dnclist::where('number', '=', $k)->first();
        //     if (dnclist::where('number', '=', $k)->first()) {
        //         // echo "DNC NUMBER " . ',' . $k . ',' . preg_replace('/0/', '971', $k, 1);
        //         // echo '<br>';
        //     } else {
        //         // echo "VALID NUMBER " . ',' . $k . ',' . preg_replace('/0/', '971', $k, 1);
        //         // echo "Valid Number " . ',' . $k;
        //         // echo '<br>';
        //         // $session_checker = whatsapp_number::where('session_id',$session_id)
        //         // ->where('userid',auth()->user()->id)->first();
        //         // if (!$session_checker) {
        //         $data = whatsapp_number::create([
        //             'number' => $k,
        //             'country_number' => preg_replace('/0/', '971', $k, 1),
        //             'userid' => auth()->user()->id,
        //             'session_id' => $session_id,
        //         ]);
        //         // }
        //     }
        // }
        // if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        //     $region_name = $_SERVER["HTTP_CF_IPCOUNTRY"];
        //     $user_country = $_SERVER["HTTP_CF_IPCOUNTRY"];
        //     $ipaddress = $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        //     $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}"));
        // } else {
        //     $ipaddress =   $request->ip();
        //     $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}"));
        //     // $user_country =   $details->country;
        //     // $region_name =   $details->region;
        // }
        // $details = json_encode($details);
        // $q = dnc_checker_log::create(
        //     [
        //         'ip_address' => $ipaddress,
        //         'FullAd' => $details,
        //         'lat' => $request->myLat,
        //         'lng' => $request->myLon,
        //         'userid' => auth()->user()->id,
        //     ]
        // );
        // // return $q;
        // return redirect()->route('MyWhatsApp', $session_id);
        // return $data;
        // return $response;
        // return json_decode($response);
        // $rr =  $response;
    }
    //
    public function whatsapp_checker_final(Request $request)
    {

        $list = $request->list;


        // $zzz = str_replace(PHP_EOL, ',', $request->list);
        // return $request->list;
        $session_id  = auth()->user()->id;

        foreach ($tags = explode(PHP_EOL, $request->list) as $k => $key) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://localhost:8080/api/sendtext',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('sessions' => 'salman', 'target' => $key, 'message' => 'S2'),
                CURLOPT_HTTPHEADER => array(
                    'Cookie: connect.sid=s%3A6_z9Ozdv6NhBHVi8NBGkjyRVqCEldV80.RLe7n6kq4b%2F%2BI9jakUh2ktbMYzYBxzNcAiZEFinIOag'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $b = json_decode($response, true); //here the json string is decoded and returned as associative array
            $fl = $b['message'];
            $sr = $b['status'];
            if ($sr == 200) {
                $dl = whatsapp_number::where('number', $fl)->first();
                if (!$dl) {
                    $data = whatsapp_number::create([
                        'number' => $fl,
                        'country_number' => $fl,
                        // 'country_number' => preg_replace('/0/', '971', $k, 1),
                        'userid' => auth()->user()->id,
                        'session_id' => auth()->user()->id,
                    ]);
                }
            }
        }
        return redirect()->route('MyWhatsApp', $session_id);

        return 'done';
        //         // return $fl;
        //     // for($key= 971521000002; $key<= 971521000102; $key++){
        //         // return count($k);
        //         // return $key;
        //         // $count = count($tags);
        //         // if ($count > 2000) {
        //         //     return redirect()->back()
        //         //         ->withErrors('Only 100 Numbers allowed at a time | يُسمح فقط بـ 300 رقمًا في المرة الواحدة')
        //         //         ->withInput();
        //         //     // return "Only 10 Messages Allowed at a time Sorry !!!";
        //         // }
        //         // if(!empty($k)){
        //         $expr = '/^(?:\+971|971)(?:2|3|4|6|7|9|50|56|54|51|52|55|57|58)[0-9]{7}$/m';
        //         // $expr2 = '';
        //         $regex = '/^[0-9]{10}+$/';

        //         // $id = '0522221220';
        //         if(!empty($key)){

        //             // $key = '971522221220';
        //             if (preg_match($expr, trim($key), $match) == 1 || preg_match($regex, trim($key), $match) == 1) {
        //                 // echo '###' . $match[0];
        //                 // if ($validator->fails()) {
        //                     // }
        //             }
        //             // elseif () {

        //             // }
        //             // else if()
        //             else{
        //                     // echo "what....?";
        //                 return redirect()->back()
        //                 ->withErrors('Invalid Number, Kindly use with Country Code: Example: 97152221230')
        //                 ->withInput();
        //             }
        //         }
        //         // return $key;
        //             $data[] = array(

        //                     'receiver' => trim($key),
        //                     'message' => 'TEST',

        //             );
        //             // $data[]['receiver'] = $key;
        //             // $data[]['message'] = 'Hello World';
        //             // echo $k . ' ' . $key . '</br>';
        //         // }

        //     }
        //     // return $data;
        //     // $data[]['receiver'] = $request->list;
        //     //  $data = array(
        //     //     ['receiver' => "923121337222", 'message' => "HelloWorld"],
        //     //     ['receiver' => "923422708646", 'message' => "HelloWorld"]
        //     // );
        //     $data_string = json_encode($data);
        //     // // // $data = '923121337222,923442708646';
        //     $curl = curl_init();

        //     curl_setopt_array($curl, array(
        //         // CURLOPT_URL => 'http://20.84.63.80:4000/chats/testBulk?id=DXB',
        //         CURLOPT_URL => 'http://20.84.63.80:4000/chats/testBulk?id=DXB',
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_ENCODING => '',
        //         CURLOPT_MAXREDIRS => 10,
        //         CURLOPT_TIMEOUT => 0,
        //         CURLOPT_FOLLOWLOCATION => true,
        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST => 'POST',
        //         CURLOPT_POSTFIELDS => $data_string,
        //         // CURLOPT_POSTFIELDS =>'[
        //         //     {
        //         //         "receiver": "923121337222",
        //         //         "message": "Hi bro, how are you?"
        //         //     },
        //         //     {
        //         //         "receiver": "9234227086461",
        //         //         "message": "I\'m fine, thank you."
        //         //     }
        //         // ]',
        //         CURLOPT_HTTPHEADER => array(
        //             'Content-Type: application/json'
        //         ),
        //     ));

        //    return $response = curl_exec($curl);

        //     curl_close($curl);
        //     $b = json_decode($response, true);//here the json string is decoded and returned as associative array
        //     $fl = $b['message'];
        //     $sr = $b['success'];
        //     // if($sr == false){
        //     //     return $message = $b['message'];
        //     // }
        //     // return $response;
        //     if(!isset($b['data']['errors'])){
        //         return redirect()->back()
        //             ->withErrors('No Number Found')
        //             ->withInput();
        //     }
        //      $z = $b['data']['errors'];
        //     $session_id  = rand(1, 122);

        //     // foreach($z[1]);
        //     // return $z[1];
        //     foreach($z as $k){
        //         // return $k;
        //         $pr[] = preg_replace('/@s.whatsapp.net/', ',', $k);
        //         //  $z = explode('@',$k);
        //         //  foreach($z as $k){
        //         //      echo $k . '<br>';
        //         //  }
        //         // $l =  preg_replace('/971/', '0', $k, 3);
        //     }
        //     foreach($pr as $p){
        //         // echo $p . '<br>';
        //          $z = str_replace(',', ' ', $p);
        //         // $z =
        //         // echo $z . '<br>';
        //         $l =  preg_replace('/971/', '0', $z, 3);
        //         // }

        //         // return "Zoom";
        //         // return $request;
        //         // $list = $request->list;
        //         // foreach(explode(PHP_EOL, $list) as $l){
        //         // return $l;
        //         // echo 'New Number ' . $l . '</br>';
        //         // dnclist::where('number')->
        //         $k = trim($l);
        //         // dnclist::where('number', '=', $k)->first();
        //         // if (dnclist::where('number', '=', $k)->first()) {
        //         //     // echo "DNC NUMBER " . ',' . $k . ',' . preg_replace('/0/', '971', $k, 1);
        //         //     // echo '<br>';
        //         // } else {
        //             // echo "VALID NUMBER " . ',' . $k . ',' . preg_replace('/0/', '971', $k, 1);
        //             // echo "Valid Number " . ',' . $k;
        //             // echo '<br>';
        //             // $session_checker = whatsapp_number::where('session_id',$session_id)
        //             // ->where('userid',auth()->user()->id)->first();
        //             // if (!$session_checker) {
        //             $data = whatsapp_number::create([
        //                 'number' => $k,
        //                 'country_number' => preg_replace('/0/', '971', $k, 1),
        //                 'userid' => auth()->user()->id,
        //                 'session_id' => $session_id,
        //             ]);
        //             // }
        //         // }
        //     }
        //     if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        //         $region_name = $_SERVER["HTTP_CF_IPCOUNTRY"];
        //         $user_country = $_SERVER["HTTP_CF_IPCOUNTRY"];
        //         $ipaddress = $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        //         $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}"));
        //     } else {
        //         $ipaddress =   $request->ip();
        //         $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}"));
        //         // $user_country =   $details->country;
        //         // $region_name =   $details->region;
        //     }
        //     $details = json_encode($details);
        //     $q = dnc_checker_log::create(
        //         [
        //             'ip_address' => $ipaddress,
        //             'FullAd' => $details,
        //             'lat' => $request->myLat,
        //             'lng' => $request->myLon,
        //             'userid' => auth()->user()->id,
        //         ]
        //     );
        //     // return $q;
        //     return redirect()->route('MyWhatsApp', $session_id);
        //     // return $data;
        //     // return $response;
        //     // return json_decode($response);
        //     // $rr =  $response;
    }
    //
    public function dnc_add_number_new(Request $request)
    {
        $list = $request->list;


        // $zzz = str_replace(PHP_EOL, ',', $request->list);
        // return $request->list;
        foreach (explode(PHP_EOL, $request->list) as $k => $key) {
            //
            if (!dnclist::where('number', '=', $key)->exists()) {
                $data = dnclist::create(
                    [
                        'number' => $key,
                    ]
                );
            }
            //
        }
        // return "1";
        return back()->with('success', 'Add successfully.');

        // return redirect(route('dnc_add_number'));
        // return $data;
        // return $response;
        // return json_decode($response);
        // $rr =  $response;
    }
    public function dnc_checker_personal_new(Request $request)
    {
        $list = $request->list;


        // $zzz = str_replace(PHP_EOL, ',', $request->list);
        // return $request->list;
        foreach (explode(PHP_EOL, $request->list) as $k => $key) {
            // return count($k)
            // if(!empty($k)){
            $expr = '/^(?:\+971|971)(?:2|3|4|6|7|9|50|54|56|51|52|55|57|58)[0-9]{7}$/m';
            // $expr2 = '';
            $regex = '/^[0-9]{10}+$/';

            // $id = '0522221220';
            if (!empty($key)) {

                // $key = '971522221220';
                if (preg_match($expr, trim($key), $match) == 1 || preg_match($regex, trim($key), $match) == 1) {
                    // echo '###' . $match[0];
                    // if ($validator->fails()) {
                    // }
                }
                // elseif () {

                // }
                // else if()
                else {
                    // echo "what....?";
                    return redirect()->back()
                        ->withErrors('Invalid Number, Kindly use with Country Code: Example: 97152221230')
                        ->withInput();
                }
            }
            // return $key;
            $data[] = array(

                'receiver' => trim($key),
                'message' => 'TEST',

            );
            // $data[]['receiver'] = $key;
            // $data[]['message'] = 'Hello World';
            // echo $k . ' ' . $key . '</br>';
            // }

        }
        // return $data;
        // $data[]['receiver'] = $request->list;
        //  $data = array(
        //     ['receiver' => "923121337222", 'message' => "HelloWorld"],
        //     ['receiver' => "923422708646", 'message' => "HelloWorld"]
        // );
        $data_string = json_encode($data);
        // // // $data = '923121337222,923442708646';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://20.84.63.80:4000/chats/testBulk?id=DXB',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data_string,
            // CURLOPT_POSTFIELDS =>'[
            //     {
            //         "receiver": "923121337222",
            //         "message": "Hi bro, how are you?"
            //     },
            //     {
            //         "receiver": "9234227086461",
            //         "message": "I\'m fine, thank you."
            //     }
            // ]',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $b = json_decode($response, true); //here the json string is decoded and returned as associative array
        $fl = $b['message'];
        $sr = $b['success'];
        // if($sr == false){
        //     return $message = $b['message'];
        // }
        // return $response;
        if (!isset($b['data']['errors'])) {
            return redirect()->back()
                ->withErrors('No Number Found')
                ->withInput();
        }
        $z = $b['data']['errors'];
        $session_id  = rand(1, 122);

        // foreach($z[1]);
        // return $z[1];
        foreach ($z as $k) {
            // return $k;
            $pr[] = preg_replace('/@s.whatsapp.net/', ',', $k);
            //  $z = explode('@',$k);
            //  foreach($z as $k){
            //      echo $k . '<br>';
            //  }
            // $l =  preg_replace('/971/', '0', $k, 3);
        }
        foreach ($pr as $p) {
            // echo $p . '<br>';
            $z = str_replace(',', ' ', $p);
            // $z =
            // echo $z . '<br>';
            $l =  preg_replace('/971/', '0', $z, 3);
            // }

            // return "Zoom";
            // return $request;
            // $list = $request->list;
            // foreach(explode(PHP_EOL, $list) as $l){
            // return $l;
            // echo 'New Number ' . $l . '</br>';
            // dnclist::where('number')->
            $k = trim($l);
            dnclist::where('number', '=', $k)->first();
            if (dnclist::where('number', '=', $k)->first()) {
                // echo "DNC NUMBER " . ',' . $k . ',' . preg_replace('/0/', '971', $k, 1);
                // echo '<br>';
            } else {
                // echo "VALID NUMBER " . ',' . $k . ',' . preg_replace('/0/', '971', $k, 1);
                // echo "Valid Number " . ',' . $k;
                // echo '<br>';
                // $session_checker = whatsapp_number::where('session_id',$session_id)
                // ->where('userid',auth()->user()->id)->first();
                // if (!$session_checker) {
                $data = whatsapp_number::create([
                    'number' => $k,
                    'country_number' => preg_replace('/0/', '971', $k, 1),
                    'userid' => auth()->user()->id,
                    'session_id' => $session_id,
                ]);
                // }
            }
        }
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $region_name = $_SERVER["HTTP_CF_IPCOUNTRY"];
            $user_country = $_SERVER["HTTP_CF_IPCOUNTRY"];
            $ipaddress = $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}"));
        } else {
            $ipaddress =   $request->ip();
            $details = $ipaddress;
            // $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}"));
            // $user_country =   $details->country;
            // $region_name =   $details->region;
        }
        $details = json_encode($details);
        $q = dnc_checker_log::create(
            [
                'ip_address' => $ipaddress,
                'FullAd' => $details,
                'lat' => $request->myLat,
                'lng' => $request->myLon,
                'userid' => auth()->user()->id,
            ]
        );
        // return $q;
        return redirect()->route('MyWhatsApp', $session_id);
        // return $data;
        // return $response;
        // return json_decode($response);
        // $rr =  $response;
    }
    //
    //
    public function dnc_checker_list_rehan(Request $request)
    {
        $list = $request->list;


        // $zzz = str_replace(PHP_EOL, ',', $request->list);
        // return $request->list;
        // foreach (explode(PHP_EOL, $request->list) as $k => $key) {
        //     // return count($k)
        //     // if(!empty($k)){
        //     $expr = '/^(?:\+971|971)(?:2|3|4|6|7|9|50|51|52|55|56|58|54)[0-9]{7}$/m';
        //     // $expr2 = '';
        //     $regex = '/^[0-9]{10}+$/';

        //     // $id = '0522221220';
        //     // if(!empty($key)){

        //     //     // $key = '971522221220';
        //     //     if (preg_match($expr, trim($key), $match) == 1 || preg_match($regex, trim($key), $match) == 1) {
        //     //         // echo '###' . $match[0];
        //     //         // if ($validator->fails()) {
        //     //             // }
        //     //     }
        //     //     // elseif () {

        //     //     // }
        //     //     // else if()
        //     //     else{
        //     //             // echo "what....?";
        //     //         return redirect()->back()
        //     //         ->withErrors('Invalid Number, Kindly use with Country Code: Example: 97152221230')
        //     //         ->withInput();
        //     //     }
        //     // }
        //     // return $key;
        //         $data[] = array(

        //                 'receiver' => trim($key),
        //                 'message' => 'TEST',

        //         );
        //         // $data[]['receiver'] = $key;
        //         // $data[]['message'] = 'Hello World';
        //         // echo $k . ' ' . $key . '</br>';
        //     // }

        // }
        // // return $data;
        // // $data[]['receiver'] = $request->list;
        // //  $data = array(
        // //     ['receiver' => "923121337222", 'message' => "HelloWorld"],
        // //     ['receiver' => "923422708646", 'message' => "HelloWorld"]
        // // );
        //  $data_string = json_encode($data);
        // // // // $data = '923121337222,923442708646';
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'http://20.84.63.80:4000/chats/testBulk?id=DXB',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS => $data_string,
        //     // CURLOPT_POSTFIELDS =>'[
        //     //     {
        //     //         "receiver": "923121337222",
        //     //         "message": "Hi bro, how are you?"
        //     //     },
        //     //     {
        //     //         "receiver": "9234227086461",
        //     //         "message": "I\'m fine, thank you."
        //     //     }
        //     // ]',
        //     CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/json'
        //     ),
        // ));

        // $response = curl_exec($curl);

        // curl_close($curl);
        // $b = json_decode($response, true);//here the json string is decoded and returned as associative array
        // $fl = $b['message'];
        // $sr = $b['success'];
        // // if($sr == false){
        // //     return $message = $b['message'];
        // // }
        // // return $response;
        // if(!isset($b['data']['errors'])){
        //     return redirect()->back()
        //         ->withErrors('No Number Found')
        //         ->withInput();
        // }
        //  $z = $b['data']['errors'];
        // $session_id  = rand(1, 122);

        // // foreach($z[1]);
        // // return $z[1];
        // foreach($z as $k){
        //     // return $k;
        //     $pr[] = preg_replace('/@s.whatsapp.net/', ',', $k);
        //     //  $z = explode('@',$k);
        //     //  foreach($z as $k){
        //     //      echo $k . '<br>';
        //     //  }
        //     // $l =  preg_replace('/971/', '0', $k, 3);
        // }
        // foreach($pr as $p){
        //     // echo $p . '<br>';
        //      $z = str_replace(',', ' ', $p);
        //     // $z =
        //     // echo $z . '<br>';
        //     $l =  preg_replace('/971/', '0', $z, 3);
        //     // }

        //     // return "Zoom";
        //     // return $request;
        //     // $list = $request->list;
        //     // foreach(explode(PHP_EOL, $list) as $l){
        //     // return $l;
        //     // echo 'New Number ' . $l . '</br>';
        //     // dnclist::where('number')->
        //     // $k = trim($l);
        //     // dnclist::where('number', '=', $k)->first();
        //     // if (dnclist::where('number', '=', $k)->first()) {
        //         // echo "DNC NUMBER " . ',' . $k . ',' . preg_replace('/0/', '971', $k, 1);
        //         // echo '<br>';
        //     // } else {
        //         // echo "VALID NUMBER " . ',' . $k . ',' . preg_replace('/0/', '971', $k, 1);
        //         // echo "Valid Number " . ',' . $k;
        //         // echo '<br>';
        //         // $session_checker = whatsapp_number::where('session_id',$session_id)
        //         // ->where('userid',auth()->user()->id)->first();
        //         // if (!$session_checker) {
        //         $data = whatsapp_number::create([
        //             'number' => $z,
        //             'country_number' => $z,
        //             'userid' => auth()->user()->id,
        //             'session_id' => $session_id,
        //         ]);
        //         // }
        //     // }
        // }
        // if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        //     $region_name = $_SERVER["HTTP_CF_IPCOUNTRY"];
        //     $user_country = $_SERVER["HTTP_CF_IPCOUNTRY"];
        //     $ipaddress = $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        //     $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}"));
        // } else {
        //     $ipaddress =   $request->ip();
        //     $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}"));
        //     // $user_country =   $details->country;
        //     // $region_name =   $details->region;
        // }
        // $details = json_encode($details);
        // $q = dnc_checker_log::create(
        //     [
        //         'ip_address' => $ipaddress,
        //         'FullAd' => $details,
        //         'lat' => $request->myLat,
        //         'lng' => $request->myLon,
        //         'userid' => auth()->user()->id,
        //     ]
        // );
        // // return $q;
        // return redirect()->route('MyWhatsApp', $session_id);
        // return $data;
        // return $response;
        // return json_decode($response);
        // $rr =  $response;

        foreach ($tags = explode(PHP_EOL, $request->list) as $k => $key) {
            // return count($k)
            $count = count($tags);
            if ($count > 5000) {
                return redirect()->back()
                    ->withErrors('Only 100 Numbers allowed at a time | يُسمح فقط بـ 300 رقمًا في المرة الواحدة')
                    ->withInput();
                // return "Only 10 Messages Allowed at a time Sorry !!!";
            }
            // if(!empty($k)){
            $expr = '/^(?:\+971|971)(?:2|3|4|6|7|9|50|56|54|51|52|55|57|58)[0-9]{7}$/m';
            // $expr2 = '';
            $regex = '/^[0-9]{10}+$/';

            // $id = '0522221220';
            // if (!empty($key)) {

            //     // $key = '971522221220';
            //     if (preg_match($expr, trim($key), $match) == 1 || preg_match($regex, trim($key), $match) == 1) {
            //         // echo '###' . $match[0];
            //         // if ($validator->fails()) {
            //         // }
            //     }
            //     // elseif () {

            //     // }
            //     // else if()
            //     else {
            //         // echo "what....?";
            //         return redirect()->back()
            //             ->withErrors('Invalid Number, Kindly use with Country Code: Example: 97152221230')
            //             ->withInput();
            //     }
            // }
            // return $key;
            $data[] = array(

                'receiver' => trim($key),
                'message' => 'TEST',

            );
            // $data[]['receiver'] = $key;
            // $data[]['message'] = 'Hello World';
            // echo $k . ' ' . $key . '</br>';
            // }

        }
        // return $data;
        // $data[]['receiver'] = $request->list;
        //  $data = array(
        //     ['receiver' => "923121337222", 'message' => "HelloWorld"],
        //     ['receiver' => "923422708646", 'message' => "HelloWorld"]
        // );
        $data_string = json_encode($data);
        // // // $data = '923121337222,923442708646';
        $curl = curl_init();
        $data_string;

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'http://20.84.63.80:4000/chats/testBulk?id=DXB',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS => $data_string,
        //     // CURLOPT_POSTFIELDS =>'[
        //     //     {
        //     //         "receiver": "923121337222",
        //     //         "message": "Hi bro, how are you?"
        //     //     },
        //     //     {
        //     //         "receiver": "9234227086461",
        //     //         "message": "I\'m fine, thank you."
        //     //     }
        //     // ]',
        //     CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/json'
        //     ),
        // ));

        // $response = curl_exec($curl);

        // curl_close($curl);
        // $b = json_decode($response, true);//here the json string is decoded and returned as associative array
        // $fl = $b['message'];
        // $sr = $b['success'];
        // // if($sr == false){
        // //     return $message = $b['message'];
        // // }
        // // return $response;
        // if(!isset($b['data']['errors'])){
        //     return redirect()->back()
        //         ->withErrors('No Number Found')
        //         ->withInput();
        // }
        foreach ($data as $d) {
            $d = $d['receiver'];
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'http://20.203.123.60:1337/' . $d . '/SendMsgToUser');
            // curl_setopt($ch, CURLOPT_URL, 'http://localhost:1337/'.$d.'/SendMsgToUser');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "Hello Fucking World");

            $headers = array();
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $z[] = $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
        }
        // return $z;
        // return json_decode($z);
        // return $result;
        // foreach($result as $z){
        // return $z['jid'];
        // }
        // return json_decode($result, true); //here the json string is decoded and returned as associative array
        // return $result;
        //  $z = $b['data']['errors'];
        $session_id  = rand(1, 122);

        // foreach($z[1]);
        // return $z[1];
        foreach ($z as $k) {
            // return $k;
            // return $k;
            $k =  json_decode($k, true);
            // return $k[''];
            if ($k['exists'] == true) {
                $pr[] = preg_replace('/@s.whatsapp.net/', ',', $k['jid']);
            }
            //  $z = explode('@',$k);
            //  foreach($z as $k){
            //      echo $k . '<br>';
            //  }
            // $l =  preg_replace('/971/', '0', $k, 3);
        }
        foreach ($pr as $p) {
            // return $p;
            // echo $p . '<br>';
            $z = str_replace(',', ' ', $p);
            // $z =
            // echo $z . '<br>';
            $l =  preg_replace('/971/', '0', $z, 3);
            // }

            // return "Zoom";
            // return $request;
            // $list = $request->list;
            // foreach(explode(PHP_EOL, $list) as $l){
            // return $l;
            // echo 'New Number ' . $l . '</br>';
            // dnclist::where('number')->
            $k = trim($l);
            dnclist::where('number', '=', $k)->first();
            if (dnclist::where('number', '=', $k)->first()) {
                // echo "DNC NUMBER " . ',' . $k . ',' . preg_replace('/0/', '971', $k, 1);
                // echo '<br>';
            } else {
                // echo "VALID NUMBER " . ',' . $k . ',' . preg_replace('/0/', '971', $k, 1);
                // echo "Valid Number " . ',' . $k;
                // echo '<br>';
                // $session_checker = whatsapp_number::where('session_id',$session_id)
                // ->where('userid',auth()->user()->id)->first();
                // if (!$session_checker) {
                $data = whatsapp_number::create([
                    'number' => $k,
                    'country_number' => preg_replace('/0/', '971', $k, 1),
                    'userid' => auth()->user()->id,
                    'session_id' => $session_id,
                ]);
                // }
            }
        }
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $region_name = $_SERVER["HTTP_CF_IPCOUNTRY"];
            $user_country = $_SERVER["HTTP_CF_IPCOUNTRY"];
            $ipaddress = $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            // $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}"));
            $details = $ipaddress;
        } else {
            $ipaddress =   $request->ip();
            $details = $ipaddress;

            // $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}"));
            // $user_country =   $details->country;
            // $region_name =   $details->region;
        }
        $details = json_encode($details);
        $q = dnc_checker_log::create(
            [
                'ip_address' => $ipaddress,
                'FullAd' => $details,
                'lat' => $request->myLat,
                'lng' => $request->myLon,
                'userid' => auth()->user()->id,
            ]
        );
        // return $q;
        return redirect()->route('MyWhatsApp', $session_id);
    }
    //
    public function dnc_checker_list(Request $request)
    {
        // return "Zoom";
        // return $k = str_replace(PHP_EOL,',',$request->list);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://wap.parhakooo.com/uploadFile',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('email' => 'salmanahmedrajput@outlook.com', 'numbers' => str_replace(PHP_EOL, ',', $request->list)),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $z = json_decode($response);
        // dd($z);
        if (empty($z->data)) {
            redirect()->route('dnc_checker');
        }
        $dd = $z->data;
        $session_id  = rand(1, 122);
        foreach ($dd as $kk) {
            $l =  preg_replace('/971/', '0', $kk->number, 3);
            // }

            // return "Zoom";
            // return $request;
            // $list = $request->list;
            // foreach(explode(PHP_EOL, $list) as $l){
            // return $l;
            // echo 'New Number ' . $l . '</br>';
            // dnclist::where('number')->
            $k = trim($l);
            dnclist::where('number', '=', $k)->first();
            if (dnclist::where('number', '=', $k)->first()) {
                // echo "DNC NUMBER " . ',' . $k . ',' . preg_replace('/0/', '971', $k, 1);
                // echo '<br>';
            } else {
                // echo "VALID NUMBER " . ',' . $k . ',' . preg_replace('/0/', '971', $k, 1);
                // echo "Valid Number " . ',' . $k;
                // echo '<br>';
                // $session_checker = whatsapp_number::where('session_id',$session_id)
                // ->where('userid',auth()->user()->id)->first();
                // if (!$session_checker) {
                $data = whatsapp_number::create([
                    'number' => $k,
                    'country_number' => preg_replace('/0/', '971', $k, 1),
                    'userid' => auth()->user()->id,
                    'session_id' => $session_id,
                ]);
                // }
            }
            $id = auth()->user()->id;
            // ob_end_clean();
            // return Excel::download(new whatsapp_export($id, $session_id), '_' . $id . 'whatsapp_export.xlsx');
        }
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $region_name = $_SERVER["HTTP_CF_IPCOUNTRY"];
            $user_country = $_SERVER["HTTP_CF_IPCOUNTRY"];
            $ipaddress = $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}"));
        } else {
            $ipaddress =   $request->ip();
            $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}"));
            // $user_country =   $details->country;
            // $region_name =   $details->region;
        }
        $details = json_encode($details);
        $q = dnc_checker_log::create(
            [
                'ip_address' => $ipaddress,
                'FullAd' => $details,
                'lat' => $request->myLat,
                'lng' => $request->myLon,
                'userid' => auth()->user()->id,
            ]
        );
        // return $q;
        return redirect()->route('MyWhatsApp', auth()->user()->id);
        // return view('dashboard.dnc-list');
    }
    //
    public function MyWhatsApp(Request $request)
    {
        // return "GoBackToWork";
        $k = whatsapp_number::select('id','number', 'country_number')
        ->where('userid', auth()->user()->id)
        ->whereNull('status')
            // ->where('session_id', $this->sessionid)
        ->paginate();
        // $k =  similar_number_generator::where('number', $request->number)->whereNull('remarks')->paginate();
        return view('agent.add-log', compact('k'));
        // $id = auth()->user()->id;
        // $session_id = $request->session_id;
        // // ob_end_clean();
        // if (ob_get_length()) ob_end_clean();


        // return Excel::download(new whatsapp_export($id, $session_id), '_' . $id . 'whatsapp_export.xlsx');
    }
    public static function MyWhatsAppCount($status)
    {
        // return "GoBackToWork";
        return $k = whatsapp_number::select('id','number', 'country_number')
        ->where('userid', auth()->user()->id)
            // ->where('status',$status)
            ->when($status, function ($query) use ($status) {
                if ($status == 'connected') {
                    $query->whereIn('status', ['Follow up - Interested','Call Later','Follow Up - General','Not Interested','Lead','DNC', 'soft_dnd']);
                    // ->whereYear('lead_sales.created_at', Carbon::now()->year)
                } else {
                    $query->where('status', $status);
                    // return $query->where('users.agent_code', $id);
                }
            })
            // ->where('session_id', $this->sessionid)
        ->get()->count();
        // $k =  similar_number_generator::where('number', $request->number)->whereNull('remarks')->paginate();
        // return view('agent.add-log', compact('k'));
        // $id = auth()->user()->id;
        // $session_id = $request->session_id;
        // // ob_end_clean();
        // if (ob_get_length()) ob_end_clean();


        // return Excel::download(new whatsapp_export($id, $session_id), '_' . $id . 'whatsapp_export.xlsx');
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
        $k = whatsapp_number::where('id', $request->number_id)->first();
        $k->status = $request->status;
        $k->language = $request->language;
        if ($request->status == 'DNC') {
            $k->mark_dnd = 1;
        }
        if ($request->status == 'soft_dnd') {
            $k->mark_soft_dnd = 1;
        }
        // $k->user_id = auth()->user()->id;
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
    public function dnc_upload()
    {
        // return "b";
        return view('dashboard.dnc');
    }
    //
    public function index()
    {
        // return "b";
        return view('dashboard.import');
    }
    public function update_passcode()
    {
        // return "b";
        return view('dashboard.update_passcode');
    }
    public function update_sr()
    {
        // return "b";
        return view('dashboard.update_sr');
    }
    //
    function update_sr_excel(Request $request)
    {
        // return $request;
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);

        // $path = $request->file('select_file')->getRealPath();
        $path1 = $request->file('select_file')->store('temp');
        $path = storage_path('app') . '/' . $path1;
        // $path = storage_path('app') . '/' . $path1;
        // $data = \Excel::import(new UsersImport, $path);

        // $data = Excel::Import(new $path);
        // Excel::import(new NumberImport, $path);
        Excel::import(new UpdateSR, $path);
        return back()->with('success', 'Pass code successfully.');
        //

    }
    //
    function dnc_impoort(Request $request)
    {
        // return $request;
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);

        // $path = $request->file('select_file')->getRealPath();
        $path1 = $request->file('select_file')->store('temp');
        $path = storage_path('app') . '/' . $path1;
        // $path = storage_path('app') . '/' . $path1;
        // $data = \Excel::import(new UsersImport, $path);

        // $data = Excel::Import(new $path);
        // Excel::import(new NumberImport, $path);
        Excel::import(new UpdateDNC, $path);
        return back()->with('success', 'Pass code successfully.');
        //

    }
    //
    function update_passcode_excel(Request $request)
    {
        // return $request;
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);

        // $path = $request->file('select_file')->getRealPath();
        $path1 = $request->file('select_file')->store('temp');
        $path = storage_path('app') . '/' . $path1;
        // $path = storage_path('app') . '/' . $path1;
        // $data = \Excel::import(new UsersImport, $path);

        // $data = Excel::Import(new $path);
        // Excel::import(new NumberImport, $path);
        Excel::import(new UpdatePass, $path);
        return back()->with('success', 'Pass code successfully.');
        //

    }
    //
    public function update_excel()
    {
        // return "b";
        return view('dashboard.update_excel');
    }
    //
    public function index_region()
    {
        // return "b";
        return view('dashboard.import-region');
    }
    public function index_area()
    {
        // return "b";
        return view('dashboard.import-area');
    }
    //
    function import(Request $request)
    {
        return $request;
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);

        // $path = $request->file('select_file')->getRealPath();
        $path1 = $request->file('select_file')->store('temp');
        $path = storage_path('app') . '/' . $path1;
        // $path = storage_path('app') . '/' . $path1;
        // $data = \Excel::import(new UsersImport, $path);

        // $data = Excel::Import(new $path);
        // Excel::import(new NumberImport, $path);
        Excel::import(new NumberImport, $path);
        return back()->with('success', 'Number Data Imported successfullys.');
        //

    }
    //
    //
    function update_number(Request $request)
    {
        // return $request;
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);

        // $path = $request->file('select_file')->getRealPath();
        $path1 = $request->file('select_file')->store('temp');
        $path = storage_path('app') . '/' . $path1;
        // $path = storage_path('app') . '/' . $path1;
        // $data = \Excel::import(new UsersImport, $path);

        // $data = Excel::Import(new $path);
        // Excel::import(new NumberImport, $path);
        Excel::import(new NumberUpdate, $path);
        return back()->with('success', 'Number Data Updated successfully.');
        //

    }
    //
    function import_region(Request $request)
    {
        // return $request;
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);

        // $path = $request->file('select_file')->getRealPath();
        $path1 = $request->file('select_file')->store('temp');
        $path = storage_path('app') . '/' . $path1;
        // $path = storage_path('app') . '/' . $path1;
        // $data = \Excel::import(new UsersImport, $path);

        // $data = Excel::Import(new $path);
        // Excel::import(new NumberImport, $path);
        Excel::import(new UploadRegionNumber, $path);
        return back()->with('success', 'Number Data Imported successfullys.');
        //

    }
    //
    function import_area(Request $request)
    {
        // return $request;
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);

        // $path = $request->file('select_file')->getRealPath();
        $path1 = $request->file('select_file')->store('temp');
        $path = storage_path('app') . '/' . $path1;
        // $path = storage_path('app') . '/' . $path1;
        // $data = \Excel::import(new UsersImport, $path);

        // $data = Excel::Import(new $path);
        // Excel::import(new NumberImport, $path);
        Excel::import(new AreaUploader, $path);
        return back()->with('success', 'Number Data Imported successfullys.');
        //

    }
    //

    //
    public function index_elife()
    {
        // return "b";
        $b = elife_bulker::where('identify', '0')->get();
        $NumberCount = elife_bulker::where('identify', '0')->count();
        $u = User::select('users.*')->where('role', 'sale')->where('agent_code', auth()->user()->agent_code)->get();
        return view('dashboard.import2', compact('b', 'NumberCount', 'u'));
    }
    function import_elife(Request $request)
    {
        // return $request;
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);

        // $path = $request->file('select_file')->getRealPath();
        $path1 = $request->file('select_file')->store('temp');
        $path = storage_path('app') . '/' . $path1;
        // $path = storage_path('app') . '/' . $path1;
        // $data = \Excel::import(new UsersImport, $path);

        // $data = Excel::Import(new $path);
        Excel::import(new elife_bulk, $path);
        return back()->with('success', 'Number Data Imported successfully.');
        //

    }
    //
    public function upload_data()
    {
        // return "b";
        $data = dummy_data_test::all();
        return view('dashboard.upload_data', compact('data'));
    }
    //
    public function upload_main_data(Request $request)
    {

        // use Storage;
        // $url = "http://www.google.co.in/intl/en_com/images/srpr/logo1w.png";
        $url = $request->url;
        $contents = file_get_contents($url);
        $name = substr($url, strrpos($url, '/') + 1);
        $path = \Storage::put($name, $contents);
        // $contents = Storage::disk('local')->get($name);
        Excel::import(new EtiTest, $name);
        return back()->with('success', 'Number Data Imported successfully.');
        // $ch = curl_init();
        // return $contents;

        //    return Storage::disk('local')->put('example.txt', 'Contents');
        // $contents = Storage::disk('local')->get($name);
        // dd($contents);
        // echo '<img src="'.$contents.'">';
        // curl_setopt($ch, CURLOPT_HEADER, 0);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_URL, $request->url);
        // $data = curl_exec($ch);
        // curl_close($ch);

        // return $data;
    }
    //
    public function update_data()
    {
        // return "b";
        $data = mnp_data::all();
        return view('dashboard.update_data', compact('data'));
    }
    //
    function update_mnp_data(Request $request)
    {
        // return $request;
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);

        // $path = $request->file('select_file')->getRealPath();
        $path1 = $request->file('select_file')->store('temp');
        $path = storage_path('app') . '/' . $path1;
        // $path = storage_path('app') . '/' . $path1;
        // $data = \Excel::import(new UsersImport, $path);

        // $data = Excel::Import(new $path);
        Excel::import(new mnp_bulk, $path);
        return back()->with('success', 'Number Data Imported successfully.');
        //

    }

    public function assigner_user(Request $request)
    {
        // return $request;
        foreach ($request->number as $k) {
            $ks = main_data_user_assigned::create([
                'number_id' => $k,
                'user_id' => $request->user,
                'call_center' => $request->user,
                // 'status' => '',
            ]);
            //
            $k = main_data_manager_assigner::where('number_id', $k)->where('manager_id', auth()->user()->id)->first();
            $k->status = '1';
            $k->save();
            // echo $k . '<br>';
            // $k = explode('-', $k);
            // return $k['0'];
            // return $k['1'];
            // number_assigner::create([
            //     'number_id' => $k,
            //     'userid' => $request->user,
            //     'status' => ' ',
            // ]);
            // if ($k['1'] == 'Country') {
            // return $k;
            // $ks = main_data_manager_assigner::create([
            //     'number_id' => $k,
            //     'manager_id' => $request->user,
            //     'call_center' => $request->user,
            //     // 'status' => '',
            // ]);
            // $ks = number_assigner::where('number', $k])->update(['user_id' => $request->user]);
            // $ks = main_data_manager_assigner::where('number_id', $k])->update(['user_id' => $request->user]);
            // } else {
            // $ks = bulknumber::where('number', $k)->update(['identify' => '1']);
            // }
        }
        return "1";
    }
    public function assigner(Request $request)
    {
        // return $request;
        foreach ($request->number as $k) {

            //
            $k = WhatsAppMnpBank::where('id', $k)->first();
            $k->is_whatsapp = '1';
            $k->save();
            //
            $ks = main_data_manager_assigner::create([
                'number_id' => $k,
                'manager_id' => $request->user,
                'call_center' => $request->user,
                // 'status' => '',
            ]);
            // echo $k . '<br>';
            // $k = explode('-', $k);
            // return $k['0'];
            // return $k['1'];
            // number_assigner::create([
            //     'number_id' => $k,
            //     'userid' => $request->user,
            //     'status' => ' ',
            // ]);
            // if ($k['1'] == 'Country') {
            // $ks = number_assigner::where('number', $k[0])->update(['manager_id' => $request->user]);
            // } else {
            // $ks = bulknumber::where('number', $k)->update(['identify' => '1']);
            // }
        }
        return "1";
    }
    //
    public function vc(Request $request)
    {
        return view('dashboard.video');
    }
    //
    public function vcupload(Request $request)
    {
        // return $request;
        if (!empty($request->video)) {
            // return $request->audio;
            // return $request->file();
            if ($file = $request->file('video')) {
                // LocalStorageCodeStart
                $image2 = file_get_contents($file);
                $originalFileName = time() . $file->getClientOriginalName();
                $multi_filePath = 'aashir_video' . '/' . $originalFileName . '.mp4';
                \Storage::disk('azure')->put($multi_filePath, $image2);
                //
                // $ext = date('d-m-Y-H-i');
                // $mytime = Carbon::dw();
                // $ext =  $mytime->toDateTimeString();
                // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                // $name = $originalFileName;

                // $file[$key]->move('audio', $name);
                // $input['path'] = $name;
                // LocalStorageCodeEnd
                // AzureCodeStart
                //
                $filename = 'https://salmanrajzzdiag.blob.core.windows.net/callmax/' . $multi_filePath;
                \QrCode::size(500)
                    ->format('png')
                    ->generate($filename, public_path('images/qrcode.png'));

                return view('dashboard.video_qr', compact('filename'));
                // return \QrCode::size(250)->generate($filename);

                // return $multi_filePath;

                // AzureCodeEnd
            }
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
        // $data = audio_recording::create([
        //     // 'resource_name' => $request->resource_name,
        //     'audio_file' => $name,
        //     'username' => 'salman',
        //     'lead_no' => $request->lead_id,
        //     // 'teacher_id' => $request->teacher_id,
        //     'status' => 1,
        // ]);
        // }
        // }
    }
}
