{{-- <!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Survey Manager</title>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/survey-manager/css/survey.css') }}" />
</head>
<body>

<div id="survey-manager">
</div>

<script>
    window.SurveyConfig = {!! json_encode(config('survey-manager')) !!};
</script>

<script src="{{ asset('vendor/survey-manager/js/survey-manager.js') }}"></script>

</body>
</html> --}}

@extends('layouts.admin')

@section('styles')
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/survey-manager/css/survey.css') }}" />
@endsection

@section('survey_manager_content')
<div style="width:140%; position:relative; left:-360px;">
    <div id="survey-manager">
    </div>
</div>
   
@endsection

@section('scripts')
@parent
    <script>
        window.SurveyConfig = {!! json_encode(config('survey-manager')) !!};
    </script>
    <script src="{{ asset('vendor/survey-manager/js/survey-manager.js') }}"></script>
@endsection
