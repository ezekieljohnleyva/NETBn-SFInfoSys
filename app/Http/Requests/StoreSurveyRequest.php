<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;

class StoreSurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('survey_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:surveys,name,NULL,deleted_at,deleted_at,NULL'
            ],
            'task_status_id' => [
                'integer'
            ]
        ];
    }
}
