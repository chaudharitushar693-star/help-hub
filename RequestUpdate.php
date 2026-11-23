<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestUpdate extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'assistance_request_id',
        'updated_by',
        'status',
        'comment',
        'created_at',
    ];

    public function assistanceRequest()
    {
        return $this->belongsTo(AssistanceRequest::class);
    }
}
