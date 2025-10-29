<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendVisitReminderJob;
class Visit extends Model
{
    use HasFactory;

    protected $casts = [
        'start' => 'datetime',
    ];

    protected $guarded = [];

    public function emps() {
        return $this->hasMany(VisitEmp::class);
    }

    public function emps_requester() {
        return $this->hasMany(VisitEmp::class)->where('type', 'requester');
    }
    public function emps_recipients() {
        return $this->hasMany(VisitEmp::class)->where('type', 'recipient');
    }

    public function is_recipient() {
        return $this->hasMany(VisitEmp::class)
            ->where('type', 'recipient')
            ->where('user_id', Auth::id())
            ->exists();
    }

    public function is_requester() {
        return $this->hasMany(VisitEmp::class)
            ->where('type', 'requester')
            ->where('user_id', Auth::id())
            ->exists();
    }

    public function reminder($visit)
    {

        SendVisitReminderJob::dispatch($visit, $visit->user->email)
            ->delay(now()->addDays($visit->reminder_delay_days));
    }
}
