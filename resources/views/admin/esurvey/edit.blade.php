@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('Survey') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.esurvey.update", [$survey->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="required" for="name">{{ trans('cruds.taskTag.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $survey->name) }}" required>
                    @if($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.taskTag.fields.name_helper') }}</span>
                </div>
                <div class="form-group col-md-2">
                    <label class="" for="pamu">{{ trans('Pamu') }}</label>
                    <select class="form-control select2 {{ $errors->has('pamu') ? 'is-invalid' : '' }}" name="pamu" id="pamu">
                        @foreach($pamus as $id => $entry)
                            <option value="{{ $id }}" {{ (old('pamu') ? old('pamu') : $survey->pamu ?? '') == $entry ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('status'))
                        <span class="text-danger">{{ $errors->first('pamu') }}</span>
                    @endif
                </div>
                <div class="form-group col-md-2">
                    <label class="required" for="task_status_id">{{ trans('Status') }}</label>
                    <select class="form-control select2 {{ $errors->has('status') ? 'is-invalid' : '' }}" name="task_status_id" id="task_status_id" required>
                        @foreach($statuses as $id => $entry)
                            <option value="{{ $id }}" {{ (old('tatsk_status_id') ? old('tatsk_status_id') : $survey->task_status->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('status'))
                        <span class="text-danger">{{ $errors->first('status') }}</span>
                    @endif
                </div>
            </div>
            
            
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection