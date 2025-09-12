<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MateriController extends Controller
{
    public function index()
    {
        return view('materi.materi-user');
    }
    public function materiUser()
    {
        return view('materi.materi-user');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf|max:10240',
        ]);

        $file = $request->file('file');

        $extension = $file->getClientOriginalExtension();
        $slug = Str::slug($request->name);
        $newFileName = 'materi-' . $slug . '-' . time() . '.' . $extension;

        $filePath = $file->storeAs('materi', $newFileName, 'public');
        Materi::create([
            'name' => $request->name,
            'file' => $filePath,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil ditambahkan'
        ]);
    }
    public function data()
    {
        $materi = Materi::where('user_id', auth()->id());

        return DataTables::of($materi)
            ->addIndexColumn()
            ->addColumn('file', function ($row) {
                return '<a href="' . asset('storage/' . $row->file) . '" target="_blank">Download</a>';
            })
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->translatedFormat('d F Y');
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-danger btn-sm">Hapus</button>';
            })
            ->rawColumns(['file', 'action'])
            ->make(true);
    }
    public function dataMateri()
    {
        $user = auth()->user();

        $materi = Materi::with('user')
            ->when(
                $user->role === 'pengajar',
                fn($q) => $q->where('user_id', $user->id) // pengajar: hanya materi miliknya
            ); // non-pengajar: lihat semua

        return DataTables::of($materi)
            ->addIndexColumn()
            ->addColumn('pengajar', fn($row) => $row->user?->name ?? '-') // existing
            ->addColumn('uploader', fn($row) => $row->user?->name ?? '-') // kolom tambahan
            ->addColumn('file', function ($row) {
                $url = $row->file ? asset('storage/' . ltrim($row->file, '/')) : '#';
                return $row->file
                    ? '<a href="' . e($url) . '" target="_blank" rel="noopener">Download</a>'
                    : '-';
            })
            ->editColumn('created_at', fn($row) => Carbon::parse($row->created_at)->translatedFormat('d F Y'))
            ->addColumn('action', function ($row) use ($user) {
                // Tampilkan aksi hanya jika role pengajar DAN pemilik data
                if ($user->role === 'pengajar' && (int)$row->user_id === (int)$user->id) {
                    $btnEdit = '<a href="' . route('materi.edit', $row->id) . '" class="btn btn-sm btn-warning me-1">Edit</a>';
                    $btnDelete = '<form action="' . route('materi.destroy', $row->id) . '" method="POST" style="display:inline-block">'
                        . csrf_field() . method_field('DELETE') .
                        '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Yakin menghapus materi ini?\')">Hapus</button>
                    </form>';
                    return $btnEdit . $btnDelete;
                }
                return ''; // kosong untuk non-pengajar / bukan pemilik
            })
            ->rawColumns(['file', 'action'])
            ->make(true);
    }
}
