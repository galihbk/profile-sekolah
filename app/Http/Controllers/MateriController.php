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
        return view('materi.index');
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
        $materi = Materi::with('user'); // memuat relasi user

        return DataTables::of($materi)
            ->addIndexColumn()
            ->addColumn('pengajar', function ($row) {
                return $row->user ? $row->user->name : '-';
            })
            ->addColumn('file', function ($row) {
                return '<a href="' . asset('storage/' . $row->file) . '" target="_blank">Download</a>';
            })
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->translatedFormat('d F Y');
            })
            ->rawColumns(['file', 'action'])
            ->make(true);
    }
}
