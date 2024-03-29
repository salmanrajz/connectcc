<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Example Routes
Route::get('/', function () {
    return redirect()->route('login');
    // return redirect('home/landing/page');
});

// Route::view('/', 'landing');
Route::match(['get', 'post'], '/dashboard', function(){
    return view('dashboard');
})->middleware('auth');
Route::view('/pages/slick', 'pages.slick');
Route::view('/pages/datatables', 'pages.datatables');
Route::view('/pages/blank', 'pages.blank');
Auth::routes();



// composer require laravel/ui --dev
// php artisan ui bootstrap --auth
// npm install && npm run dev

Auth::routes();

Route::get('/test', [App\Http\Controllers\HomeController::class, 'test'])->name('test');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('skill-auto-complete-multi-channel', [App\Http\Controllers\AjaxController::class, 'dataAjaxMultiChannel'])->name('skill-auto-complete-multi-channel');
Route::get('skill-auto-complete', [App\Http\Controllers\AjaxController::class, 'dataAjax'])->name('skill-auto-complete')->middleware('auth');
Route::post('CheckPackageName', [App\Http\Controllers\AjaxController::class, 'CheckPackageName'])->name('ajaxRequest.CheckPackageName')->middleware('auth');
// Route::post('CheckPackageName', 'AjaxController@CheckPackageName')->name('ajaxRequest.CheckPackageName');

