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
                 <a class="nav-link {{request()->is("admin/esurvey/$id/result/list")?'active':'' }}" href="{{ route('admin.esurvey.result.list',$id) }}" style="text-decoration: none !important;color:black">Individual Survey Result</a>
             </li>
             <li class="nav-item">
                 <form method="post" action="{{ route('admin.esurvey.result.unit',$id) }}">
                     @csrf
                     <a  class ="nav-link {{request()->is("admin/esurvey/$id/result/unit")?'active':'' }}" onclick="this.parentNode.submit();">Unit Survey Result</a>
                 </form>
             </li> 
             <li class="nav-item">
                 <a class="nav-link {{request()->is("admin/esurvey/$id/result/unit-search")?'active':'' }}" href="{{ route('admin.esurvey.result.unitSearch',$id) }}" style="text-decoration: none !important;color:black">Generate Survey Report</a>
             </li> 
         
         </ul>
         <br>
         
     
            <form action="{{ route("admin.esurvey.result.unitPDF",$id) }}"  enctype="multipart/form-data"  method="GET">
                    
                <div class="row">

                    <div class=" col-md-2">
                        <label for="unit"> <i class="fas fa-fw fa-object-group nav-icon"></i> PAMU</label>
                        <select class="form-control select2 {{ $errors->has('pamu') ? 'is-invalid' : '' }}" name="pamu" id="pamu" onchange="getUnits(this)" required>
                            @foreach ($filters['pamus'] as $key => $item)
                                <option value="{{$item}}" {{$item==$filters['pamu']?'selected':''}}>{{$item}}</option>
                            @endforeach
                        </select>
                    </div> 

                    <div class=" col-md-2">
                        <label for="unit"> <i class="fas fa-fw fa-object-group nav-icon"></i> Unit</label>
                        <select class="form-control select2 {{ $errors->has('unit') ? 'is-invalid' : '' }}" name="unit" id="unit" required>
                            @foreach ($filters['units'] as $key => $item)
                                <option value="{{$item}}" {{$item==$filters['unit']?'selected':''}}>{{$item}}</option>
                            @endforeach
                        </select>
                    </div>    
                    <div class=" col-md-2">
                        <label for="employeee_type"><i class="fas fa-fw fa-user nav-icon"></i> Employee Type</label>
                        <select class="form-control select2 {{ $errors->has('employee_type') ? 'is-invalid' : '' }}" name="employee_type" id="employee_type" required>
                            @foreach ($filters['employee_types'] as $key => $item)
                                <option value="{{$item}}" {{$item==$filters['employee_type']?'selected':''}}>{{$item}}</option>
                            @endforeach
                        </select>
                    </div> 
                    <div class=" col-md-2">
                        <label for="excludedQuestions"><i class="fas fa-fw fa-question nav-icon"></i> Exclude Elements</label>
                        <select class="form-control select2 {{ $errors->has('excludedQuestions') ? 'is-invalid' : '' }}" name="excludedQuestions[]" multiple id="excludedQuestions">
                            @foreach ($filters['questions'] as $key => $item)
                                <option value="{{$item}}" {{in_array($item, $filters['excludedQuestions'])?'selected':''}}>{{$item}}</option>
                            @endforeach
                        </select>
                    </div> 
                    <div class="col-sm-2">
                        <label for="">&nbsp;</label>
                        <button class=" form-control btn btn-outline-primary " type="submit">
                            Filter
                            <i class="fas fa-fw fa-sort nav-icon"></i>
                        </button>
                    </div>
                </div>
            </form>
            <br>

            
    </div>

</div>


@endsection

@section('scripts')
<script>

    document.addEventListener("DOMContentLoaded", function(event) { 
        var scrollpos = localStorage.getItem('scrollpos');
        if (scrollpos) window.scrollTo(0, scrollpos);
    });

    window.onbeforeunload = function(e) {
        localStorage.setItem('scrollpos', window.scrollY);
    };

    function getUnits(data){
        axios({
            method: 'get',
            headers: {
                
            },
            url: "{{ route('admin.unit.lists') }}",
            params: {
                pamu: data.value,
            }
        })
        .then(function (response) {
            
            $('#unit').empty();
            var data = response.data;
        
            data.forEach(function(item) {
                $('#unit').append($('<option>', {
                    value: item['UnitCode'],
                    text: item['UnitCode']
                }));
            });
        }).catch(function (error) {
            console.log(error);
        });
    }


</script>   
@endsection