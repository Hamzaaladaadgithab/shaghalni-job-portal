<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Storage;

class Resume extends Model
{
    use HasFactory,HasUuids , SoftDeletes;



    protected $table = 'resumes';

    protected $keyType = 'string';
    public $incrementing = false;


    protected $fillable = [
        'filename',
        'fileurl',
        'summary',
        'contactDetails',
        'education',
        'experience',
        'skills',
        'userid',
    ];

    protected $dates=[
        'deleted_at'
    ];


    protected function casts(): array{
        return [
            'deleted_at'=>'datetime'

        ];
    }


    public function user(){
        return $this->belongsTo(User::class,'userid','id');
    }


    public function jobapplications(){
        return $this->hasMany(JobApplication::class,'resumeid','id');
    }

    /**
     * Get the URL for the resume file
     */
    public function getResumeUrl()
    {
        if (!$this->fileurl) {
            return null;
        }

        // For S3, construct URL manually
        $baseUrl = config('filesystems.disks.cloud.url');
        if ($baseUrl) {
            return rtrim($baseUrl, '/') . '/' . ltrim($this->fileurl, '/');
        }

        // Fallback to AWS URL construction
        $bucket = config('filesystems.disks.cloud.bucket');
        $region = config('filesystems.disks.cloud.region');
        
        if ($bucket && $region) {
            return "https://{$bucket}.s3.{$region}.amazonaws.com/{$this->fileurl}";
        }

        return null;
    }

    /**
     * Get a temporary URL for the resume file
     */
    public function getTemporaryUrl($minutes = 5)
    {
        return $this->getResumeUrl();
    }

    




}
