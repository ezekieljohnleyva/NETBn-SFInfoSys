@if(request()->is("admin/esurvey") )
    @can($viewGate)
        <a class="btn btn-xs btn-success" href="{{ route('admin.' . $crudRoutePart . '.launch', $row->id) }}">
            {{ trans('Launch') }}
        </a>
    @endcan

    @can($viewGate)
    <a class="btn btn-xs btn-info" href="{{ route('admin.esurvey.result.list', $row->id) }}">
        {{ trans('Results') }}
    </a>

    
    @endcan
@endif

    @can($viewGate)
        <!-- <a class="btn btn-xs btn-primary" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}"> -->
        
            @if (request()->is("admin/esurvey"))
            <a class="btn btn-xs btn-primary" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}">
                {{'Show Survey'}}
                </a>
            @else
            <a class="btn btn-xs btn-primary" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}">
                {{ trans('global.view') }}
                </a>  
            <a class="btn btn-xs btn-success" href="{{ route('admin.' . $crudRoutePart . '.showPDF', $row->id) }}">
                {{ trans('global.generatepdf') }}
                </a>  
            @endif
        <!-- </a> -->

        <!-- <a class="btn btn-xs btn-info" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}">
        
            @if (request()->is("admin/esurvey"))
                {{'Show Survey'}}
            @else
                {{ trans('global.generatepdf') }}
            @endif
        </a> -->


        
    @endcan
    @can($editGate)
        <a class="btn btn-xs btn-info" href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @if(request()->is("admin/esurvey") )
        @can($viewGate)
            <a class="btn btn-xs btn-primary" href="{{ route('admin.' . $crudRoutePart . '.duplicate', $row->id) }}">
                {{ trans('Duplicate') }}
            </a>
        @endcan
    @endif
    @can($deleteGate)
        <form action="{{ route('admin.' . $crudRoutePart . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
