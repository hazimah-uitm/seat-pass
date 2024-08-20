<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Staff extends Model
{
    use Notifiable, LogsActivity, HasRoles, SoftDeletes;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'no_pekerja',
        'email',
        'attendance',
        'category',
        'department',
        'campus',
        'club',
        'payment',
        'status',
        'created_at',
    ];

    public $timestamps = true;

    public function booking()
    {
        return $this->hasOne(Booking::class, 'staff_id');
    } 
}
