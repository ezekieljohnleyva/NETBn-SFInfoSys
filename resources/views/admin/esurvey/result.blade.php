@extends('layouts.admin')
@section('content')

<div class="card">
  <div class="card-header">
    <div class="row">
        <div class="col-md-2">
            {{$surveyName??''}} Survey Results
        </div>

        <div class="d-flex col-md-10 "> 
            <button type="button" class="btn btn-outline-primary">
                <span>5 - Outstanding (96% - 100%)</span>&nbsp;&nbsp;&nbsp;|
                <span>4 - Very Satisfactory (88% - 94%)</span>&nbsp;&nbsp;&nbsp;|
                <span>3 - Satisfactory (81% - 87%)</span>&nbsp;&nbsp;&nbsp;|
                <span>2 - Marginal (71% - 80%)</span>&nbsp;&nbsp;&nbsp;|
                <span>1 - Unsatisfactory (0% - 70%)</span> &nbsp;&nbsp;&nbsp;
            </button>
        </div>
   </div>
  </div>

    <div class="card-body">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{request()->is("admin/esurvey/$id/result/list")?'active':'' }}" href="{{ route('admin.esurvey.result.list',$id) }}" style="text-decoration: none !important; color:black">Individual Survey Result</a>
            </li>
            <li class="nav-item">
                <form method="post" action="{{ route('admin.esurvey.result.unit',$id) }}">
                    @csrf
                    <a  class ="nav-link {{request()->is("admin/esurvey/$id/result/unit")?'active':'' }}" onclick="this.parentNode.submit();">Unit Survey Result</a>
                </form>
            </li>   
            <li class="nav-item">
                <a class="nav-link {{request()->is("admin/esurvey/$id/result/unit-pdf")?'active':'' }}" href="{{ route('admin.esurvey.result.unitSearch',$id) }}" style="text-decoration: none !important; color:black">Generate Survey Report</a>
            </li> 
        </ul>
        <br>
            @include('admin.esurvey.result.individualResultList')
            
    </div>
</div>

@endsection