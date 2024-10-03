<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccMast;
use Illuminate\Http\Request;

class CustomersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
//        dd(request()->cust);
//        dd($request->input('cust'));
        $customer = request()->cust;

//        dd($this->cust);
        $customers = AccMast::where('Type', '10')
            ->where('Arabic_name', 'like', '%'.$customer.'%')
//            ->select('Code', 'Arabic_Name')
            ->select('Arabic_Name')
            ->get();//->toJson();

//        dd(response()->json($customers));

        return response()->json($customers);
//        return response()->json($this->cust);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
