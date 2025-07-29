<?php
namespace App\Http\Controllers;
use Illuminate\View\View;

class JadwalController extends Controller
{
    public function index(): View
    {
        // $dosen = Dosen::all();
        return view('jadwal.index'); // , compact('dosen')
    }
}
