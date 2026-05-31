<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class JobApplication extends Model
{
    use HasFactory,HasUuids , SoftDeletes;


    protected $table = 'job_applications';


    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'status',
        'aigeneratedscore',
        'aigeneratedfeedback',
        'userid',
        'resumeid',
        'jobvacancyid',
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

    public function jobvacancy(){
        return $this->belongsTo(JobVacancy::class,'jobvacancyid','id');
    }


    public function resume(){
        return $this->belongsTo(Resume::class,'resumeid','id');
    }


















}
