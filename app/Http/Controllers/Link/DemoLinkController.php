<?php

namespace App\Http\Controllers\Link;

use App\Http\Controllers\Controller;
/**
 * Created by PhpStorm.
 * User: SYMB
 * Date: 5/30/2016
 * Time: 5:59 PM
 */
class DemoLinkController extends controller
{
    public function newLeadDemo() {
        return view('admin.demo');
    }
}