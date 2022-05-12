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
                    @foreach ($surveyRating as $question => $result)
                        <tr>
                            <th>
                                {{$question}}
                            </th>
                            <td>
                                @if(is_array($result))
                                    <table class="table table-bordered table-striped"> 
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Points</th>
                                                <th>Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalPercentage = 0;
                                                $i = 0;
                                            @endphp
                                            @foreach ($result as $row => $value)
                                                <tr>
                                                    <td>{{$row}}</td>
                                                    <td>{{$value}}</td>
                                                    <td>{{round($value/5*100,2)}}%</td>
                                                </tr>
                                            @php
                                                $totalPercentage+=$value/5*100;
                                                $i++;
                                            @endphp
                                            @endforeach
                                                <tr>
                                                    <td>Total Average</td>
                                                    <td></td>
                                                    <td>{{round($totalPercentage/$i,2)}}%</td>
                                                </tr>
                                        </tbody>
                                        
                                    </table>

                                @else
                                    <button type="button" class="btn btn-outline-secondary">{{$result}}</button>
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