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
                return '
        <button type="button" 
            class="btn btn-sm btn-warning btn-edit" 
            data-id="' . $row->id . '" 
            data-diagnosa="' . $row->diagnosa . '" 
            data-anjuran="' . $row->anjuran . '" 
            data-pantangan="' . $row->pantangan . '">
            Edit
        </button>
        <button type="button" data-id="' . $row->id . '" class="btn btn-sm btn-danger btn-hapus">Hapus</button>
    ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function destroy($id)
    {
        $diagnosa = Diagnosa::findOrFail($id);
        $diagnosa->delete();

        return response()->json(['success' => 'Diagnosa berhasil dihapus.']);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'diagnosa' => 'required|string|max:255',
            'anjuran'  => 'required|string',
            'pantangan' => 'required|string',
        ]);

        $diag = Diagnosa::findOrFail($id);
        $diag->update($request->only('diagnosa', 'anjuran', 'pantangan'));

        return redirect()->route('diagnosa')->with('success', 'Diagnosa berhasil diupdate.');
    }
}
