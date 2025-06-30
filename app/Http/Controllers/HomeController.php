<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Berita;
class HomeController extends Controller
{
    public function index()
{
    $beritaTerbaru = Berita::orderBy('created_at', 'desc')->take(3)->get();
    return view('home.index', compact('beritaTerbaru'));
}
    public function about()
    {
        return view('home.about');
    }
    public function berita()
    {
        $beritaTerbaru = Berita::orderBy('created_at', 'desc')->get();
        return view('home.berita',compact('beritaTerbaru'));
    }
    public function contact()
    {
        return view('home.contact');
    }
    public function gallery()
    {
        return view('home.gallery');
    }
}
