<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
    * Listado de ordenes generadas
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $orders = Orders::orderBy('id','desc')->paginate(5);
        return view('orders.index', compact('orders'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('orders.create');
    }
}
