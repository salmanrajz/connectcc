<?php

namespace App\Http\Controllers;

use App\Models\call_center;
use App\Models\User;
use App\Models\emirate;
use App\Models\Rules\MatchOldPassword;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

// use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;



class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function MasterLogin(Request $request)
    {
        if (auth()->user()->role == 'Admin') {
            $data = User::findorfail($request->id);
            Auth::login($data);
            return redirect()->route('home');
        } else {
            abort(404);
        }
    }
    //
    public function loadpendingusers(Request $request)
    {
        if (auth()->user()->role == 'Admin') {
            // $plan = request_agent::where('status',0);
            $plan = \App\Models\request_agent::all();
        } else {
            $plan = \App\Models\request_agent::where('agent_code', auth()->user()->agent_code)->where('status', 0)->get();
        }
        return view('ajax.requestajaxpendinguser', compact('plan'));
    }
    public function loadusers(Request $request)
    {
        $data = User::withTrashed()
            // ->where('role', '!=','Customer')
            // ->whereIn('users.role', ['NumberAdmin', 'Sale'])
            // ->where('jobtype','target')
            ->get();
        return view('dashboard.ajax.loadusers', compact('data'));
    }
    //
    public function viewusers()
    {
        $data = User::withTrashed()
            // ->where('role', '!=','Customer')
            // ->whereIn('users.role', ['NumberAdmin', 'Sale'])
            // ->where('jobtype','target')
            ->get();
        return view('users.view-users', compact('data'));
    }
    //
    public function user_joining_data()
    {
        // $data = User::withTrashed()
        $data = User::whereIn('users.role', ['NumberAdmin', 'Sale'])
            // ->where('role', '!=','Customer')
            // ->whereIn('users.role', ['NumberAdmin', 'Sale'])
            // ->where('jobtype','target')
            // ->whereYear('users.created_at')
            ->whereYear('users.created_at', Carbon::now()->year)
            ->withTrashed()
            ->get();
        return view('dashboard.users-joining-data', compact('data'));
    }
    public function TargetUser()
    {
        $data = User::select('users.*')
            ->where('role', '!=', 'Customer')
            ->whereIn('users.role', ['NumberAdmin', 'Sale'])
            ->where('jobtype', 'targeted')
            ->get();
        return view('dashboard.view-users-target', compact('data'));
    }
    //
    public function PermanentBlock(Request $request)
    {
        $id = $request->id;
        $data = User::findorfail($id);
        $details = [
            'name' => $data->name,
            'email' => $data->email,
            'request' => 'Permanent Block',
            'request_by' => auth()->user()->name,
            'call_center' => $data->agent_code,
        ];
        $to = 'salmanahmedrajput@outlook.com';
        \Mail::to($to)
            ->cc(['salmanahmed334@gmail.com', 'isqintl@gmail.com'])
            ->send(new \App\Mail\NotifyUserRequest($details));
        // return "1";
        // return $k;
        notify()->info('User Permanent block request has been sent succesfully');
        return redirect(route('active.agent'));
    }
    public function TemporaryBlock(Request $request)
    {
        $id = $request->id;
        $data = User::findorfail($id);
        $details = [
            'name' => $data->name,
            'email' => $data->email,
            'request' => 'Temporary Block',
            'request_by' => auth()->user()->name,
            'call_center' => $data->agent_code,
        ];
        $to = 'salmanahmedrajput@outlook.com';
        \Mail::to($to)
            ->cc(['salmanahmed334@gmail.com', 'isqintl@gmail.com'])
            ->send(new \App\Mail\NotifyUserRequest($details));
        // return "1";
        // return $k;
        notify()->info('User Temporary block request has been sent succesfully');
        return redirect(route('active.agent'));
        // notify()->info('User Temporary block request has been sent succesfully');
    }
    //
    public function active_agent()
    {
        $data = User::where('agent_code', auth()->user()->agent_code)
            ->whereIn('users.role', ['NumberAdmin', 'Sale'])
            ->get();
        return view('dashboard.active-users', compact('data'));
    }
    public function myagentlist()
    {
        $myrole = auth()->user()->multi_agentcode;
        $data = User::whereIn('users.role', ['NumberAdmin', 'Sale'])
            ->when($myrole, function ($query) use ($myrole) {
                if ($myrole == '1') {
                    return $query->where('users.agent_code', auth()->user()->agent_code);
                } else {
                    return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                }
            })
            ->get();
        return view('dashboard.active-users', compact('data'));
    }
    public function create()
    {
        $CallCenter = call_center::wherestatus('1')->get();
        $role = Role::all();
        $permissions = Permission::all();
        $emirates = emirate::all();
        $leaders = User::where('role', 'TeamLeader')->get();
        // $role = Role::findById(2);

        return view('dashboard.add-user', compact('CallCenter', 'role', 'permissions', 'emirates', 'leaders'));
    }
    //
    public function UserProfile(Request $request)
    {
        $user = User::findorfail($request->id);
        return view('dashboard.user-profile', compact('user'));
    }
    //
    public function ProfileUpdateDone(Request $request)
    {
        // return $request->name;


        $validator = Validator::make($request->all(), [ // <---
            // 'de_password' => 'required',
            'email' => [
                'required',
                Rule::unique('secondary_email')->ignore($request->userid),
            ],
            'cnic' => [
                'required',
                Rule::unique('cnic')->ignore($request->userid),
            ],
            'phone' => [
                'required',
                Rule::unique('phone')->ignore($request->userid),
            ],
        ]);
        // return $request;
        // return "Zoom";
        $user = User::findorfail($request->userid);
        // return view('dashboard.user-profile', compact('user'));
        if ($file = $request->file('cnic_front')) {
            //convert image to base64
            $image = base64_encode(file_get_contents($request->file('cnic_front')));
            $image2 = file_get_contents($request->file('cnic_front'));
            // AzureCodeStart
            $originalFileName = time() . $file->getClientOriginalName();
            $multi_filePath = 'user-cnic' . '/' . $originalFileName;
            \Storage::disk('azure')->put($multi_filePath, $image2);
            // AzureCodeEnd
            //prepare request
            $mytime = Carbon::now();
            $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file->getClientOriginalName();
            $cnic_front = $originalFileName;
            $file->move('user-cnic', $cnic_front);
        } else {
            $cnic_front = $request->cnic_front_old;
        }
        if ($file = $request->file('img')) {
            //convert image to base64
            $image = base64_encode(file_get_contents($request->file('img')));
            $image2 = file_get_contents($request->file('img'));
            // AzureCodeStart
            $originalFileName = time() . $file->getClientOriginalName();
            $multi_filePath = 'user-cnic' . '/' . $originalFileName;
            \Storage::disk('azure')->put($multi_filePath, $image2);
            // AzureCodeEnd
            //prepare request
            $mytime = Carbon::now();
            $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file->getClientOriginalName();
            $name = $originalFileName;
            $file->move('user-cnic', $name);
        } else {
            $name = $request->profile;
        }
        if ($file = $request->file('business_whatsapp_undertaking')) {
            //convert image to base64
            $image = base64_encode(file_get_contents($request->file('business_whatsapp_undertaking')));
            $image2 = file_get_contents($request->file('business_whatsapp_undertaking'));
            // AzureCodeStart
            $originalFileName = time() . $file->getClientOriginalName();
            $multi_filePath = 'user-cnic' . '/' . $originalFileName;
            \Storage::disk('azure')->put($multi_filePath, $image2);
            // AzureCodeEnd
            //prepare request
            $mytime = Carbon::now();
            $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file->getClientOriginalName();
            $business_whatsapp_undertaking = $originalFileName;
            $file->move('user-cnic', $business_whatsapp_undertaking);
        } else {
            $business_whatsapp_undertaking = $request->business_whatsapp_undertaking_old;
        }
        if ($file = $request->file('cnic_back')) {
            //convert image to base64
            $image = base64_encode(file_get_contents($request->file('cnic_back')));
            $image2 = file_get_contents($request->file('cnic_back'));
            // AzureCodeStart
            $originalFileName = time() . $file->getClientOriginalName();
            $multi_filePath = 'user-cnic' . '/' . $originalFileName;
            \Storage::disk('azure')->put($multi_filePath, $image2);
            // AzureCodeEnd
            //prepare request
            $mytime = Carbon::now();
            $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file->getClientOriginalName();
            $cnic_back = $originalFileName;
            $file->move('user-cnic', $name);
        } else {
            $cnic_back = $request->cnic_back_old;
        }
        if ($file = $request->file('contact_docs')) {
            //convert image to base64
            $image = base64_encode(file_get_contents($request->file('contact_docs')));
            $image2 = file_get_contents($request->file('contact_docs'));
            // AzureCodeStart
            $originalFileName = time() . $file->getClientOriginalName();
            $multi_filePath = 'user-cnic' . '/' . $originalFileName;
            \Storage::disk('azure')->put($multi_filePath, $image2);
            // AzureCodeEnd
            //prepare request
            $mytime = Carbon::now();
            $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file->getClientOriginalName();
            $contact_docs = $originalFileName;
            $file->move('user-cnic', $name);
        } else {
            $contact_docs = $request->contact_docs_old;
        }
        $user->name = $request->name;
        $user->secondary_email = $request->email;
        $user->business_whatsapp_undertaking = $business_whatsapp_undertaking;
        $user->phone = $request->phone;
        $user->profile = $name;
        $user->cnic_number = $request->cnic;
        $user->cnic_front = $cnic_front;
        $user->cnic_back = $cnic_back;
        $user->extension = $request->extension;
        $user->business_whatsapp = $request->business_whatsapp;
        $user->contact_docs_old = $contact_docs;
        $user->save();
        notify()->success('Data Update succesfully');

        // // return redirect()->back()->withInput();
        return redirect(route('active.agent'));
    }
    //
    public function store(Request $request)
    {
        // return $request;
        // return

        $validator = Validator::make($request->all(), [ // <---
            'name' => 'required|string',
            'call_center_ip' => 'required|string',
            'secondary_ip' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'emirates.*' => 'required',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required'],
            'agent_group' => ['required']
            // 'password' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // if ($file = $request->file('img')) {
        //     // $ext = date('d-m-Y-H-i');
        //     $mytime = Carbon::now();
        //     $ext =  $mytime->toDateTimeString();
        //     $name = $ext . '-' . $file->getClientOriginalName();
        //     $name = Str::slug($name, '-');

        //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
        //     $file->move('image', $name);
        //     $input['path'] = $name;
        // }
        // else{
        //     $name = 'default.png';
        // }
        if ($file = $request->file('cnic_front')) {
            //convert image to base64
            $image = base64_encode(file_get_contents($request->file('cnic_front')));
            $image2 = file_get_contents($request->file('cnic_front'));
            // AzureCodeStart
            $originalFileName = time() . $file->getClientOriginalName();
            $multi_filePath = 'user-cnic' . '/' . $originalFileName;
            \Storage::disk('azure')->put($multi_filePath, $image2);
            // AzureCodeEnd
            //prepare request
            $mytime = Carbon::now();
            $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file->getClientOriginalName();
            $cnic_front = $originalFileName;
            $file->move('user-cnic', $cnic_front);
        } else {
            $cnic_front = 'default.png';
        }
        if ($file = $request->file('img')) {
            //convert image to base64
            $image = base64_encode(file_get_contents($request->file('img')));
            $image2 = file_get_contents($request->file('img'));
            // AzureCodeStart
            $originalFileName = time() . $file->getClientOriginalName();
            $multi_filePath = 'user-cnic' . '/' . $originalFileName;
            \Storage::disk('azure')->put($multi_filePath, $image2);
            // AzureCodeEnd
            //prepare request
            $mytime = Carbon::now();
            $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file->getClientOriginalName();
            $name = $originalFileName;
            $file->move('user-cnic', $name);
        } else {
            $name = 'default.png';
        }
        if ($file = $request->file('cnic_back')) {
            //convert image to base64
            $image = base64_encode(file_get_contents($request->file('cnic_back')));
            $image2 = file_get_contents($request->file('cnic_back'));
            // AzureCodeStart
            $originalFileName = time() . $file->getClientOriginalName();
            $multi_filePath = 'user-cnic' . '/' . $originalFileName;
            \Storage::disk('azure')->put($multi_filePath, $image2);
            // AzureCodeEnd
            //prepare request
            $mytime = Carbon::now();
            $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file->getClientOriginalName();
            $cnic_back = $originalFileName;
            $file->move('user-cnic', $name);
        } else {
            $cnic_back = 'default.png';
        }
        // return implode(',', $request->multi_agentcode);
        // return $request->role;
        $data =   User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'agent_code' => $request->agent_group,
            'multi_agentcode' => implode(',', $request->multi_agentcode),
            'role' => $request->role,
            'password' => Hash::make($request['password']),
            'sl' => $request->password,
            'call_center_ip' => $request->call_center_ip,
            'secondary_ip' => $request->secondary_ip,
            'emirate' => implode(',', $request->emirates),
            'profile' => $name,
            'phone' => $request->phone,
            'cnic_front' => $cnic_front,
            'cnic_back' => $cnic_back,
            'jobtype' => $request->jobtype,
        ]);
        $data->assignRole($request->role);
        if (!empty($request->permissions)) {

            foreach ($request->permissions as $key => $value) {
                # code...
                // auth()->user()->givePermissionTo('manage postpaid');
                $data->givePermissionTo($value);
                // return $value;
            }
        }

        // http://10.141.5.25/vicidial/non_agent_api.php?source=test&user=api&pass=dialup&function=add_user&agent_user=agent10001&agent_pass=1234&agent_user_level=1&agent_full_name=Hayakum+Agent&agent_user_group=HykmGrp01&hotkeys_active=1&agent_choose_ingroups=0&agent_choose_blended=0&closer_default_blended=1

        // $data = call_center::create([
        //     'call_center_name' => $request->name,
        //     'call_center_amount' => $request->call_center_amount,
        //     'call_center_code' => $request->call_center_short_code,
        //     'status' => $request->status,
        // ]);
        // notify()->success('Call Center has been created succesfully');

        // // return redirect()->back()->withInput();
        return redirect(route('user-index'));
    }
    public function AssignUser(Request $request)
    {
        // return $request;
        $id = $request->leadid;
        return view('dashboard.ajax.channel-assigner', compact('id'));
    }
    public function destroy(user $user, $id)
    {
        //
        // return $id;
        $d = user::withTrashed()->find($id);
        if (!is_null($d->deleted_at)) {
            $d->restore();
            // return
            notify()->info('User has been succesfully Enable');
        } else {
            $d->delete();
            // return
            notify()->info('User has been succesfully deleted');
        }

        // // return redirect()->back()->withInput();
        return redirect(route('user-index'));
    }
    //
    public function ChangePassword(Request $request)
    {
        // return $request;
        $user = user::findorfail(auth()->user()->id);
        return view('dashboard.edit-password', compact('user'));
    }
    public function UpdateProfile(Request $request)
    {
        // return $request;
        $user = user::findorfail(auth()->user()->id);
        return view('dashboard.edit-cnic', compact('user'));
    }
    //
    //
    public function ChangePasswordDone(Request $request)
    {
        // return $request;
        $validator = Validator::make($request->all(), [ // <---
            // 'de_password' => 'required',
            'password' => 'required|confirmed|min:8|different:oldpass',
            'oldpass' => ['required', new MatchOldPassword],


        ]);
        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->errors()->all()]);
        // }
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $user = user::findorfail(auth()->user()->id);
        $user->update([
            // 'name' => $request->name,
            // 'email' => $request->email,
            // 'agent_code' => $request->agent_group,
            // 'phone' => $request->phone,
            // 'call_center_ip' => $request->call_center_ip,
            // 'secondary_ip' => $request->secondary_ip,
            // 'jobtype' => $request->jobtype,
            // 'profile' => $name,
            'password' => Hash::make($request->password),
            'sl' => $request->password,
            // 'cnic_front' => $cnic_front,
            // 'cnic_back' => $cnic_back,
            // 'emirate' => implode(',', $request->emirates),

            // 'password' => Hash::make($request->password),
        ]);
        notify()->success('User has been updated Succesfully');
        return redirect(route('home'));
        // $user = user::findorfail(auth()->user()->id);
        // return view('dashboard.edit-password',compact('user'));
    }
    public function UpdateProfileDone(Request $request)
    {
        // return $request;

        $validator = Validator::make($request->all(), [ // <---
            'cnic' => 'required',
            // 'password' => 'required|confirmed|min:8|different:oldpass',
            // 'oldpass' => ['required', new MatchOldPassword],


        ]);
        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->errors()->all()]);
        // }
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $user = user::findorfail(auth()->user()->id);
        $user->update([
            // 'name' => $request->name,
            // 'email' => $request->email,
            // 'agent_code' => $request->agent_group,
            // 'phone' => $request->phone,
            // 'call_center_ip' => $request->call_center_ip,
            // 'secondary_ip' => $request->secondary_ip,
            // 'jobtype' => $request->jobtype,
            'cnic_number' => $request->cnic,
            // 'password' => Hash::make($request->password),
            // 'sl' => $request->password,
            // 'cnic_front' => $cnic_front,
            // 'cnic_back' => $cnic_back,
            // 'emirate' => implode(',', $request->emirates),

            // 'password' => Hash::make($request->password),
        ]);
        notify()->success('User has been updated Succesfully');
        return redirect(route('home'));
        // $user = user::findorfail(auth()->user()->id);
        // return view('dashboard.edit-password',compact('user'));
    }
    //
    public function edit($id)
    {
        $user = user::findorfail($id);
        $CallCenter = call_center::wherestatus('1')->get();
        $role = Role::all();
        $permissions = Permission::all();
        $emirates = emirate::all();
        // $selected_role = Role::findById($id);
        $leaders = User::where('role', 'TeamLeader')->get();


        return view('dashboard.edit-user', compact('user', 'CallCenter', 'role', 'permissions', 'emirates', 'leaders'));
    }
    public function update(Request $request)
    {
        // return $request;
        $user = user::findorfail($request->id);

        if ($file = $request->file('cnic_front')) {
            //convert image to base64
            $image = base64_encode(file_get_contents($request->file('cnic_front')));
            $image2 = file_get_contents($request->file('cnic_front'));
            // AzureCodeStart
            $originalFileName = time() . $file->getClientOriginalName();
            $multi_filePath = 'user-cnic' . '/' . $originalFileName;
            \Storage::disk('azure')->put($multi_filePath, $image2);
            // AzureCodeEnd
            //prepare request
            $mytime = Carbon::now();
            $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file->getClientOriginalName();
            $cnic_front = $originalFileName;
            $file->move('user-cnic', $cnic_front);
        } else {
            $cnic_front =  $request->cnic_front_old;
        }
        if ($file = $request->file('img')) {
            //convert image to base64
            $image = base64_encode(file_get_contents($request->file('img')));
            $image2 = file_get_contents($request->file('img'));
            // AzureCodeStart
            $originalFileName = time() . $file->getClientOriginalName();
            $multi_filePath = 'user-cnic' . '/' . $originalFileName;
            \Storage::disk('azure')->put($multi_filePath, $image2);
            // AzureCodeEnd
            //prepare request
            $mytime = Carbon::now();
            $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file->getClientOriginalName();
            $name = $originalFileName;
            $file->move('user-cnic', $name);
        } else {
            $name =  $request->img_old;
        }
        if ($file = $request->file('cnic_back')) {
            //convert image to base64
            $image = base64_encode(file_get_contents($request->file('cnic_back')));
            $image2 = file_get_contents($request->file('cnic_back'));
            // AzureCodeStart
            $originalFileName = time() . $file->getClientOriginalName();
            $multi_filePath = 'user-cnic' . '/' . $originalFileName;
            \Storage::disk('azure')->put($multi_filePath, $image2);
            // AzureCodeEnd
            //prepare request
            $mytime = Carbon::now();
            $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file->getClientOriginalName();
            $cnic_back = $originalFileName;
            $file->move('user-cnic', $cnic_back);
        } else {
            $cnic_back = $request->cnic_back_old;
        }
        // return  $cnic_front . $cnic_back;
        if ($request->password == '') {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'agent_code' => $request->agent_group,
                'phone' => $request->phone,
                'call_center_ip' => $request->call_center_ip,
                'secondary_ip' => $request->secondary_ip,
                'jobtype' => $request->jobtype,
                'profile' => $name,
                // 'password' => Hash::make($request->password),
                'cnic_front' => $cnic_front,
                'cnic_back' => $cnic_back,
                'emirate' => implode(',', $request->emirates),
                'teamleader' => $request->teamleader,
                'is_mnp' => $request->is_mnp,
                // 'password' => Hash::make($request->password),
            ]);
            notify()->success('User has been updated Succesfully');
            return redirect(route('user-index'));
        } else {
            $user->update([
                'name' => $request->name,
                'agent_code' => $request->agent_group,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'sl' => $request->password,
                'jobtype' => $request->jobtype,
                'profile' => $name,
                'call_center_ip' => $request->call_center_ip,
                'secondary_ip' => $request->secondary_ip,
                'cnic_front' => $cnic_front,
                'cnic_back' => $cnic_back,
                'teamleader' => $request->teamleader,
            ]);
            notify()->success('User has been updated Succesfully');
            return redirect(route('user-index'));
        }
        // $id = $request

        // return $id;
    }
    //
    public function UpdateUserTile(Request $request)
    {
        // return $request;
        // $data = User::findorfail($request->userid);
        if ($request->status == 'remove') {
            $check = \App\Models\AssignChannel::where('userid', $request->userid)->where('name', $request->tileid)->first();
            if ($check) {
                $check->delete();
                return 2;
            }
        } else {
            $data = \App\Models\AssignChannel::create([
                'userid' => $request->userid,
                'name' => $request->tileid,
                // 'status' => '1',
            ]);
            return "1";
        }
        //
    }
    public function AgentMonthlySale(Request $request)
    {
        // return "AgentMonthlySale";
        $userid = $request->id;
        return view('dashboard.view-agent-report-users', compact('userid'));
    }
    public function CallCenterMonthlySale(Request $request)
    {
        // return "AgentMonthlySale";
        $userid = $request->id;
        $users = \App\Models\User::where('agent_code', $request->id)
            ->whereIn("users.role", array('sale', 'NumberAdmin'))
            ->get();
        return view('dashboard.view-agent-report-callcenter', compact('users'));
    }
}
