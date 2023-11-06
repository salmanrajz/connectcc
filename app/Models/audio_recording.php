<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class audio_recording extends Model
{
    //
    protected $fillable = [
        'audio_file',
        'username',
        'lead_no',
        'status',
    ];
}
