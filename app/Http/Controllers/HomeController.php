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

    public function index(Request $request, $term)
    {
        return view('home', ['term' => $term]);
    }

    public function wizard(Request $request, $term)
    {
        return view('home', ['term' => $term]);
    }

    public function quickForm(Request $request, $term)
    {
        return view('home', ['term' => $term]);
    }

    public function webProtege(Request $request, $term)
    {
        return view('home', ['term' => $term]);
    }

    public function termsWiki(Request $request, $term)
    {
        return view('home', ['term' => $term]);
    }

}
