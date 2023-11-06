<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class candidate_data extends Model
{
    //
    protected $fillable = [
            'name','email','phone','schedule_date','schedule_time','location','manager_id','message','manager_feedback','call_center','source','status','id_status', 'second_remarks', 'last_experience', 'total_exposure', 'country', 'call_center_experience', 'last_salary', 'expected_salary', 'any_other_engagement'
    ];
}
