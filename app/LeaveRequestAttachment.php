<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveRequestAttachment extends Model
{
    protected $table = 'leave_requests_attachments';
    protected $filable = ['id', 'file', 'leave_request_id'];

    public function leaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class, 'leave_request_id', 'id');
    }
}
