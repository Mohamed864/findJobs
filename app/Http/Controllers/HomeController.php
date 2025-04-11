<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use Illuminate\View\View;


class HomeController extends Controller
{
    // @desc Show home page
    // @route GET /

    public function index() {

        $jobs = Job::latest()->limit(6)->get();


        return view('pages.index')->with('jobs',$jobs);
    }
}