// Route::get('/skill-auto-complete', 'AjaxController@dataAjax')->name('skill-auto-complete')->middleware('auth');
Route::group(['prefix' => 'admin','middleware'=>'auth'], function () {
    Route::get('/add-users', [App\Http\Controllers\UserController::class, 'AddUsers'])->name('add.users');
    Route::get('/view-users', [App\Http\Controllers\UserController::class, 'viewusers'])->name('view.users');
    Route::get('/delete-user', [App\Http\Controllers\UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/edit-user/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('user.edit');
    Route::get('/master-login/{id}', [App\Http\Controllers\UserController::class, 'MasterLogin'])->name('master.login');
    // Route::get('AgentReport/{id}', 'ExportController@AgentReport')->name('AgentReport')->middleware('role:Admin|superAdmin');
    Route::get('/AgentReport/{id}', [App\Http\Controllers\ExportController::class, 'AgentReport'])->name('AgentReport');
    Route::get('/UserLog/{id}', [App\Http\Controllers\ExportController::class, 'UserLog'])->name('UserLog');
    Route::get('/AgentMonthlySale/{id}', [App\Http\Controllers\UserController::class, 'AgentMonthlySale'])->name('AgentMonthlySale');
    Route::post('/AssignUser', [App\Http\Controllers\UserController::class, 'AssignUser'])->name('user.assign.data');
    Route::post('/UserStore', [App\Http\Controllers\UserController::class, 'UserStore'])->name('admin.user.store');

    //
    Route::get('MainReport/{channel}', 'App\Http\Controllers\ReportController@DailyReport')->name('daily.report');
    Route::get('PreviousReport/{channel}', 'App\Http\Controllers\ReportController@PreviousReport')->name('previous.report');
    Route::get('DailyReport-{slug}', 'App\Http\Controllers\ReportController@DailyReportCallCenter')->name('daily.report.callcenter');
    Route::get('NewMainReport/{channel}', 'App\Http\Controllers\ReportController@NewDailyReport')->name('NewDailyReport');
    Route::get('MonthlySummary-{slug}', 'App\Http\Controllers\ReportController@MonthlyReportCallCenter')->name('daily.report.summary');
    Route::get('/myactivation/{slug}', 'App\Http\Controllers\ReportController@myactivation')->name('myactivation')->middleware('auth');
    Route::get('pending-agent', '\App\Http\Controllers\AgentController@pendinguser')->name('pending.agent');
    Route::post('Approved-User', '\App\Http\Controllers\AgentController@approved')->name('approved.user')->middleware('auth');
    Route::get('/agent-proceed-assigned/{id}', '\App\Http\Controllers\CoordinaterController@agent_assigned')->name('agent_assigned')->middleware('auth');
    Route::get('available-export', '\App\Http\Controllers\ExportController@available_export')->name('available.export')->middleware('auth');




    //


    // Route::get('/dnc-checker-number', 'ImportExcelController@dnc_checker_number')->name('dnc_checker_number')->middleware('auth');
    // Route::post('/dnc-checker-number-new', 'ImportExcelController@dnc_checker_number_new')->name('dnc.checker.number.list')->middleware('auth');
    // Route::post('/AssignUser', 'UserController@AssignUser')->name('user.assign.data')->middleware('role:Admin|Superadmin');




});
Route::group(['prefix' => 'Coordination','middleware'=>'auth'], function () {
    Route::get('add-request-agent', [App\Http\Controllers\AjaxController::class, 'AddRequestAgent'])->name('request-agent.index');
    Route::post('RequestAgentStore', [App\Http\Controllers\AjaxController::class, 'RequestAgentStore'])->name('RequestAgentStore');
});
Route::group(['prefix' => 'Coordination','middleware'=>'auth'], function () {
    Route::post('LoadMainCordData', [App\Http\Controllers\AjaxController::class, 'LoadMainCordData'])->name('admin.LoadMainCordData');
    Route::get('time-out', [App\Http\Controllers\CoordinaterController::class, 'timeout'])->name('time.out');
    Route::get('appointment', [App\Http\Controllers\CoordinaterController::class, 'appointment_lead'])->name('appointment');
    Route::get('later-lead-today', [App\Http\Controllers\CoordinaterController::class, 'later_lead_today'])->name('laterlead.today');
    Route::get('proceed-lead-daily', [App\Http\Controllers\CoordinaterController::class, 'agent_proceed_lead_daily'])->name('activation.proceed.daily');
    Route::get('my-lead-daily', [App\Http\Controllers\CoordinaterController::class, 'myproceedleaddaily'])->name('my.proceed.daily');
    Route::get('follow-up-combine', [App\Http\Controllers\CoordinaterController::class, 'followupcombine'])->name('followup.combine');
    Route::get('follow-up-combine-daily', [App\Http\Controllers\CoordinaterController::class, 'followupcombinedaily'])->name('followup.combine.today');
    Route::get('total-active-lead', [App\Http\Controllers\ActivationController::class, 'totalactivelead'])->name('total-active-lead');
    Route::get('total-active-lead-daily', [App\Http\Controllers\CoordinaterController::class, 'totalactiveleaddaily'])->name('total-active-lead.daily');
    Route::get('total-reject-lead', [App\Http\Controllers\CoordinaterController::class, 'totalrejectlead'])->name('total-reject-daily');
    Route::get('total-reject-lead-daily', [App\Http\Controllers\CoordinaterController::class, 'totalrejectleaddaily'])->name('total-reject-lead-daily');
    //
    Route::get('total-follow-lead', [App\Http\Controllers\CoordinaterController::class, 'totalfollowlead'])->name('total-follow-lead');
    Route::get('total-reverify-lead', [App\Http\Controllers\CoordinaterController::class, 'totalreverifylead'])->name('total-reverify-daily');
    Route::get('total-reverify-lead-daily', [App\Http\Controllers\CoordinaterController::class, 'totalreverifyleaddaily'])->name('total-reverify-lead-daily');
    //
    Route::get('active-non-verified', [App\Http\Controllers\CoordinaterController::class, 'active_non_verified'])->name('admin.active.non.verified');
    Route::get('active-non-verified-daily', [App\Http\Controllers\CoordinaterController::class, 'active_non_verified_daily'])->name('admin.active.non.verified.daily');
    //
    Route::get('my-lead-yesterday', [App\Http\Controllers\CoordinaterController::class, 'myproceedleadyesterday'])->name('my.proceed.yesterday');
    Route::get('later-lead-all', [App\Http\Controllers\CoordinaterController::class, 'later_lead_all'])->name('laterlead.all');
    Route::get('proceed-lead-daily', [App\Http\Controllers\CoordinaterController::class, 'agent_proceed_lead_daily'])->name('activation.proceed.daily');
    Route::get('manage-cordination/{id}', [App\Http\Controllers\AjaxController::class, 'manage_cordinator'])->name('manage-cordination');
    Route::post('update-time', [App\Http\Controllers\AjaxController::class, 'update_time'])->name('update.time');
    Route::get('add-activation/{id}', [App\Http\Controllers\ActivationController::class, 'AddActivation'])->name('activation.edit');
    Route::get('ActivationFollow', [App\Http\Controllers\AjaxController::class, 'ActivationFollow'])->name('activation.follow');
    Route::post('lead-location-store', [App\Http\Controllers\AjaxController::class, 'leadlocationstore'])->name('lead-location.store');
    // Route::get('ActivationFollow', 'AjaxController@ActivationFollow')->name('activation.follow')->middleware('role:Admin|superAdmin');
    //

    Route::post('LeadReAssign', '\App\Http\Controllers\AjaxController@LeadReAssign')->name('lead.re-assign');
    Route::post('activate-lead', 'App\Http\Controllers\ActivationController@ActiveNew')->name('activate-lead');
    Route::post('activate-nonverified', 'App\Http\Controllers\ActivationController@ActiveNewNonVerified')->name('activate-nonverified');
    Route::post('confirm-lead', 'App\Http\Controllers\ActivationController@ConfirmLead')->name('confirm-lead');

    Route::post('ActivationStore', [App\Http\Controllers\ActivationController::class, 'ActivationStore'])->name('activation.store');
    Route::post('LaterLead', [App\Http\Controllers\AjaxController::class, 'LaterLead'])->name('LaterLead');
    // Route::get('/manage-cordination/{id}', 'AjaxController@manage_cordinator')->name('manage-cordination')->middleware('auth');

    Route::get('/emirate-time-out', '\App\Http\Controllers\CoordinaterController@emiratetimeout')->name( 'emirate.time.out')->middleware('auth');
    Route::get('/emirate-appointment', '\App\Http\Controllers\CoordinaterController@emirate_appointment_lead')->name('emirate.appointment')->middleware('auth');
    Route::get('/emirate-proceed/{id}', '\App\Http\Controllers\CoordinaterController@emirate')->name('emirate-cordination')->middleware('auth');
    Route::get('/emirate-proceed-assigned/{id}', '\App\Http\Controllers\CoordinaterController@emirate_assigned')->name('emirate-cordination-proceed')->middleware('auth');
    // Route::post('LaterLead', 'AjaxController@LaterLead')->name('LaterLead')->middleware('auth');
    //
        Route::get('/emirate-total-active-lead-daily', '\App\Http\Controllers\ActivationController@emiratetotalactiveleaddaily')->name('emirate.total-active-lead.daily')->middleware('auth');
    Route::get('/emirate-total-reject-lead-daily', '\App\Http\Controllers\ActivationController@emiratetotalrejectleaddaily')->name('emirate.total-reject-lead-daily')->middleware('auth');
    Route::get('/emirate-total-reverify-lead-daily', '\App\Http\Controllers\ActivationController@emiratetotalreverifyleaddaily')->name('emirate.total-reverify-lead-daily')->middleware('auth');
    Route::get('/emirate-proceed-lead-daily', '\App\Http\Controllers\CoordinaterController@emirate_agent_proceed_lead_daily')->name('emirate.activation.proceed.daily')->middleware('auth');
    Route::get('/emirate-later-lead-today', '\App\Http\Controllers\CoordinaterController@emirate_later_lead_today')->name('emirate.laterlead.today')->middleware('auth');
    Route::get('/emirate-my-lead-daily', '\App\Http\Controllers\CoordinaterController@emiratemyproceedleaddaily')->name('emirate.my.proceed.daily')->middleware('auth');
    Route::get('/emirate-my-lead-yesterday', '\App\Http\Controllers\CoordinaterController@emiratemyproceedleadyesterday')->name('emirate.my.proceed.yesterday')->middleware('auth');
    Route::get('/emirate-follow-up-combine-daily', '\App\Http\Controllers\CoordinaterController@emiratefollowupcombinedaily')->name('emirate.followup.combine.today');
    // Route::get('/proceed-lead-daily', 'CoordinaterController@agent_proceed_lead_daily')->name('activation.proceed.daily')->middleware('auth');

    //
    // Route::get('/my-lead-yesterday', 'CoordinaterController@myproceedleadyesterday')->name('my.proceed.yesterday')->middleware('auth');
    // Route::get('/later-lead-all', 'CoordinaterController@later_lead_all')->name('laterlead.all')->middleware('auth');
    Route::get('/checknumber', '\App\Http\Controllers\MainController@checknumber')->name('checknumber.status')->middleware('auth');
    Route::get('/checkleadnumber', '\App\Http\Controllers\MainController@checkleadnumber')->name('checkleadnumber.status')->middleware('auth');
    Route::get('/checkreservednumber', '\App\Http\Controllers\MainController@checkreservednumber')->name('checkreservednumber.status')->middleware('auth');
    Route::get('/checkcustomernumber', '\App\Http\Controllers\MainController@checkcustomernumber')->name('checkcustomernumber.status')->middleware('auth');
    Route::get('/checknumberoriginal', '\App\Http\Controllers\MainController@checknumberoriginal')->name('checknumberoriginal.status')->middleware('auth');

    Route::post('number-search', '\App\Http\Controllers\AjaxController@number_search_lead')->name('number.search.lead');
    Route::post('number-search-original', '\App\Http\Controllers\AjaxController@number_original_lead')->name('number.original.lead');
    Route::post('number-search-original-reserved', '\App\Http\Controllers\AjaxController@number_original_lead_reserved')->name('number.original.lead.reserved');
    Route::post('number-save-reserved', '\App\Http\Controllers\AjaxController@numbersavereserved')->name('numbersavereserved');
    Route::post('number-reserved-search', '\App\Http\Controllers\AjaxController@number_reserved_search_lead')->name('number.reserved.search.lead');
    Route::post('customer-number-search', '\App\Http\Controllers\AjaxController@customer_number_search_lead')->name('customernumber.search.lead');

    //



    //
    // Route::get('/active-non-verified', 'AgentController@active_non_verified')->name('admin.active.non.verified')->middleware('auth');
    // Route::get('/active-non-verified-daily', 'AgentController@active_non_verified_daily')->name('admin.active.non.verified.daily')->middleware('auth');
    // Route::get('/total-follow-lead', 'ActivationController@totalfollowlead')->name('total-follow-lead')->middleware('auth');
    // Route::get('/total-reverify-lead', 'ActivationController@totalreverifylead')->name('total-reverify-lead')->middleware('auth');
    // Route::get('/total-reverify-lead-daily', 'ActivationController@totalreverifyleaddaily')->name('total-reverify-lead-daily')->middleware('auth');
    // Route::get('/daily-collection', 'ActivationController@daily_collection')->name('daily-collection')->middleware('auth');
    // Route::get('/total-reject-lead', 'ActivationController@totalrejectlead')->name('total-reject-lead')->middleware('auth');
    // Route::get('/total-reject-lead-daily', 'ActivationController@totalrejectleaddaily')->name('total-reject-lead-daily')->middleware('auth');
    // Route::get('/total-active-lead', 'ActivationController@totalactivelead')->name('total-active-lead')->middleware('auth');
    // Route::get('/total-active-lead-daily', 'ActivationController@totalactiveleaddaily')->name('total-active-lead.daily')->middleware('auth');
    // Route::get('/follow-up-combine', 'CoordinaterController@followupcombine')->name('followup.combine')->middleware('auth');
    // Route::get('/follow-up-combine-daily', 'CoordinaterController@followupcombinedaily')->name('followup.combine.today')->middleware('auth');
    //
});
Route::group(['prefix' => 'Verification','middleware'=>'auth'], function () {
    // Route::get('pending_lead-{slug}', 'AjaxController@pending_lead')->name('pending.lead')->middleware('auth');
    Route::get('/pending-lead', [App\Http\Controllers\AjaxController::class, 'pending_lead'])->name('pending.lead');
    Route::post('/AcceptLead', [App\Http\Controllers\AjaxController::class, 'AcceptLead'])->name('ajaxRequest.AcceptLead');
    Route::post('/assign_me', [App\Http\Controllers\AjaxController::class, 'assign_me'])->name('ajaxRequest.Assignme');
    Route::get('/verification-lead/{id}', [App\Http\Controllers\AjaxController::class, 'lead_generate'])->name('verification.lead');
    Route::post('/verified-at-location', [App\Http\Controllers\AgentController::class, 'verified_at_location'])->name('verified.at.location');
    Route::post('/verified-at-whatsapp', [App\Http\Controllers\AjaxController::class, 'verified_at_whatsapp'])->name('verified.at.whatsapp');
    Route::post('/verified-today', [App\Http\Controllers\AjaxController::class, 'verified_today'])->name('verified.today');
    Route::post('/not-answer', [App\Http\Controllers\AjaxController::class, 'not_answer'])->name('not.answer');
    Route::post('/verified-at-active', [App\Http\Controllers\AjaxController::class, 'verified_active'])->name('verification.active.store');
    Route::post('/verified', [App\Http\Controllers\AjaxController::class, 'verified'])->name('admin.verify');
    Route::post('/verification-store', [App\Http\Controllers\AjaxController::class, 'verify_store'])->name('verification.store');
    // Route::post('/assign_me', [App\Http\Controllers\AjaxController::class, 'assign_me'])->name('ajaxRequest.Assignme');
    Route::post('/SaveChanges', [App\Http\Controllers\AjaxController::class, 'SaveChanges'])->name('SaveChanges');
    Route::post('update-lead-number', [App\Http\Controllers\AjaxController::class, 'update_lead_number'])->name('update.lead.number');
    Route::post('update-number', [App\Http\Controllers\AjaxController::class, 'update_number'])->name('update.number');
    Route::post('PlanType2', [App\Http\Controllers\AjaxController::class, 'PlanType2'])->name('ajaxRequest.PlanType2');
    Route::post('checkNumData', [App\Http\Controllers\AjaxController::class, 'checkNumData'])->name('ajaxRequest.checkNumData');

    // Route::post('/update-time', 'AgentController@update_time')->name('update.time')->middleware('auth');

    // Route::get('/later-lead-today', 'CoordinaterController@later_lead_today')->name('laterlead.today')->middleware('auth');
    // Route::get('/proceed-lead-daily', 'CoordinaterController@agent_proceed_lead_daily')->name('activation.proceed.daily')->middleware('auth');
    // Route::get('/my-lead-daily', 'CoordinaterController@myproceedleaddaily')->name('my.proceed.daily')->middleware('auth');
    //
    // Route::get('/follow-up-combine', 'CoordinaterController@followupcombine')->name('followup.combine')->middleware('auth');
    // Route::get('/follow-up-combine-daily', 'CoordinaterController@followupcombinedaily')->name('followup.combine.today')->middleware('auth');
    //
    // Route::get('/appointment', 'CoordinaterController@appointment_lead')->name('appointment')->middleware('auth');
    //
    // Route::get('/time-out', 'CoordinaterController@timeout')->name('time.out')->middleware('auth');

    // Route::post('checkNumData', 'AjaxController@checkNumData')->name('ajaxRequest.checkNumData')->middleware('role:Admin|superAdmin');
    // Route::post('/LoadMainCordData', 'AjaxController@LoadMainCordData')->name('admin.LoadMainCordData')->middleware('auth');

    // Route::post('PlanType2', 'AjaxController@PlanType2')->name('ajaxRequest.PlanType2');

    // Route::post('update-lead-number', [App\Http\Controllers\AjaxController::class, 'update_lead_number'])->name('update.lead.number');
    // Route::post('SaveChanges', 'AjaxController@SaveChanges')->name('SaveChanges')->middleware('auth');
    // Route::post('update-lead-number', 'AjaxController@update_lead_number')->name('update.lead.number');
    // Route::post('update-number', 'AjaxController@update_number')->name('update.number');
    // Route::post('update-lead-number', 'AjaxController@update_lead_number')->name('update.lead.number');


    // Route::post('/verified-at-location', 'AgentController@verified_at_location')->name('verified.at.location')->middleware('auth');
    // Route::post('/verified-at-whatsapp', 'AgentController@verified_at_whatsapp')->name('verified.at.whatsapp')->middleware('auth');
    // Route::post('/verified-today', 'AgentController@verified_today')->name('verified.today')->middleware('auth');
    // Route::post('/not-answer', 'AgentController@not_answer')->name('not.answer')->middleware('auth');
    // Route::post('/verified-at-active', 'AgentController@verified_active')->name('verification.active.store')->middleware('auth');
    // Route::get('/verified', 'AgentController@verified')->name('admin.verify')->middleware('auth');
    //


    // Route::post('AcceptLead', 'AjaxController@AcceptLead')->name('ajaxRequest.AcceptLead')->middleware('auth');
    // Route::post('assign_me', 'AjaxController@assign_me')->name('ajaxRequest.Assignme')->middleware('auth');


});
Route::group(['prefix' => 'Coordination','middleware'=>'auth'], function () {
    Route::get('mygroupleads/{id}/channel/{channel}', [
        'as' => 'showCampaignProductDetails', 'uses' => 'App\Http\Controllers\AjaxController@ShowGroupLeads'
    ]);
    Route::get('Ourgroupleads/{id}/channel/{channel}/call_center/{call_center}', [
        'as' => 'OurshowCampaignProductDetails', 'uses' => 'App\Http\Controllers\AjaxController@OurShowGroupLeads'
    ]);
    Route::get('mygroupleads-daily/{id}/channel/{channel}', [
        'as' => 'showCampaignProductDetailsDaily', 'uses' => 'App\Http\Controllers\AjaxController@ShowGroupLeadsDaily'
    ]);
    Route::get('ourgroupleads-daily/{id}/channel/{channel}/call_center/{call_center}', [
        'as' => 'OurshowCampaignProductDetailsDaily', 'uses' => 'App\Http\Controllers\AjaxController@OurShowGroupLeadsDaily'
    ]);
    Route::get('/add-location-lead/{id}', [App\Http\Controllers\CoordinaterController::class, 'CordinationLead'])->name('verification.add-location-lead');
    Route::get('/add-re-process/{id}', [App\Http\Controllers\CoordinaterController::class, 'reprocess_CordinationLead'])->name('cord.add-location-lead');
    Route::post('/reprocess-group', [App\Http\Controllers\AgentController::class, 'reprocessgroup'])->name('reprocess.group');
    Route::post('/emirate-proceed-lead', [App\Http\Controllers\AgentController::class, 'emirate_proceed_lead'])->name('emirate.proceed.lead');
    // Route::post('/lead-location-store', [App\Http\Controllers\AgentController::class, 'leadlocationstore'])->name('lead-location.store');
    Route::post('/leadrejectedAgent', [App\Http\Controllers\AjaxController::class, 'leadreject'])->name('lead.rejected');
    Route::get('/re-follow-edit/{id}', '\App\Http\Controllers\AgentController@ReFollowEdit')->name('lead.re-follow-process')->middleware('auth');
    // Route::get('/add-location-lead/{id}', 'CoordinaterController@CordinationLead')->name('verification.add-location-lead')->middleware('auth');
    // lead.rejected
    // Route::post('leadrejectedAgent', 'AjaxController@leadreject')->name('lead.rejected');
    // Route::post('/reprocess-group', 'AgentController@reprocessgroup')->name('reprocess.group');

    // Route::get('/add-re-process/{id}', 'CoordinaterController@reprocess_CordinationLead')->name('cord.add-location-lead')->middleware('auth');
    //
    //
});
Route::group(['prefix' => 'Agent','middleware'=>'auth'], function () {

    Route::get('/add-new-lead', [App\Http\Controllers\LeadController::class, 'addnewleads'])->name('add.new.lead');
    Route::post('/customer-number-checker', [App\Http\Controllers\NumberController::class, 'customer_number_checker'])->name('number.tester');
    Route::get('customer-number-dtl/{slug}', [App\Http\Controllers\NumberController::class, 'customer_number_dtl'])->name('customer.number.dtl');
    Route::post('/lead-store-new', [App\Http\Controllers\AjaxController::class, 'leadstorenew'])->name('leadstorenew');
    Route::post('/lead-update-new', [App\Http\Controllers\AjaxController::class, 'leadupdatenew'])->name('leadupdatenew');
    Route::post('/PlanType', [App\Http\Controllers\AjaxController::class, 'PlanType'])->name('ajaxRequest.PlanType');

    Route::get('/view-lead/{id}', [App\Http\Controllers\AgentController::class, 'viewlead'])->name('view.lead');
    Route::get('/edit-lead/{id}', [App\Http\Controllers\AgentController::class, 'editlead'])->name('edit.lead');
    Route::get('myleads', [App\Http\Controllers\AgentController::class, 'agent_lead'])->name('mylead');
    Route::post('mylead-ajax', [App\Http\Controllers\AgentController::class, 'agent_lead_ajax'])->name('mylead.ajax');
    Route::get('view-lead-active/{id}', [App\Http\Controllers\AgentController::class, 'viewleadactive'])->name('view.lead.active');
    Route::get('add-location-lead/{id}', [App\Http\Controllers\CoordinaterController::class, 'CordinationLead'])->name('verification.add-location-lead');
    // Route::get('/add-location-lead/{id}', 'CoordinaterController@CordinationLead')->name('verification.add-location-lead')->middleware('auth');
    Route::get('dnc-checker-new', [App\Http\Controllers\ImportExcelController::class, 'dnc_checker_number'])->name('dnc_checker_number');
    // Route::get('/dnc-checker-new', 'ImportExcelController@dnc_checker_new')->name('dnc_checker_new')->middleware('auth');
    Route::post('customer-feedbac-submit', [App\Http\Controllers\ImportExcelController::class, 'submit_feedback_number'])->name('number.feedback.submit');
    // Route::post('customer-feedbac-submit', 'NumberController@submit_feedback_number')->name('number.feedback.submit')->middleware('auth');

    //
    Route::post('dnc-checker-number-list-new', [App\Http\Controllers\ImportExcelController::class, 'dnc_checker_list_new'])->name('dnc.checker.new.list');
    // Route::post('/dnc-checker-list-new', 'ImportExcelController@dnc_checker_list_new')->name('dnc.checker.new.list')->middleware('auth');

    Route::get('MyWhatsApp/{session_id}', [App\Http\Controllers\ImportExcelController::class, 'MyWhatsApp'])->name('MyWhatsApp');
    // Route::get('MyWhatsApp/{session_id}', 'ImportExcelController@MyWhatsApp')->name('MyWhatsApp')->middleware('auth');
    Route::get('add-new-dncr', [App\Http\Controllers\FunctionController::class, 'AddDncr'])->name('dncr.request');
    Route::post('add-new-dncr-post', [App\Http\Controllers\FunctionController::class, 'AddDncrPost'])->name('AddDncrPost');
    // Route::get('/add-new', 'CourixController@addform')->name('courixform')->middleware('auth');
    Route::get('add-dncr-number-agent', [App\Http\Controllers\ImportExcelController::class, 'dnc_add_number_agent'])->name('add.dnc.number.agent');
    Route::post('add-dnc', [App\Http\Controllers\NumberController::class, 'submit_dnc_number'])->name('submit_dnc_number');
    Route::post('ChatRequest', [App\Http\Controllers\ChatController::class, 'ChatPost'])->name('chat.post');
    // Route::post('ChatRequest', 'ChatController@ChatPost')->name('chat.post');
    Route::get('/ourlead', '\App\Http\Controllers\AgentController@ourlead')->name('admin.ourlead')->middleware('auth');
    Route::post('/ourlead-ajax', '\App\Http\Controllers\AgentController@our_lead_ajax')->name('ourlead.ajax')->middleware('auth');


    // Route::get('/add-dnc-number-agent', 'ImportExcelController@dnc_add_number_agent')->name('add.dnc.number.agent')->middleware('auth');
    // Route::post('/add-dnc', 'NumberController@submit_dnc_number')->name('submit_dnc_number')->middleware('auth');
    //



    // Route::get('/view-lead-active/{id}', 'AgentController@viewleadactive')->name('view.lead.active')->middleware('auth');

    // Route::post('/mylead-ajax', 'AgentController@agent_lead_ajax')->name('mylead.ajax')->middleware('auth');
    //
    // Route::get('/mylead', 'AgentController@agent_lead')->name('admin.mylead')->middleware('auth');
    //
    // Route::get('/view-lead/{id}', 'AgentController@viewlead')->name('view.lead')->middleware('auth');
    // Route::post('PlanType', 'AjaxController@PlanType')->name('ajaxRequest.PlanType');

    // Route::get('/skill-auto-complete-multi-channel', 'AjaxController@dataAjaxMultiChannel')->name('skill-auto-complete-multi-channel')->middleware('auth');

    // Route::post('/lead-store-new', 'AjaxController@leadstorenew')->name('leadstorenew')->middleware('auth');


    // Route::get('customer-number-dtl/{slug}', 'NumberController@customer_number_dtl')->name('customer.number.dtl')->middleware('auth');

    // Route::post('customer-number-checker', 'NumberController@customer_number_checker')->name('number.tester')->middleware('auth');
    // Number System
    // Route::get('number-system-{slug}', 'AjaxController@numbersystem')->name('number-system-ttf');
    Route::get('number-system', [App\Http\Controllers\AjaxController::class, 'numbersystem'])->name('number-system');
    Route::post('ajaxRequest', [App\Http\Controllers\AjaxController::class, 'ajaxRequestPost'])->name('ajaxRequest.post');
    Route::post('NumberByType', [App\Http\Controllers\AjaxController::class, 'NumberByType'])->name('ajaxRequest.NumberByType');
    Route::post('NumberByCallCenter', [App\Http\Controllers\AjaxController::class, 'NumberByCallCenter'])->name('ajaxRequest.NumberByCallCenter');
    Route::post('NumberByType2', [App\Http\Controllers\AjaxController::class, 'NumberByType2'])->name('ajaxRequest.NumberByType2');
    Route::post('ReservedNum', [App\Http\Controllers\AjaxController::class, 'ReservedNum'])->name('ajaxRequest.ReservedNum');
    Route::get('guest-res', [App\Http\Controllers\AjaxController::class, 'guest_res'])->name('guest-res');
    Route::post('BookNUm', [App\Http\Controllers\AjaxController::class, 'BookNUm'])->name('ajaxRequest.BookNum');
    Route::post('RevNum', [App\Http\Controllers\AjaxController::class, 'RevNum'])->name('ajaxRequest.RevNum');
    Route::post('RemoveRevive', [App\Http\Controllers\AjaxController::class, 'RemoveRevive'])->name('Remove.RevNum');
    Route::post('HoldNum', [App\Http\Controllers\AjaxController::class, 'HoldNum'])->name('ajaxRequest.HoldNum');
    // lead . rejected
    // Route::post('NumberByType', 'AjaxController@NumberByType')->name('ajaxRequest.NumberByType');
    // Route::post('NumberByCallCenter', 'AjaxController@NumberByCallCenter')->name('ajaxRequest.NumberByCallCenter');
    // Route::post('NumberByType2', 'AjaxController@NumberByType2')->name('ajaxRequest.NumberByType2');
    // Route::post('ReservedNum', 'AjaxController@ReservedNum')->name('ajaxRequest.ReservedNum');
    // Route::get('guest-res', 'AjaxController@guest_res')->name('guest-res')->middleware('role:Admin|superAdmin');
    // Route::post('BookNUm', 'AjaxController@BookNum')->name('ajaxRequest.BookNum')->middleware('auth');
    // Route::post('RevNum', 'AjaxController@RevNum')->name('ajaxRequest.RevNum')->middleware('auth');
    // Route::post('RemoveRevive', 'AjaxController@RevNum2')->name('Remove.RevNum')->middleware('auth');
    // Route::post('HoldNum', 'AjaxController@HoldNum')->name('ajaxRequest.HoldNum')->middleware('auth');
    // Route::post('ajaxRequest', 'AjaxController@ajaxRequestPost')->name('ajaxRequest.post');
//


    //

});


Route::get('ImportExcel', [App\Http\Controllers\ImportExcelController::class, 'index'])->name('ImportExcel');
Route::post('import', [App\Http\Controllers\ImportExcelController::class, 'import'])->name('import.excel');
Route::get('logout', [App\Http\Controllers\FunctionController::class, 'logout'])->name('logout');
// Route::get('/logout', 'Auth\LoginController@logout');
// Route::get('logout', function () {
//     auth()->logout();
//     Session()->flush();

//     return Redirect::to('/');
// })->name('logout');
Route::get('/search-number', '\App\Http\Controllers\AjaxController@search_number')->name('search-number')->middleware('auth');
Route::get('/search-customer-number', '\App\Http\Controllers\AjaxController@search_customer_number')->name('search-number')->middleware('auth');
Route::get('/search-lead-number', '\App\Http\Controllers\AjaxController@search_lead_number')->name('search-number')->middleware('auth');
Route::post('lead-search', '\App\Http\Controllers\AjaxController@lead_search')->name('lead.no.search')->middleware('auth');
Route::post('ManagerReject', '\App\Http\Controllers\AjaxController@ManagerReject')->name('ajaxRequest.ManagerReject')->middleware('auth');
Route::post('numbersystemstore', '\App\Http\Controllers\AjaxController@numbersystemstore')->name('number-system.store')->middleware('auth');
Route::post('NumberEdit', '\App\Http\Controllers\AjaxController@NumberEdit')->name('NumberEdit');

