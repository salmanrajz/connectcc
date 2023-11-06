<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
// use Thomasjohnkane\Snooze\Traits\SnoozeNotifiable;
// use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;
use Yadahan\AuthenticationLog\AuthenticationLogable;
use Illuminate\Database\Eloquent\SoftDeletes;
// use NotificationChannels\WebPush\HasPushSubscriptions;
// use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\HasApiTokens;





class User extends Authenticatable
{
    use Notifiable, HasRoles, AuthenticationLogable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password','roleid','agent_code','role','profile','emirate','phone','notify_email','jobtype','sl','cnic_front','cnic_back', 'multi_agentcode', 'call_center_ip', 'secondary_ip','secondary_email','extension','business_whatsapp', 'business_whatsapp_undertaking','teamleader', 'contact_docs_old','cnic_number', 'is_mnp'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
