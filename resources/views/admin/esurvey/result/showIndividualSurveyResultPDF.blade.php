<!DOCTYPE html>
<html>
<head>
    <title>Service Feedback Form</title>
</head>

<body>
<header id="header">
   <center><p id="font1">By 2028, a world-class Army that is a source of national pride.</p></center>
</header>
<footer id="footer">
      <img  id="imgfooter" src="{{ public_path('storage/img/footer.jpg') }}"  />

      <center id="txtfooter">
         <span>Honor. Patriotism. Duty.</span>
      </center>
</footer>


<!-- 
<body> -->
<!-- 

<div class="card">
    <div class="card-header">
        {{ trans('Individual Survey Result') }}
    </div>
    {{-- {{dd($rating['generatedPerSurveyResultRatings'])}} --}}
    <div class="card-body">
        <div class="form-group"> -->
            <!-- <div class="form-group"> -->
                <!-- <a class="btn btn-default" href="{{ URL::previous()}}">
                    {{ trans('global.back_to_list') }}
                </a> -->
            <!-- </div> -->
            <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;">
            
            
            <tr> 
            <td rowspan = "4" style="text-align:center;"><img src="https://i.ibb.co/vHM5DLH/army.png" width="65" height="65" /></td>
            <td rowspan = "4" style="text-align:center; text-decoration:underline; font-weight:bold">LEADERSHIP AND MANAGEMENT, MORALE AND WELFARE AND DISCIPLINE, LAW AND ORDER SURVEY QUESTIONNAIRE</td>
            <td rowspan = "4" style="text-align:center;"><img src="https://i.ibb.co/bPSHXbK/otig.png" width="65" height="65" /></td>
        
            </tr>
            </table>
            <br>
       
            <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;">
            
          
            </tr>
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
                                    <span>{{$result}}</span>
                                @endif
                            </td>
                           
                            
                        </tr>
                        
                    @endforeach 
                </tbody>
            </table>
</body>
<!-- </body> -->
</html>

<style type="text/css">
   #font1 {
      font-style: italic;
     
   }
   p.solid {border-style: solid;}

   #header {
      position: fixed;
      top: -30;
      width: 100%;
      height: 100px;
   }

   #footer {
      position: fixed;
      bottom: -150;
      width: 100%;
      height: 50px;
   }

   .left{         
      text-align:left;
      float:left;
   }
   .right{
      float:right;
      text-align:right;
   }
   .centered{
      text-align:center;
      font-style: italic;
      font-family: arial;
   }
   #imgfooter{
      position: fixed;
      bottom:5;
      width:130;
   }
   #txtfooter{
      position: fixed;
      bottom:100;
      font-style: italic
   }
   body{
      margin-top:20px;
      font-family: Arial, Helvetica, sans-serif;
      font-size: 13.1px;
   }     

</style>
