<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class FunctionController extends Controller
{
    //
    public function logout(){
        auth()->logout();
        Session()->flush();

        return \Redirect::to('/');
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
}
