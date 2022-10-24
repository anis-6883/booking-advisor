<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    /**
     * updateStatus
     *
     * Update Status of spacific row by the given table name and id
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request)
    {
        DB::table($request->table)->where('id', $request->id)->update(['status' => $request->status]);
        return response()->json(['result' => true]);
    }
}
