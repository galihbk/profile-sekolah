<?php

namespace App\Http\Controllers;

use App\Models\Diagnosa;
use App\Models\Medis;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MedisController extends Controller
{
    public function index()
    {
        return view('medis.index');
    }
    public function create()
    {
        $diagnosa = Diagnosa::all();
        return view('medis.create', compact('diagnosa'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'diagnosa_id' => 'nullable|exists:diagnosas,id',
            'tanggal_periksa' => 'required|date',
            'keluhan' => 'required|string',
            'tambahan' => 'nullable|string',
        ]);

        $exists = Medis::where('user_id', $validated['user_id'])
            ->whereDate('tanggal_periksa', $validated['tanggal_periksa'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['tanggal_periksa' => 'Pasien sudah melakukan rekam medis pada tanggal ini.']);
        }

        Medis::create($validated);

        return redirect()->route('medis')->with('success', 'Rekam medis berhasil ditambahkan.');
    }
    public function data()
    {
        $data = Medis::with('user', 'diagnosa')->latest();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('name', fn($row) => $row->user->name)
            ->addColumn('diagnosa', fn($row) => optional($row->diagnosa)->diagnosa ?? '-')
            ->addColumn('umur', function ($row) {
                if ($row->user->tanggal_lahir) {
                    return \Carbon\Carbon::parse($row->user->tanggal_lahir)->age . ' tahun';
                }
                return '-';
            })
            ->addColumn('jenis_kelamin', function ($row) {
                return $row->user->jenis_kelamin ?? '-';
            })
            ->addColumn('action', function ($row) {
                return '
                <a href="' . route('users.edit', $row->id) . '" class="btn btn-sm btn-warning">Edit</a>
                <form action="' . route('users.destroy', $row->id) . '" method="POST" style="display:inline-block;">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Yakin ingin menghapus?\')">Hapus</button>
                </form>
            ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function destroy(Medis $medis)
    {
        $medis->delete();

        return response()->json(['success' => true, 'message' => 'Rekam medis berhasil dihapus']);
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
    public function autocomplete(Request $request)
    {
        $query = $request->get('query');

        $users = User::where('name', 'like', "%{$query}%")->get();

        return response()->json($users);
    }
}
