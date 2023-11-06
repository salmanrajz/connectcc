<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dncr_files_log extends Model
{
    use HasFactory;
    protected $fillable = [
        'requested_file', 'generated_file', 'requested_id', 'user_id'
    ];
}
