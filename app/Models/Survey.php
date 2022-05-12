<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ESurveyTrait;
use OwenIt\Auditing\Contracts\Auditable;

use AidynMakhataev\LaravelSurveyJs\app\Models\SurveyResult;



class Survey extends Model implements Auditable
{
    use SoftDeletes;
    use ESurveyTrait;
    use \OwenIt\Auditing\Auditable;

    public $table = 'surveys';

    protected $fillable = [
        'name', 'slug', 'json', 'task_status_id', 'pamu',
    ];


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function team(){
        return $this->belongsTo(Team::class,'team_id','id');
    } 

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by','id');
    } 

    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by','id');
    } 

    public function task_status(){
        return $this->belongsTo(TaskStatus::class,'task_status_id','id');
    }

    public function survey_result(){
        return $this->hasMany(SurveyResult::class,'survey_id','id')
            ->selectRaw('survey_id, count(survey_id) as total')
            ->groupBy('survey_id');
    }

    public function survey_respondents(){
        return $this->hasMany(SurveyResult::class,'survey_id','id');
    }
    
}
