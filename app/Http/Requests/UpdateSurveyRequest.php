<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;


class UpdateSurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('survey_update');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:surveys,name,' .request()->route('esurvey'),
            ],
        ];
    }
}
