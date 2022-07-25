<?php

namespace App\Api\V1\Controllers\CP;

use App\Api\V1\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Model\Table\Table;

class TableController extends ApiController {

    function create(Request $request){

        $this->validate($request,[
            "name"  => 'required'
        ],[
            'name.required'=> 'Please input table name'
        ]);

        $table = new Table();
        $table->name = $request->name;
        $table->save();

        return response()->json([
            'status'    => 200,
            'message'   => "Table was created successfully"
        ]);

    }

    function listing(){

        $data = Table::select('*')->get();

        return response()->json([
            'status'    => 'success',
            'data'      => $data
        ]);
    }

}