<?php

namespace App\Http\Controllers;

use App\Models\Diagnosa;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DiagnosaController extends Controller
{
    public function index()
    {
        return view('diagnosa.index');
    }
    public function store(Request $request)
    {
        $request->validate([
            'diagnosa' => 'required|string|max:255',
            'anjuran' => 'required|string',
            'pantangan' => 'required|string',
        ]);

        Diagnosa::create([
            'diagnosa' => $request->diagnosa,
            'anjuran' => $request->anjuran,
            'pantangan' => $request->pantangan,
        ]);

        return redirect()->back()->with('success', 'Diagnosa berhasil ditambahkan.');
    }
    public function data()
    {
        $data = Diagnosa::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="#" class="btn btn-sm btn-warning">Edit</a> 
                        <a href="#" class="btn btn-sm btn-danger">Hapus</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
