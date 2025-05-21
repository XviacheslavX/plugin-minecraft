<?php

namespace Azuriom\Plugin\Centralcorp\Controllers;

use Azuriom\Http\Controllers\Controller;

class CentralcorpHomeController extends Controller
{
    /**
     * Show the home plugin page.
     */
    public function index()
    {
        return view('centralcorp::index');
    }
}
