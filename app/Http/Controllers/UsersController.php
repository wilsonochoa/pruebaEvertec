<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
   * Vista de productos a comprar
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('users.index');
  }

}
