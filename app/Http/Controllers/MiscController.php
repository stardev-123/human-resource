<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MiscController extends Controller
{

    public function tnc(){
        if(!config('config.enable_tnc'))
            return redirect('/');

        return view('tnc');
    }

    public function maintenance(){
        if(!config('config.maintenance_mode'))
            return redirect('/');

        return view('global.maintenance');
    }
}