<?php

namespace App\Http\Controllers\Link;

use App\Http\Controllers\Controller;

class FrontLinkController extends Controller
{
    public function get()
    {
        return '';
    }
    public function contact()
    {
        return view('contact');
    }
    public function careFinder()
    {
        return view('front.carefinder');
    }
}