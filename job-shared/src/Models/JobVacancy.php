<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class JobVacancy extends Model
{

    use HasFactory,HasUuids , SoftDeletes;



    protected $table = 'job_vacancies';

    protected $keyType = 'string';
    public $incrementing = false;



    protected $fillable = [
        'title',
        'description',
        'location',
        'type',
        'salary',
        'company_id',
        'jobcategory_id',
    ];


    protected $dates=[
        'deleted_at'
    ];

    protected function casts(): array{
        return [
            'deleted_at'=>'datetime'

        ];
    }



    public function jobcategory(){
        return $this->belongsTo(JobCategory::class,'jobcategory_id','id');
    }

    public function company(){
        return $this->belongsTo(Company::class,'company_id','id');
    }



    public function jobapplications(){
        return $this->hasMany(JobApplication::class,'jobvacancyid','id');
    }



    public function relatedJobs()
    {
        return $this->hasMany(JobVacancy::class, 'company_id', 'company_id')
                    ->where('id', '!=', $this->id);
    }










}
