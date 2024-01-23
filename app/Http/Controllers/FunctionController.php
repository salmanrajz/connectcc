<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FunctionController extends Controller
{
    //
    public function logout(){
        // return "000";
        Auth::logout();
        return redirect(route('login'));
    }
    //
    public function AddDncr(Request $request)
    {
        $data = \App\Models\dncr_files_log::where('user_id', auth()->user()->id)->get();
        return view('agent.add-dncr-file', compact('data'));
    }
    public function ViewDncrRequest(Request $request)
    {
        $data = \App\Models\dncr_files_log::where('generated_file', 'NULLS')->get();
        return view('agent.view-dncr-file', compact('data'));
    }
    public function UploadDncrRequest(Request $request)
    {
        $data = \App\Models\dncr_files_log::findorfail($request->id);
        return view('agent.upload-dncr-file', compact('data'));
    }
    //
    public function AddDncrPost(Request $request)
    {
        if ($file = $request->file('myfile')) {
            $image2 = file_get_contents($file);
            $originalFileName = time() . $file->getClientOriginalName();
            $multi_filePath = 'documents' . '/' . $originalFileName;
            \Storage::disk('azure')->put($multi_filePath, $image2);
            // AzureCodeEnd
            // LocalStorageStart
            $mytime = Carbon::now();
            $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file[$key]->getClientOriginalName();
            $name = $originalFileName;

            // $file->move('documents', $name);
            // $input['path'] = $name;
            // LocalStorageEnd
        }
        $da = \App\Models\dncr_files_log::create([
                'requested_file' => $name,
                'generated_file' => 'NULLS',
                'requested_id' => rand(0, 9),
                'user_id' => auth()->user()->id,
            ]);
        notify()->success('File Upload Succesfully');

        return back()->with('success', 'File Generated successfully.');
    }
    public function UploadDncrPostRequest(Request $request)
    {
        if ($file = $request->file('myfile')) {
            $image2 = file_get_contents($file);
            $originalFileName = time() . $file->getClientOriginalName();
            $multi_filePath = 'documents' . '/' . $originalFileName;
            \Storage::disk('azure')->put($multi_filePath, $image2);
            // AzureCodeEnd
            // LocalStorageStart
            $mytime = Carbon::now();
            $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file[$key]->getClientOriginalName();
            $name = $originalFileName;

            // $file->move('documents', $name);
            // $input['path'] = $name;
            // LocalStorageEnd
        }
        $data = \App\Models\dncr_files_log::findorfail($request->id);
        $data->generated_file = $name;
        $data->save();
        // $da = \App\Models\dncr_files_log::create([
        //     'requested_file' => $name,
        //     'generated_file' => 'NULLS',
        //     'requested_id' => rand(0,9),
        //     'user_id' => auth()->user()->id,
        // ]);
        notify()->success('File Upload Succesfully');

        return redirect(route('ViewDncrRequest'));
        // return
        // return back()->with('success', 'File Generated successfully.');

    }
    //
    public static function MyTotalSale($id)
    {
        // return $id;
       return $active = \App\Models\activation_form::select('activation_forms.created_at')
        ->LeftJoin(
            'lead_sales',
            'lead_sales.id',
            'activation_forms.lead_id'
        )
            ->Join(
                'users',
                'users.id',
                'lead_sales.saler_id'
            )
            ->where('activation_forms.status', '1.02')
            // ->whereIn('lead_sales.lead_type', ['postpaid', 'HomeWifi'])
            // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
            ->where('users.id', $id)
            ->orderBy('activation_forms.created_at', 'desc')
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->get()->count();

        // ->count();
        // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
        // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
        // ->where('users.id', $id)
    }
}
