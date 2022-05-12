@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Over Survey Result
        </div>
    
        <div class="card-body" id="groupCollapse" >
            @foreach ($unitList as $item)
                <button type="button" class="btn btn-primary" data-toggle="collapse" data-target={{'#'.$item}}>{{$item}}</button>  
            @endforeach

            @foreach ($generatedPerSurveyUnitOverAllRatings as $key => $generatedPerSurveyUnitOverAllRating)
                    {{-- {{dd($generatedPerSurveyUnitOverAllRatings['CSBn'])}} --}}
                <div id={{$key}} class="collapse" data-parent="#groupCollapse">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            @foreach ($generatedPerSurveyUnitOverAllRating as $generatedPerSurveyResultRatings)
                                    @php
                                        $avgPercentage  = 0;
                                        $i=0;
                                    @endphp
                                    <tr>
                                        <td >
                                            {{ $generatedPerSurveyResultRatings['title']??'' }}
                                        </td>
                                        <td >
                                            @if($generatedPerSurveyResultRatings['value'])
                                                @if(is_array($generatedPerSurveyResultRatings['value']))
                            
                                                @foreach ($generatedPerSurveyResultRatings['value'] as $key => $item)
                                                    {{-- <h4><span class="badge badge-secondary">{{$item}}</span></h4> --}}
                                                    <button type="button" class="btn btn-outline-secondary disabled">{{$item}}</button>
                                                @endforeach
                                                @else
                                                    {{$generatedPerSurveyResultRatings['value']}}
                                                @endif
                                                
                                            @elseif(is_array($generatedPerSurveyResultRatings['rating']))
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>{{'Title'}}</th>
                                                                <th>{{'No of Respondents'}}</th>
                                                                <th>{{'Points'}} </th>
                                                                <th>{{'Percentage'}} </th>
                                                                <th>{{'Average'}} </th>
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                        

                                                            @foreach ($generatedPerSurveyResultRatings['rating'] as $key => $item)
                                                            @php
                                                                $avgPercentage  = $avgPercentage + ($item['percentage']/$item['respondents']);
                                                                $i++;
                                                            @endphp
                                                            <tr>
                                                                <th>
                                                                    {{$key}}
                                                                </th>
                                                                <td>
                                                                    {{$item['respondents']?$item['respondents']:''}}
                                                                </td>
                                                                <td>
                                                                    {{$item['points']?$item['points']:''}}
                                                                </td>
                                                                <td>
                                                                    {{$item['percentage']?round($item['percentage'],2).'%':''}}
                                                                    
                                                                </td>
                                                                <td>
                                                                   
                                                                    {{(round($item['percentage']/$item['respondents'],2)).'%'}}
                                                                  
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            <tr>
                                                                <th>
                                                                    {{'Total Average'}}
                                                                </th>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>
                                                                    {{round($avgPercentage/$i,2)}}%
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
                </div>
            @endforeach
        </div>
    </div>
   
@endsection

@section('scripts')
    <script>

    </script>
@endsection