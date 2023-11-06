<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFaceAi extends Model
{
    //
    protected $fillable = [
        'userid','UserImageUrl','FaceID', 'persistedFaceId', 'PersonGroupID','person_id'
    ];
}
