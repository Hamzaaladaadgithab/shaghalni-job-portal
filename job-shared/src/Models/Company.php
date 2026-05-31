<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Company extends Model
{
    use HasFactory,HasUuids , SoftDeletes;

    protected $table = 'companies';


    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
    'name',
    'address',
    'industry',
    'website',
    'ownerid',
];



protected $dates=[
        'deleted_at'
    ];


    protected function casts(): array{
        return [
            'deleted_at'=>'datetime'

        ];
    }


    public function owner(){
        return $this->belongsTo(User::class,'ownerid','id');
    }


    public function jobvacancies(){
        return $this->hasMany(JobVacancy::class,'company_id','id');
    }


    public function jobapplications(){
        return $this->hasManyThrough(JobApplication::class,JobVacancy::class,'company_id','jobvacancyid','id','id');
    }




}
