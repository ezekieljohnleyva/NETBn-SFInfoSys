<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Audit;

use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Audit::with('creator')->latest()->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($data){
                $button = '<button type="button"
                name="view" id="'.$data->id.'" class="view btn btn-success btn-sm">View</button>';
               
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        // $data = Audit::latest()->get();
        // dd($data);
       
        $auditData = Audit::latest()->get();

        // dd($auditData);

        return view('admin.audit.index', ['auditData' => $auditData]);
      

    }
    public function show($id)
    {
        if(request()->ajax())
        {
            $data = Audit::findOrFail($id);
            return response()->json(['result' => $data]);
    }
    }
}
