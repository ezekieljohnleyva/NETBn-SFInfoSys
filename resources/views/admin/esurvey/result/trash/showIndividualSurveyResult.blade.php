@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('Individual Survey Result') }}
    </div>
    {{-- {{dd($rating['generatedPerSurveyResultRatings'])}} --}}
    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ URL::previous()}}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    @foreach ($rating['generatedPerSurveyResultRatings'] as $generatedPerSurveyResultRatings)
                        @php
                            $avgPercentage  = 0;
                            $i=0;
                        @endphp
                            <tr>
                                <td colspan="1">
                                    {{ $generatedPerSurveyResultRatings['title']??'' }}
                                </td>
                                <td colspan="5">
                                    @if($generatedPerSurveyResultRatings['value'])
                                            <button type="button" class="btn btn-outline-secondary disabled">{{$generatedPerSurveyResultRatings['value']}}</button>
                                    @elseif(is_array($generatedPerSurveyResultRatings['rating']))
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Points</th>
                                                        <th>Average</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($generatedPerSurveyResultRatings['rating'] as $key => $item)
                                                        @php
                                                            $avgPercentage  = $avgPercentage + ($item['percentage']);
                                                            $i++;
                                                        @endphp
                                                        <tr>
                                                            <th>
                                                                {{$key}}
                                                            </th>
                                                            <td>
                                                                {{$item['points']?$item['points']:''}}
                                                            </td>
                                                            <td>
                                                                {{$item['percentage']?$item['percentage'].'%':''}}
                                                                
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <th>
                                                            {{'Total Average'}}
                                                        </th>
                                                        <td>
                                                            {{-- {{$generatedPerSurveyResultRatings['totalPoints']}} --}}
                                                        </td>
                                                        <td>
                                                            {{-- {{$generatedPerSurveyResultRatings['totalPercentage']?$generatedPerSurveyResultRatings['totalPercentage'].'%':''}} --}}
                                                            {{round($avgPercentage/$i,2).'%'}}
                                                        </td>
                                                    </tr>
                                                    
                                                </tbody>
                                            </table>
                                    @else
                                            {{ $generatedPerSurveyResultRatings['rating']?$generatedPerSurveyResultRatings['rating'].'%':'' }}
                                    @endif
                                </td>
                               
                            </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ URL::previous()}}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection