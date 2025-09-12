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
        $diagnosa = Diagnosa::select('id', 'diagnosa')->orderBy('diagnosa')->get();
        return view('medis.create', compact('diagnosa'));
    }

    // ==== UTIL NORMALISASI ====
    private function norm($v)
    {
        if ($v === null) return null;
        $v = trim((string)$v);
        return ($v === '' || $v === '-') ? null : $v;
    }

    private function num($v)
    {
        if ($v === null) return null;
        $v = trim((string)$v);
        if ($v === '' || $v === '-') return null;
        $v = str_replace(',', '.', $v);
        return is_numeric($v) ? 0 + $v : null;
    }

    public function store(Request $request)
    {
        // Required minimum untuk constraint DB
        $userId = (int) $request->input('user_id');
        $tanggal = $request->input('tanggal_periksa');

        try {
            $tanggal = $tanggal ? Carbon::parse($tanggal)->format('Y-m-d') : now()->format('Y-m-d');
        } catch (\Exception $e) {
            $tanggal = now()->format('Y-m-d');
        }

        $keluhan = $this->norm($request->input('keluhan')) ?? '-';

        // Cegah duplikat tanggal untuk user yang sama (opsional)
        $exists = Medis::where('user_id', $userId)
            ->whereDate('tanggal_periksa', $tanggal)
            ->exists();
        if ($exists) {
            return back()->withInput()
                ->withErrors(['tanggal_periksa' => 'Pasien sudah melakukan rekam medis pada tanggal ini.']);
        }

        $data = [
            'user_id'           => $userId,
            'diagnosa_id'       => $this->norm($request->input('diagnosa_id')),
            'tanggal_periksa'   => $tanggal,
            'keluhan'           => $keluhan,
            'tambahan'          => $this->norm($request->input('tambahan')),

            'gula_darah_tipe'   => $this->norm($request->input('gula_darah_tipe')),
            'gula_darah_mg_dl'  => $this->num($request->input('gula_darah_mg_dl')),
            'kolesterol_mg_dl'  => $this->num($request->input('kolesterol_mg_dl')),
            'asam_urat_mg_dl'   => $this->num($request->input('asam_urat_mg_dl')),
            'berat_kg'          => $this->num($request->input('berat_kg')),
            'tinggi_cm'         => $this->num($request->input('tinggi_cm')),
            'tensi_sistolik'    => $this->num($request->input('tensi_sistolik')),
            'tensi_diastolik'   => $this->num($request->input('tensi_diastolik')),
            'spo2'              => $this->num($request->input('spo2')),
        ];

        // Hitung IMT jika valid
        if (!is_null($data['berat_kg']) && !is_null($data['tinggi_cm']) && $data['tinggi_cm'] > 0) {
            $t = $data['tinggi_cm'] / 100;
            $data['imt'] = round($data['berat_kg'] / ($t * $t), 2);
        } else {
            $data['imt'] = null;
        }

        Medis::create($data);

        return redirect()->route('medis')->with('success', 'Rekam medis berhasil ditambahkan.');
    }

    public function data()
    {
        $q = Medis::query()->with(['user', 'diagnosa'])->latest();

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
            ->editColumn('tanggal_periksa', function ($r) {
                return $r->tanggal_periksa
                    ? Carbon::parse($r->tanggal_periksa)->translatedFormat('d F Y')
                    : '-';
            })
            ->addColumn('action', function ($r) {
                $btnPrint = '<a href="' . route('medis.print.preview', $r->id) . '" class="btn btn-sm btn-info">Print</a>';

                if (auth()->user()->role === 'admin') {
                    $btnEdit = '<a href="' . route('medis.edit', $r->id) . '" class="btn btn-sm btn-warning me-1">Edit</a>';
                    $btnDelete = '<form action="' . route('medis.destroy', $r->id) . '" method="POST" style="display:inline-block;margin-left:4px;">'
                        . csrf_field() . method_field('DELETE') . '
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

        $medis->load(['user', 'diagnosa']);
        $diagnosa = Diagnosa::select('id', 'diagnosa')->orderBy('diagnosa')->get();

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

        $tanggal = $request->input('tanggal_periksa');
        try {
            $tanggal = $tanggal ? Carbon::parse($tanggal)->format('Y-m-d') : $medis->tanggal_periksa;
        } catch (\Exception $e) {
            $tanggal = $medis->tanggal_periksa;
        }

        $data = [
            'diagnosa_id'       => $this->norm($request->input('diagnosa_id')),
            'tanggal_periksa'   => $tanggal,
            'keluhan'           => $this->norm($request->input('keluhan')) ?? ($medis->keluhan ?? '-'),
            'tambahan'          => $this->norm($request->input('tambahan')),

            'gula_darah_tipe'   => $this->norm($request->input('gula_darah_tipe')),
            'gula_darah_mg_dl'  => $this->num($request->input('gula_darah_mg_dl')),
            'kolesterol_mg_dl'  => $this->num($request->input('kolesterol_mg_dl')),
            'asam_urat_mg_dl'   => $this->num($request->input('asam_urat_mg_dl')),
            'berat_kg'          => $this->num($request->input('berat_kg')),
            'tinggi_cm'         => $this->num($request->input('tinggi_cm')),
            'tensi_sistolik'    => $this->num($request->input('tensi_sistolik')),
            'tensi_diastolik'   => $this->num($request->input('tensi_diastolik')),
            'spo2'              => $this->num($request->input('spo2')),
        ];

        if (!is_null($data['berat_kg']) && !is_null($data['tinggi_cm']) && $data['tinggi_cm'] > 0) {
            $t = $data['tinggi_cm'] / 100;
            $data['imt'] = round($data['berat_kg'] / ($t * $t), 2);
        } else {
            $data['imt'] = null;
        }

        $medis->update($data);

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
        $paper = $request->get('paper', 'A4');
        if (strtoupper($paper) === 'F4') return [0, 0, 595.28, 935.43];
        return 'A4';
    }

    public function printPreview(Medis $medis, Request $request)
    {
        $user = auth()->user();
        abort_if($user->role === 'user' && $medis->user_id !== $user->id, 403);

        $medis->load(['user', 'diagnosa']);
        $umur = $medis->user?->tanggal_lahir ? Carbon::parse($medis->user->tanggal_lahir)->age . ' tahun' : '-';
        $tanggal = $medis->tanggal_periksa ? Carbon::parse($medis->tanggal_periksa)->locale('id')->translatedFormat('d F Y') : '-';

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

        $filename = 'Rekam_Medis_' . ($medis->user?->name ?? 'Pasien') . '_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('query');
        $users = User::where('name', 'like', "%{$query}%")
            ->where('role', 'user')
            ->select('id', 'name', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin')
            ->limit(20)
            ->get();

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
            ->rawColumns(['created_at'])
            ->make(true);
    }
}
