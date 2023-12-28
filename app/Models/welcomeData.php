<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class welcomeData extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function installProcess()
    {
        return $this->belongsTo(InstallProcess::class, 'install_process_id');
    }
    public function remarkField()
    {
        return $this->belongsTo(RemarkField::class, 'remark_field_id');
    }
    public function applicantResponse()
    {
        return $this->belongsTo(ApplicantResponse::class, 'applicant_response_id');
    }
}
