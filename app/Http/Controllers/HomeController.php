<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function wizard()
    {
        return view('home');
    }

    public function quickForm()
    {
        return view('home');
    }

    public function webProtege()
    {
        return view('home');
    }

    public function termsWiki()
    {
        return view('home');
    }

}
