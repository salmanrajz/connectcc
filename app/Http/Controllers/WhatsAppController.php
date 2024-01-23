<?php

namespace App\Http\Controllers;

use App\Models\dnclist;
use App\Models\lead_sale;
use App\Models\main_data_manager_assigner;
use App\Models\main_data_user_assigned;
use App\Models\User;
use App\Models\WhatsAppDND;
use App\Models\WhatsAppMnpBank;
use App\Models\WhatsAppScan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class WhatsAppController extends Controller
{
    //
    public function MasterStroke(Request $request)
    {
        // return 'ok';
        ini_set('max_execution_time', '3000'); //300 seconds = 5 minutes
        //
        $numberstart = WhatsAppDND::orderBy('id', 'desc')->first();
        if (!$numberstart) {
            $numberstart = 971561000000;
        } else {
            $numberstart = $numberstart->wapnumber;
        }
        if ($numberstart === 971569999999 || $numberstart > 971569999999) {
            return "Game Over";
        }
        // $end = $numberstart + 5;
        $end = $numberstart + 1000000;
        // for ($v = $numberstart; $v <= '971583999999'; $v++) {
        for ($v = $numberstart; $v <= $end; $v++) {
            // for($i='971581000000';$i<= '971581001000';$i++){
            //     // return $i;
            //     // echo $i . '</br>';
            //     // $d=
            //         if (!WhatsAppScan::where('wapnumber', '=', $i)->exists()) {
            //             $d = WhatsAppScan::create([
            //                 'wapnumber' => $i,
            //             ]);
            //         }
            // }
            // $regex = “\\b([a-zA-Z0-9])\\1\\1+\\b”;
            // for($i='971581000000';$i<= '971581002000';$i++){
            // \Log::info($i);
            if (preg_match('/(.)\\1{6}/', $v)) {
                $data[] = [
                    // 'start' => '0',
                    // 'end' => '0',
                    'wapnumber' => $v,
                    'count_digit' => 7,
                    'prefix' => 56
                ];
            } elseif (preg_match('/(.)\\1{5}/', $v)) {
                // echo '###' . $i . '<br> => 5 Times Number';
                $data[] = [
                    // 'start' => '0',
                    // 'end' => '0',
                    'wapnumber' => $v,
                    'count_digit' => 6,
                    'prefix' => 56

                ];
            } elseif (preg_match('/(.)\\1{4}/', $v)) {
                // echo '###' . $i . '<br> => 5 Times Number';
                $data[] = [
                    // 'start' => '0',
                    // 'end' => '0',
                    'wapnumber' => $v,
                    'count_digit' => 5,
                    'prefix' => 56

                ];
            }
            // else if (preg_match('/(.)\\1{3}/', $v)) {
            //     // echo '###' . $i . '<br> => 4 Times Number';
            //     $data[] = [
            //         'start' => '0',
            //         'end' => '0',
            //         'wapnumber' => $v,
            //         'count_digit' => 4,
            //     ];
            // }
            // else if (preg_match('/(.)\\1{2}/', $v)) {
            //     // echo '###' . $i . '<br> => 3 Times Number';
            //     $data[] = [
            //         'start' => '0',
            //         'end' => '0',
            //         'wapnumber' => $v,
            //         'count_digit' => 3,
            //     ];
            // } else if (preg_match('/(.)\\1{1}/', $v)) {
            //     // echo '###' . $i . '<br> => 2 Times Number';
            //     $data[] = [
            //         'start' => '0',
            //         'end' => '0',
            //         'wapnumber' => $v,
            //         'count_digit' => 2,
            //     ];
            // }
            // // else if (preg_match('/(.)\\1{1}/', $i)) {
            // //     // echo '###' . $i . '<br> => 2 Times Number';
            // //     if (!WhatsAppScan::where('wapnumber', '=', $i)->exists()) {
            // //         $d = WhatsAppScan::create([
            // //             'wapnumber' => $i,
            // //         ]);
            // //     }
            // // }
            // else {
            //     // echo $i . ' => <br>' . '=> No 3 Times';
            //     $data[] = [
            //         'start' => '0',
            //         'end' => '0',
            //         'wapnumber' => $v,
            //         'count_digit' => 'random',
            //     ];
            // }
        }
        $chunks = array_chunk($data, 500);
        foreach ($chunks as $chunk) {
            WhatsAppDND::query()->insert($chunk);
        }
    }
    //
    public function TransferNumber(Request $request)
    {
        $data = User::select('id')->whereIn('users.role', ['NumberAdmin', 'Sale'])
            ->where('agent_code', 'CC4')
            ->get();
        if ($data->count() > 0) {
            $ManagerID = User::select('id')->whereIn('users.role', ['FloorManager'])
                ->where('agent_code', 'CC4')
                ->first();
            // return $ManagerID;
            $limit = $data->count() * 250;
            $bank = WhatsAppMnpBank::select('id', 'number', 'is_status')->whereNull('status')->limit($limit)->get();
            foreach ($bank as $k) {
                //
                // return $limit;
                // return $ManagerID;
                $checker = main_data_manager_assigner::where('manager_id', $ManagerID->id)
                    // ->whereNull('status')
                    ->whereDate('created_at', Carbon::today())
                    ->get()->count();
                // if($checker > $limit){
                //     return "Checker Bigger than Limit";
                // }else{
                //     return "Bigger Than Checker, am i limit?";
                // }
                if ($checker < $limit) {
                    //
                    // return "D";
                    // $d = dnclist::where('number', '=', $k->number)->first();
                    //
                    // if($)
                    if (!dnclist::where('number', '=', $k->number)->exists()) {
                        //
                        // return "Non Exist";
                        $ks = main_data_manager_assigner::create([
                            'number_id' => $k->id,
                            'manager_id' => $ManagerID->id,
                            'call_center' => $ManagerID->id,
                            // 'status' => '',
                        ]);
                        $kk = WhatsAppMnpBank::where('id', $k->id)->first();
                        $kk->is_status = '1';
                        $kk->save();
                    }
                }
                // else{
                //     return "Limit";
                // }
            }
            foreach ($data as $d) {
                // return $d->id;
                // foreach
                // return $d;
                $checker = main_data_manager_assigner::where('manager_id', $ManagerID->id)->whereNull('status')->get();
                foreach ($checker as $cc) {
                    $csr = main_data_user_assigned::where('user_id', $d->id)->whereNull('status')->get();
                    if ($csr->count() < 250) {
                        // echo "1";
                        $ks = main_data_user_assigned::create([
                            'number_id' => $cc->id,
                            'user_id' => $d->id,
                            'call_center' => $d->id,
                            // 'status' => '',
                        ]);
                        //
                        $k = main_data_manager_assigner::where('number_id', $cc->id)->where('manager_id', $ManagerID->id)->first();
                        $k->status = '1';
                        $k->save();
                    }
                }
            }
            return "Clear";
        }
    }
    //
    public function TransferWhatsApp(Request $request)
    {
        $d = WhatsAppScan::select('wapnumber', 'id')->where('is_whatsapp', 1)->limit(1000)->get();
        // foreach($d as $dd){
        // $chunks = array_chunk($d, 5000);
        // }
        // return $chunks;
        if ($d->count > 0) {
            foreach ($d as $chunk) {
                if (!WhatsAppMnpBank::where('number_id', '=', $chunk->id)->exists()) {
                    WhatsAppMnpBank::create([
                        'number_id' => $chunk->id,
                        'number' => $chunk->wapnumber,
                    ]);
                }
            }
        }
    }
    //
    public function ScanWhatsApp(Request $request)
    {
        // $key = '971522221220';
        //
        //  $duplicates =\DB::table('whats_app_scans') // replace table by the table name where you want to search for duplicated values
        // ->select('id', 'wapnumber') // name is the column name with duplicated values
        //     ->whereIn('wapnumber', function ($q) {
        //         $q->select('wapnumber')
        //         ->from('whats_app_scans')
        //         ->groupBy('wapnumber')
        //         ->havingRaw('COUNT(*) > 1');
        //     })
        //     ->orderBy('wapnumber')
        //     ->orderBy('id') // keep smaller id (older), to keep biggest id (younger) replace with this ->orderBy('id', 'desc')
        //     ->get();
        // // //
        // $value = "";

        // // loop throuht results and keep first duplicated value
        // foreach ($duplicates as $duplicate) {
        //     if ($duplicate->wapnumber === $value) {
        //         \DB::table('whats_app_scans')->where('id', $duplicate->id)->delete(); // comment out this line the first time to check what will be deleted and keeped
        //         echo "$duplicate->wapnumber with id $duplicate->id deleted! \n";
        //     } else
        //     echo "$duplicate->wapnumber with id $duplicate->id keeped \n";
        //     $value = $duplicate->wapnumber;
        // }
        // return "Mission Complete";

        //
        $da = WhatsAppScan::select('id', 'wapnumber')->where('count_digit', '=', 2)
            ->OrWhere('count_digit', 'random')
            ->where('is_whatsapp', 0)
            ->limit(1000)->get();
        foreach ($da as $d) {

            $data[] = array(

                'receiver' => trim($d->wapnumber),
                'message' => $d->id,

            );
        }
        $data_string = json_encode($data);
        // // // $data = '923121337222,923442708646';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://20.84.63.80:4000/chats/TestBulkTest?id=DXB',
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
        // return $b;
        $not_available = $b['data']['not_available'];
        $available = $b['data']['available'];
        //
        // foreach ($available as $k) {
        //     // return $k;
        //     $an[] = preg_replace('/@s.whatsapp.net/', ',', $k);
        //     //  $z = explode('@',$k);
        //     //  foreach($z as $k){
        //     //      echo $k . '<br>';
        //     //  }
        //     // $l =  preg_replace('/971/', '0', $k, 3);
        // }
        // foreach ($not_available as $nk) {
        //     // return $k;
        //     $nan[] = preg_replace('/@s.whatsapp.net/', ',', $nk);
        //     //  $z = explode('@',$k);
        //     //  foreach($z as $k){
        //     //      echo $k . '<br>';
        //     //  }
        //     // $l =  preg_replace('/971/', '0', $k, 3);
        // }
        // // return $pr;
        // foreach ($an as $p) {
        //     // echo $p . '<br>';
        //     $z = str_replace(',', ' ', $p);
        //     $data = WhatsAppScan::where('wapnumber', '=', $z)->first();
        //     if($data){
        //         $data->is_whatsapp = 1;
        //         $data->save();
        //     }
        // }
        // foreach ($nan as $np) {
        //     // echo $p . '<br>';
        //     $z = str_replace(',', ' ', $np);
        //     $data = WhatsAppScan::where('wapnumber', '=', $z)->first();
        //     if($data){
        //         $data->is_whatsapp = 2;
        //         $data->save();
        //     }
        // }
        return "Clear and OUT";
        return redirect()->route('ScanWhatsApp');
        // return $z;
    }
    //
    //
    public function ScanWhatsApp2(Request $request)
    {
        // $key = '971522221220';
        //
        //  $duplicates =\DB::table('whats_app_scans') // replace table by the table name where you want to search for duplicated values
        // ->select('id', 'wapnumber') // name is the column name with duplicated values
        //     ->whereIn('wapnumber', function ($q) {
        //         $q->select('wapnumber')
        //         ->from('whats_app_scans')
        //         ->groupBy('wapnumber')
        //         ->havingRaw('COUNT(*) > 1');
        //     })
        //     ->orderBy('wapnumber')
        //     ->orderBy('id') // keep smaller id (older), to keep biggest id (younger) replace with this ->orderBy('id', 'desc')
        //     ->get();
        // //
        // $value = "";

        // // loop throuht results and keep first duplicated value
        // foreach ($duplicates as $duplicate) {
        //     if ($duplicate->wapnumber === $value) {
        //         \DB::table('whats_app_scans')->where('id', $duplicate->id)->delete(); // comment out this line the first time to check what will be deleted and keeped
        //         echo "$duplicate->wapnumber with id $duplicate->id deleted! \n";
        //     } else
        //     echo "$duplicate->wapnumber with id $duplicate->id keeped \n";
        //     $value = $duplicate->wapnumber;
        // }
        // return "Mission Complete";

        //
        $da = WhatsAppScan::select('id', 'wapnumber')->where('count_digit', '=', 'random')
            // ->OrWhere('count_digit','random')
            ->where('is_whatsapp', 0)
            ->limit(1000)->get();
        foreach ($da as $d) {

            $data[] = array(

                'receiver' => trim($d->wapnumber),
                'message' => $d->id,

            );
        }
        // return $data;
        $data_string = json_encode($data);
        // // // $data = '923121337222,923442708646';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://20.84.63.80:4000/chats/TestBulkTest?id=Umar',
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
        return $b;
        $not_available = $b['data']['not_available'];
        $available = $b['data']['available'];
        //
        // foreach ($available as $k) {
        //     // return $k;
        //     $an[] = preg_replace('/@s.whatsapp.net/', ',', $k);
        //     //  $z = explode('@',$k);
        //     //  foreach($z as $k){
        //     //      echo $k . '<br>';
        //     //  }
        //     // $l =  preg_replace('/971/', '0', $k, 3);
        // }
        // foreach ($not_available as $nk) {
        //     // return $k;
        //     $nan[] = preg_replace('/@s.whatsapp.net/', ',', $nk);
        //     //  $z = explode('@',$k);
        //     //  foreach($z as $k){
        //     //      echo $k . '<br>';
        //     //  }
        //     // $l =  preg_replace('/971/', '0', $k, 3);
        // }
        // // return $pr;
        // foreach ($an as $p) {
        //     // echo $p . '<br>';
        //     $z = str_replace(',', ' ', $p);
        //     $data = WhatsAppScan::where('wapnumber', '=', $z)->first();
        //     if($data){
        //         $data->is_whatsapp = 1;
        //         $data->save();
        //     }
        // }
        // foreach ($nan as $np) {
        //     // echo $p . '<br>';
        //     $z = str_replace(',', ' ', $np);
        //     $data = WhatsAppScan::where('wapnumber', '=', $z)->first();
        //     if($data){
        //         $data->is_whatsapp = 2;
        //         $data->save();
        //     }
        // }
        return "Clear and OUT";
        return redirect()->route('ScanWhatsApp2');
        // return $z;
    }
    public function ScanWhatsApp3(Request $request)
    {
        // $key = '971522221220';
        //
        //  $duplicates =\DB::table('whats_app_scans') // replace table by the table name where you want to search for duplicated values
        // ->select('id', 'wapnumber') // name is the column name with duplicated values
        //     ->whereIn('wapnumber', function ($q) {
        //         $q->select('wapnumber')
        //         ->from('whats_app_scans')
        //         ->groupBy('wapnumber')
        //         ->havingRaw('COUNT(*) > 1');
        //     })
        //     ->orderBy('wapnumber')
        //     ->orderBy('id') // keep smaller id (older), to keep biggest id (younger) replace with this ->orderBy('id', 'desc')
        //     ->get();
        // //
        // $value = "";

        // // loop throuht results and keep first duplicated value
        // foreach ($duplicates as $duplicate) {
        //     if ($duplicate->wapnumber === $value) {
        //         \DB::table('whats_app_scans')->where('id', $duplicate->id)->delete(); // comment out this line the first time to check what will be deleted and keeped
        //         echo "$duplicate->wapnumber with id $duplicate->id deleted! \n";
        //     } else
        //     echo "$duplicate->wapnumber with id $duplicate->id keeped \n";
        //     $value = $duplicate->wapnumber;
        // }
        // return "Mission Complete";

        //
        $da = WhatsAppScan::select('id', 'wapnumber')->where('count_digit', '=', 'random')
            ->where('is_whatsapp', 0)
            ->limit(100)->get();
        foreach ($da as $d) {

            $data[] = array(

                'receiver' => trim($d->wapnumber),
                'message' => $d->id,

            );
        }
        $data_string = json_encode($data);
        // // // $data = '923121337222,923442708646';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://13.80.170.208:4000/chats/TestBulkTest?id=Mustufa',
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
        return $b;
        $not_available = $b['data']['not_available'];
        $available = $b['data']['available'];
        //
        // foreach ($available as $k) {
        //     // return $k;
        //     $an[] = preg_replace('/@s.whatsapp.net/', ',', $k);
        //     //  $z = explode('@',$k);
        //     //  foreach($z as $k){
        //     //      echo $k . '<br>';
        //     //  }
        //     // $l =  preg_replace('/971/', '0', $k, 3);
        // }
        // foreach ($not_available as $nk) {
        //     // return $k;
        //     $nan[] = preg_replace('/@s.whatsapp.net/', ',', $nk);
        //     //  $z = explode('@',$k);
        //     //  foreach($z as $k){
        //     //      echo $k . '<br>';
        //     //  }
        //     // $l =  preg_replace('/971/', '0', $k, 3);
        // }
        // // return $pr;
        // foreach ($an as $p) {
        //     // echo $p . '<br>';
        //     $z = str_replace(',', ' ', $p);
        //     $data = WhatsAppScan::where('wapnumber', '=', $z)->first();
        //     if($data){
        //         $data->is_whatsapp = 1;
        //         $data->save();
        //     }
        // }
        // foreach ($nan as $np) {
        //     // echo $p . '<br>';
        //     $z = str_replace(',', ' ', $np);
        //     $data = WhatsAppScan::where('wapnumber', '=', $z)->first();
        //     if($data){
        //         $data->is_whatsapp = 2;
        //         $data->save();
        //     }
        // }
        return "Clear and OUT";
        return redirect()->route('ScanWhatsApp3');
        // return $z;
    }
    public function ScanWhatsApp4(Request $request)
    {
        // $key = '971522221220';
        //
        //  $duplicates =\DB::table('whats_app_scans') // replace table by the table name where you want to search for duplicated values
        // ->select('id', 'wapnumber') // name is the column name with duplicated values
        //     ->whereIn('wapnumber', function ($q) {
        //         $q->select('wapnumber')
        //         ->from('whats_app_scans')
        //         ->groupBy('wapnumber')
        //         ->havingRaw('COUNT(*) > 1');
        //     })
        //     ->orderBy('wapnumber')
        //     ->orderBy('id') // keep smaller id (older), to keep biggest id (younger) replace with this ->orderBy('id', 'desc')
        //     ->get();
        // //
        // $value = "";

        // // loop throuht results and keep first duplicated value
        // foreach ($duplicates as $duplicate) {
        //     if ($duplicate->wapnumber === $value) {
        //         \DB::table('whats_app_scans')->where('id', $duplicate->id)->delete(); // comment out this line the first time to check what will be deleted and keeped
        //         echo "$duplicate->wapnumber with id $duplicate->id deleted! \n";
        //     } else
        //     echo "$duplicate->wapnumber with id $duplicate->id keeped \n";
        //     $value = $duplicate->wapnumber;
        // }
        // return "Mission Complete";

        //
        // return "Hello";
        $da = WhatsAppScan::select('id', 'wapnumber')->where('count_digit', '=', 2)
            // ->orWhei are('count_digit','random')
            ->where('is_whatsapp', 0)
            ->limit(100)->get();
        foreach ($da as $d) {

            $data[] = array(

                'receiver' => trim($d->wapnumber),
                'message' => $d->id,

            );
        }
        $data_string = json_encode($data);
        // // // $data = '923121337222,923442708646';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://20.84.63.80:4000/chats/TestBulkTest?id=Aashir',
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
        // return $b;
        // return "Clear and Done";
        $not_available = $b['data']['not_available'];
        $available = $b['data']['available'];
        //
        // foreach ($available as $k) {
        //     // return $k;
        //     $an[] = preg_replace('/@s.whatsapp.net/', ',', $k);
        //     //  $z = explode('@',$k);
        //     //  foreach($z as $k){
        //     //      echo $k . '<br>';
        //     //  }
        //     // $l =  preg_replace('/971/', '0', $k, 3);
        // }
        // foreach ($not_available as $nk) {
        //     // return $k;
        //     $nan[] = preg_replace('/@s.whatsapp.net/', ',', $nk);
        //     //  $z = explode('@',$k);
        //     //  foreach($z as $k){
        //     //      echo $k . '<br>';
        //     //  }
        //     // $l =  preg_replace('/971/', '0', $k, 3);
        // }
        // // return $pr;
        // foreach ($an as $p) {
        //     // echo $p . '<br>';
        //     $z = str_replace(',', ' ', $p);
        //     $data = WhatsAppScan::where('wapnumber', '=', $z)->first();
        //     if($data){
        //         $data->is_whatsapp = 1;
        //         $data->save();
        //     }
        // }
        // foreach ($nan as $np) {
        //     // echo $p . '<br>';
        //     $z = str_replace(',', ' ', $np);
        //     $data = WhatsAppScan::where('wapnumber', '=', $z)->first();
        //     if($data){
        //         $data->is_whatsapp = 2;
        //         $data->save();
        //     }
        // }
        return "Clear and OUT";
        return redirect()->route('ScanWhatsApp3');
        // return $z;
    }
    //
    public function WhatsAppLoopStartToEnd(Request $request)
    {
        $numberstart = WhatsAppScan::orderBy('id', 'desc')->first();
        $end = $numberstart + 5;
        if (!$numberstart) {
            $numberstart = 971581000000;
        } else {
            $numberstart = $numberstart->wapnumber;
        }
        // return $numberstart;
        ini_set('max_execution_time', '3000'); //300 seconds = 5 minutes
        for ($i = $numberstart; $i <= '971581999999'; $i++) {
            // for($i='971581000000';$i<= '971581001000';$i++){
            //     // return $i;
            //     // echo $i . '</br>';
            //     // $d=
            //         if (!WhatsAppScan::where('wapnumber', '=', $i)->exists()) {
            //             $d = WhatsAppScan::create([
            //                 'wapnumber' => $i,
            //             ]);
            //         }
            // }
            // $regex = “\\b([a-zA-Z0-9])\\1\\1+\\b”;
            // for($i='971581000000';$i<= '971581002000';$i++){
            if (preg_match('/(.)\\1{6}/', $i)) {
                // echo '###' . $i . '<br> => 5 Times Number';
                if (!WhatsAppScan::where('wapnumber', '=', $i)->exists()) {
                    $d = WhatsAppScan::create([
                        'wapnumber' => $i,
                        'count_digit' => '7',
                    ]);
                }
            } elseif (preg_match('/(.)\\1{5}/', $i)) {
                // echo '###' . $i . '<br> => 5 Times Number';
                if (!WhatsAppScan::where('wapnumber', '=', $i)->exists()) {
                    $d = WhatsAppScan::create([
                        'wapnumber' => $i,
                        'count_digit' => '6',

                    ]);
                }
            } elseif (preg_match('/(.)\\1{4}/', $i)) {
                // echo '###' . $i . '<br> => 5 Times Number';
                if (!WhatsAppScan::where('wapnumber', '=', $i)->exists()) {
                    $d = WhatsAppScan::create([
                        'wapnumber' => $i,
                        'count_digit' => '5',

                    ]);
                }
            } else if (preg_match('/(.)\\1{3}/', $i)) {
                // echo '###' . $i . '<br> => 4 Times Number';
                if (!WhatsAppScan::where('wapnumber', '=', $i)->exists()) {
                    $d = WhatsAppScan::create([
                        'wapnumber' => $i,
                        'count_digit' => '4',

                    ]);
                }
            } else if (preg_match('/(.)\\1{2}/', $i)) {
                // echo '###' . $i . '<br> => 3 Times Number';
                if (!WhatsAppScan::where('wapnumber', '=', $i)->exists()) {
                    $d = WhatsAppScan::create([
                        'wapnumber' => $i,
                        'count_digit' => '3',

                    ]);
                }
            } else if (preg_match('/(.)\\1{1}/', $i)) {
                // echo '###' . $i . '<br> => 2 Times Number';
                if (!WhatsAppScan::where('wapnumber', '=', $i)->exists()) {
                    $d = WhatsAppScan::create([
                        'wapnumber' => $i,
                        'count_digit' => '2',

                    ]);
                }
            }
            // else if (preg_match('/(.)\\1{1}/', $i)) {
            //     // echo '###' . $i . '<br> => 2 Times Number';
            //     if (!WhatsAppScan::where('wapnumber', '=', $i)->exists()) {
            //         $d = WhatsAppScan::create([
            //             'wapnumber' => $i,
            //         ]);
            //     }
            // }
            else {
                // echo $i . ' => <br>' . '=> No 3 Times';
                if (!WhatsAppScan::where('wapnumber', '=', $i)->exists()) {
                    $d = WhatsAppScan::create([
                        'wapnumber' => $i,
                        'count_digit' => 'random',

                    ]);
                }
            }
        }
    }
    //
    public function FinalCordLead()
    {
        if (auth()->user()->role == 'MainCoordinator') {
            $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", "verification_forms.id as ver_id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
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
                ->Join(
                    'verification_forms',
                    'verification_forms.lead_no',
                    '=',
                    'lead_sales.id'
                )
                ->where('verification_forms.status', '1.09')        // ->where('users.agent_code', auth()->user()->agent_code)
                ->get();
            return view('dashboard.manage-cordination-lead', compact('operation'));
        } else {
            $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", "verification_forms.id as ver_id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
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
                ->Join(
                    'verification_forms',
                    'verification_forms.lead_no',
                    '=',
                    'lead_sales.id'
                )
                ->where('lead_sales.status', '1.07')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->get();
            return view('dashboard.manage-cordination-lead', compact('operation'));
        }
    }
    public function OurFinalCordLead($agent_code)
    {
        if (auth()->user()->role == 'MainCoordinator') {
            $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", "verification_forms.id as ver_id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
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
                ->Join(
                    'verification_forms',
                    'verification_forms.lead_no',
                    '=',
                    'lead_sales.id'
                )
                ->where('verification_forms.status', '1.09')        // ->where('users.agent_code', auth()->user()->agent_code)
                ->get();
            return view('dashboard.manage-cordination-lead', compact('operation'));
        } else {
            $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", "verification_forms.id as ver_id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
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
                ->Join(
                    'verification_forms',
                    'verification_forms.lead_no',
                    '=',
                    'lead_sales.id'
                )
                ->where('lead_sales.status', '1.07')
                ->where('users.agent_code', $agent_code)
                ->get();
            return view('dashboard.manage-cordination-lead', compact('operation'));
        }
    }
    //
    public function ShowWhatsAppCode(Request $request)
    {
        return view('whatsapp.front');
    }
    //
    public function JoinWhatsApp(Request $request)
    {
        // return $request;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://127.0.0.1:4000/sessions/add',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'id=' . auth()->user()->id . '&isLegacy=false',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;
        // return $response;
        $b = json_decode($response, true); //here the json string is decoded and returned as associative array
        $fl = $b['message'];
        $sr = $b['success'];
        if ($sr == false) {
            return $fl = $b['message'];
        } else {
            return $qr_code = $b['data']['qr'];
        }
    }
    public function SendWhatsApp(Request $request)
    {
        // return "Zoom";
        // return $request;
        $validator = Validator::make($request->all(), [ // <---
            // 'title' => 'required|unique:posts|max:255',
            // 'body' => 'required',
            'message' => 'required',
            'numbers' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        //
        $list = $request->numbers;


        // $zzz = str_replace(PHP_EOL, ',', $request->list);
        // return $request->list;
        foreach (explode(PHP_EOL, $request->numbers) as $k => $key) {
            // return count($k)
            // if(!empty($k)){
            // $expr = '/^(?:\+971|971)(?:2|3|4|6|7|9|50|51|52|55|56|58|54)[0-9]{7}$/m';
            // // $id = '0522221220';
            // if (!empty($key)) {

            //     // $key = '971522221220';
            //     if (preg_match($expr, trim($key), $match) == 1) {
            //         // echo '###' . $match[0];
            //         // if ($validator->fails()) {
            //         // }
            //     } else {
            //         // echo "what....?";
            //         return redirect()->back()
            //         ->withErrors('Invalid Number, Kindly use with Country Code: Example: 97152221230')
            //         ->withInput();
            //     }
            // }
            // return $key;
            $data[] = array(

                'receiver' => trim($key),
                'message' => $request->message,

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
            CURLOPT_URL => 'http://127.0.0.1:4000/chats/send-bulk?id=' . auth()->user()->id,
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
        // return $response;

        $b = json_decode($response, true); //here the json string is decoded and returned as associative array
        // $fl = $b['message'];
        // $sr = $b['success'];
        return response()->json(['success' => $b['message']]);
        //
    }
    //
    public function WhatsAppSend(Request $request)
    {
        // return "Zoom";
        return view('whatsapp.send-whatsapp');
    }
    public function WhatsAppUrl(Request $request)
    {
        // return "zoom";
        // return auth()->user()->id;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => '127.0.0.1:4000/sessions/status/' . auth()->user()->id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
    //
    public function DeleteWhatsApp(Request $request)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => '127.0.0.1:4000/sessions/delete/' . auth()->user()->id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
    // })->name('verification.final-cord-lead')->middleware('auth');
    public static function ActivationWhatsApp($details)
    {
        // return $details;
        $token = env('FACEBOOK_TOKEN');

        // return $details['lead_no'];

        foreach (explode(',', $details['numbers']) as $nm) {


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
            "name": "activation_update",
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
                            "text": "' . $details['sim_type'] . '"
                        },
                        {
                            "type": "text",
                            "text": "' . $details['link'] . '"
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
            $response;
        }
    }
    //
    public static function CoordinationWhatsApp($details)
    {
        // return $details;
        $token = env('FACEBOOK_TOKEN');

        // return $details['lead_no'];

        foreach (explode(',', $details['numbers']) as $nm) {


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
            "name": "assign_to_act",
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
                            "text": "' . $details['sim_type'] . '"
                        },
                        {
                            "type": "text",
                            "text": "' . $details['eti_lead_id'] . '"
                        },
                        {
                            "type": "text",
                            "text": "' . $details['link'] . '"
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
            // echo $response;
        }
    }
    //
    public static function DNCWhatsApp($details)
    {
        // return $details;
        $token = env('FACEBOOK_TOKEN');

        // return $details['lead_no'];

        foreach (explode(',', $details['numbers']) as $nm) {


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
                    "name": "dnc_update",
                    "language": {
                        "code": "en_US"
                    },
                    "components": [
                        {
                            "type": "body",
                            "parameters": [
                                {
                                    "type": "text",
                                    "text": "' . $details['dnc_number'] . '"
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
            $response;
        }
        // return "zoom";
        return back()->with('success', 'Add successfully.');
        // return redirect(route('add.dnc.number.agent'));
    }
    // /
    public static function TrainingWhatsApp($details)
    {
        // return $details;
        $token = env('FACEBOOK_TOKEN');

        // return $details['lead_no'];
        $number = '923121337222,97143032080';

        foreach (explode(',', $number) as $nm) {


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
            "name": "training_code",
            "language": {
                "code": "en_US"
            },
            "components": [
                {
                    "type": "body",
                    "parameters": [
                        {
                            "type": "text",
                            "text": "' . $details['code'] . '"
                        },
                        {
                            "type": "text",
                            "text": "' . $details['email'] . '"
                        },
                        {
                            "type": "text",
                            "text": "' . $details['call_center'] . '"
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
            $response;
        }
    }
    public static function PromoWhatsApp($similar_number, $agent_whatsapp_number, $k)
    {
        // return $details;

        $token = env('FACEBOOK_TOKEN');

        // return $details['lead_no'];
        // $number = '923121337222,971522221220,923123500256';

        // foreach (explode(',',$number) as $nm) {


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
            "to": "' . $k . '",
            "type": "template",
            "template": {
                "name": "new_promo",
                "language": {
                    "code": "en_US"
                },
                "components": [
                    {
                        "type": "body",
                        "parameters": [
                            {
                                "type": "text",
                                "text": "' . $similar_number . '"
                            },
                            {
                                "type": "text",
                                "text": "' . $agent_whatsapp_number . '"
                            }


                        ]
                    }
                ]
            }
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer EAARZAZA9molUwBALSAVdKuxlZBgt2RsqQeqLg7DLYXOMc5mADTBdF8ZCK8cFDNseWXZB7zq9AlQyy80X1igbEdakXNvyieWQ6hEpmToUm0faINSW3IZCnEliBkVvdcBZBJWwICirbCJPYJx3ox6wNAyDMZApobQyh1XeVHGZCZA3MgP6MOEFyaPoNA'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;
    }
    // }
}
