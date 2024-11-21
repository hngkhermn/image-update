<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;

class indexController extends Controller
{
    public function index() {
        $files = Upload::all();
        return view('index', compact('files'));
    }
}
