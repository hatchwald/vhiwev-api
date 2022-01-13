<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function tester(Request $request)
    {
        $data = ['code' => '200', 'message' => 'just testing'];
        return response()->json($data);
    }
}
