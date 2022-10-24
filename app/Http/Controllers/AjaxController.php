<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    public function updateStatus(Request $request)
    {
        DB::table($request->table)->where('id', $request->id)->update(['status' => $request->status]);
        return response()->json(['result' => true]);
    }
}
