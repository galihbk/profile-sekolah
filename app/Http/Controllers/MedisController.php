<?php

namespace App\Http\Controllers;

use App\Models\Diagnosa;
use App\Models\Medis;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class MedisController extends Controller
{
    public function index()
    {
        return view('medis.index');
    }
    public function history()
    {
        return view('medis.history');
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
            'gula_darah_tipe'  => ['nullable', 'in:puasa,jpp,sewaktu'],
            'gula_darah_mg_dl' => ['nullable', 'integer', 'min:20', 'max:800'],

            'kolesterol_mg_dl' => ['nullable', 'integer', 'min:50', 'max:1000'],
            'asam_urat_mg_dl'  => ['nullable', 'numeric', 'min:0', 'max:20'],

            'berat_kg'         => ['nullable', 'numeric', 'min:1', 'max:500'],
            'tinggi_cm'        => ['nullable', 'numeric', 'min:30', 'max:300'],

            'tensi_sistolik'   => ['nullable', 'integer', 'min:60', 'max:260'],
            'tensi_diastolik'  => ['nullable', 'integer', 'min:30', 'max:180', 'lte:tensi_sistolik'],

            'spo2'             => ['nullable', 'integer', 'min:50', 'max:100'],
        ]);

        $exists = Medis::where('user_id', $validated['user_id'])
            ->whereDate('tanggal_periksa', $validated['tanggal_periksa'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['tanggal_periksa' => 'Pasien sudah melakukan rekam medis pada tanggal ini.']);
        }
        if (!empty($validated['berat_kg']) && !empty($validated['tinggi_cm'])) {
            $m = (float) $validated['berat_kg'];
            $t = (float) $validated['tinggi_cm'] / 100;
            if ($t > 0) {
                $validated['imt'] = round($m / ($t * $t), 2);
            }
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
    public function historyData()
    {
        $medis = Medis::where('user_id', Auth::id())->orderBy('created_at', 'desc');

        return DataTables::of($medis)
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->translatedFormat('d F Y, H:i');
            })
            ->rawColumns(['created_at']) // jika ingin menampilkan HTML, tapi di sini optional
            ->make(true);
    }
}
