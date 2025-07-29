<?php

namespace App\Http\Controllers;

class ForgotController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-pass');
    }
}
