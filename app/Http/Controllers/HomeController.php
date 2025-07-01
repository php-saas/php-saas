<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('index');
    }

    public function privacy(): View
    {
        return view('privacy', [
            'title' => 'Privacy Policy',
        ]);
    }

    public function terms(): View
    {
        return view('terms', [
            'title' => 'Terms of Use',
        ]);
    }

    public function contact(): View
    {
        return view('contact', [
            'title' => 'Contact Us',
        ]);
    }
}
