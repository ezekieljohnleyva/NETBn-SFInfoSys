<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pamu;
use App\Models\Unit;

class PamuController extends Controller
{
    public function pamuList(Request $request){

        return Pamu::orderBy('pamu')->get('pamu')->toArray();
    }
}
