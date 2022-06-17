<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function index(Request $request)
    {
        return view('api-client');
    }
}
