<?php

namespace App\Http\Controllers;

use App\Models\Diagnosa;
use App\Models\Medis;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


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
        $q = Medis::query()
            ->with(['user', 'diagnosa'])
            ->latest();

        // 1) Batasi data untuk role=user
        if (auth()->user()->role === 'user') {
            $q->where('user_id', auth()->id());
        }

        return DataTables::of($q)
            ->addIndexColumn()
            ->addColumn('name', fn($r) => $r->user->name ?? '-')
            ->addColumn('diagnosa', fn($r) => optional($r->diagnosa)->diagnosa ?? '-')
            ->addColumn('umur', function ($r) {
                return $r->user?->tanggal_lahir
                    ? Carbon::parse($r->user->tanggal_lahir)->age . ' tahun'
                    : '-';
            })
            ->addColumn('jenis_kelamin', fn($r) => $r->user->jenis_kelamin ?? '-')

            // 2) Aksi: Edit/Hapus hanya admin, Print selalu ada
            ->addColumn('action', function ($r) {
                $btnPrint = '<a href="' . route('medis.print.preview', $r->id) . '" class="btn btn-sm btn-info">Print</a>';


                if (auth()->user()->role === 'admin') {
                    $btnEdit = '<a href="' . route('medis.edit', $r->id) . '" class="btn btn-sm btn-warning me-1">Edit</a>';
                    $btnDelete = '<form action="' . route('medis.destroy', $r->id) . '" method="POST" style="display:inline-block;margin-left:4px;">
            ' . csrf_field() . method_field('DELETE') . '
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Yakin ingin menghapus?\')">Hapus</button>
        </form>';
                    return $btnEdit . $btnDelete . ' ' . $btnPrint;
                }
                return $btnPrint;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit(Medis $medis)
    {
        abort_if(auth()->user()->role !== 'admin', 403);

        $medis->load(['user', 'diagnosa']); // agar akses relasi lancar
        $diagnosa = \App\Models\Diagnosa::select('id', 'diagnosa')->get();

        // data tampil untuk header form
        $ttl = trim(($medis->user->tempat_lahir ?? '') . ', ' . (
            $medis->user->tanggal_lahir
            ? Carbon::parse($medis->user->tanggal_lahir)->locale('id')->translatedFormat('d F Y')
            : ''
        ), ', ');

        $umur = $medis->user->tanggal_lahir
            ? Carbon::parse($medis->user->tanggal_lahir)->age . ' tahun'
            : '';

        return view('medis.edit', compact('medis', 'diagnosa', 'ttl', 'umur'));
    }
    public function update(Request $request, Medis $medis)
    {
        abort_if(auth()->user()->role !== 'admin', 403);

        $validated = $request->validate([
            'diagnosa_id'        => 'nullable|exists:diagnosas,id',
            'tanggal_periksa'    => 'required|date',
            'keluhan'            => 'nullable|string',
            'tambahan'           => 'nullable|string',
            'gula_darah_mg_dl'   => 'nullable|numeric',
            'gula_darah_tipe'    => 'nullable|in:puasa,jpp,sewaktu',
            'kolesterol_mg_dl'   => 'nullable|numeric',
            'asam_urat_mg_dl'    => 'nullable|numeric',
            'berat_kg'           => 'nullable|numeric',
            'tinggi_cm'          => 'nullable|numeric',
            'imt'                => 'nullable|numeric',
            'tensi_sistolik'     => 'nullable|numeric',
            'tensi_diastolik'    => 'nullable|numeric',
            'spo2'               => 'nullable|numeric',
        ]);

        $medis->update($validated);

        return redirect()->route('medis')->with('success', 'Rekam medis berhasil diupdate.');
    }
    public function destroy(Medis $medis)
    {
        abort_if(auth()->user()->role !== 'admin', 403);

        $medis->delete();

        return back()->with('success', 'Rekam medis berhasil dihapus.');
    }
    private function paperFrom(Request $request)
    {
        // A4 default; F4 (210x330mm) = 595.28 x 935.43 pt
        $paper = $request->get('paper', 'A4');
        if (strtoupper($paper) === 'F4') return [0, 0, 595.28, 935.43];
        return 'A4';
    }

    public function printPreview(Medis $medis, Request $request)
    {
        // admin boleh semua; user hanya miliknya
        $user = auth()->user();
        abort_if($user->role === 'user' && $medis->user_id !== $user->id, 403);

        $medis->load(['user', 'diagnosa']);
        $umur = $medis->user?->tanggal_lahir ? Carbon::parse($medis->user->tanggal_lahir)->age . ' tahun' : '-';
        $tanggal = $medis->tanggal_periksa ? Carbon::parse($medis->tanggal_periksa)->locale('id')->translatedFormat('d F Y') : '-';

        // Halaman HTML yang menampilkan iframe
        return view('medis.print-preview', compact('medis', 'umur', 'tanggal'));
    }

    public function printStream(Medis $medis, Request $request)
    {
        $user = auth()->user();
        abort_if($user->role === 'user' && $medis->user_id !== $user->id, 403);

        $medis->load(['user', 'diagnosa']);
        $umur = $medis->user?->tanggal_lahir ? Carbon::parse($medis->user->tanggal_lahir)->age . ' tahun' : '-';
        $tanggal = $medis->tanggal_periksa ? Carbon::parse($medis->tanggal_periksa)->locale('id')->translatedFormat('d F Y') : '-';

        $paper = $this->paperFrom($request);

        $pdf = Pdf::loadView('medis.pdf', compact('medis', 'umur', 'tanggal'))
            ->setPaper($paper, 'portrait');

        // stream inline utk <iframe>
        return $pdf->stream('rekam-medis.pdf');
    }

    public function printDownload(Medis $medis, Request $request)
    {
        $user = auth()->user();
        abort_if($user->role === 'user' && $medis->user_id !== $user->id, 403);

        $medis->load(['user', 'diagnosa']);
        $umur = $medis->user?->tanggal_lahir ? Carbon::parse($medis->user->tanggal_lahir)->age . ' tahun' : '-';
        $tanggal = $medis->tanggal_periksa ? Carbon::parse($medis->tanggal_periksa)->locale('id')->translatedFormat('d F Y') : '-';

        $paper = $this->paperFrom($request);

        $pdf = Pdf::loadView('medis.pdf', compact('medis', 'umur', 'tanggal'))
            ->setPaper($paper, 'portrait');

        $filename = 'Rekam_Medis_' . $medis->user?->name . '_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }
    public function autocomplete(Request $request)
    {
        $query = $request->get('query');

        $users = User::where('name', 'like', "%{$query}%")->where('role', 'user')->get();

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
