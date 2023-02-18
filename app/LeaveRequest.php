<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = ['type', 'period_type', 'from_date', 'to_date', 'reason'];

    /**
     * Get the user that owns the LeaveRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function leaveRequestAttachments()
    {
        return $this->hasMany(LeaveRequestAttachment::class, 'leave_request_id', 'id');
    }
}
