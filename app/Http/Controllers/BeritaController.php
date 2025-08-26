<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index()
    {
        return view('berita.index');
    }
    public function store(Request $request)
    {
         $request->validate([
            'judul' => 'required|string',
            'image' => 'required|image',
            'deskripsi' => 'required|string'
        ]);
        $file = $request->file('image');

        $extension = $file->getClientOriginalExtension();
        $slug = Str::slug($request->name);
        $newFileName = 'image-' . $slug . '-' . time() . '.' . $extension;

        $filePath = $file->storeAs('image', $newFileName, 'public');
        Berita::create([
            'judul' => $request->judul,
            'image' => $newFileName,
            'description' => $request->deskripsi,
        ]);

        return response()->json(['message' => 'Berita berhasil ditambahkan']);
    }
    public function data()
{
    $data = Berita::get();

    return datatables()->of($data)
        ->addIndexColumn()
        ->addColumn('judul', function ($row) {
            $url = route('berita.show', $row->id);
            return '<a href="' . $url . '" traget="_blank"><strong>' . e($row->judul) . '</strong></a>';
        })
        ->addColumn('created_at', function ($row) {
            return $row->created_at->format('d-m-Y'); // 👈 format tgl di sini
        })
        ->addColumn('action', function ($row) {
            $url = route('berita.show', $row->id);
            return '
                <a href="' . $url . '" class="btn btn-sm btn-warning" traget="_blank" data-id="'.$row->id.'">Detail</a>
                <form action="' . route('berita.destroy', $row->id) . '" method="POST" style="display:inline-block;">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Yakin ingin menghapus?\')">Hapus</button>
                </form>
            ';
        })
        ->rawColumns(['judul', 'action'])
        ->make(true);
}


public function destroy(Berita $berita)
{
    if ($berita->image && Storage::disk('public')->exists('image/' . $berita->image)) {
        Storage::disk('public')->delete('image/' . $berita->image);
    }

    $berita->delete();

    return redirect()->back()->with('success', 'Berita berhasil dihapus');
}
    public function update(Request $request, Medis $medis)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'diagnosa_id' => 'nullable|exists:diagnosas,id',
            'tanggal_periksa' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'umur' => 'required|integer|min:0',
            'keluhan' => 'required|string',
            'tambahan' => 'nullable|string',
        ]);

        $medis->update($validated);

        return redirect()->route('medis.index')->with('success', 'Rekam medis berhasil diperbarui.');
    }
public function show($id)
{
    $beritaTerbaru = Berita::orderBy('created_at', 'desc')->get();
    $berita = Berita::findOrFail($id);
    return view('berita.detail', compact('berita', 'beritaTerbaru'));
}
public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        return response()->json($berita);
    }
}
