<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }
    public function create()
    {
        return view('user.create');
    }
    public function pengajar()
    {
        return view('user.pengajar');
    }
    public function createPengajar()
    {
        return view('user.create-pengajar');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullName'         => 'required|string|max:255',
            'placeOfBirth'     => 'required|string|max:255',
            'dateOfBirth'      => 'required|date',
            'gender'           => 'required|in:Laki-laki,Perempuan',
            'email'            => 'required|email',
            'username'            => 'required|unique:users,username',
            'password'         => 'required|min:6|confirmed',
            'address'          => 'required|string',
        ]);
        User::create([
            'username'     => $validated['username'],
            'name'     => $validated['fullName'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
            'tempat_lahir' => $validated['placeOfBirth'],
            'tanggal_lahir' => $validated['dateOfBirth'],
            'jenis_kelamin' => $validated['gender'],
            'alamat' => $validated['address'],
        ]);
        return redirect()->route('users.index')->with('success', 'Data lansia berhasil ditambahkan');
    }
    public function storePengajar(Request $request)
    {
        $validated = $request->validate([
            'fullName'         => 'required|string|max:255',
            'gender'           => 'required|in:Laki-laki,Perempuan',
            'email'            => 'required|email',
            'username'            => 'required|unique:users,username',
            'password'         => 'required|min:6|confirmed',
            'address'          => 'required|string',
        ]);
        User::create([
            'name'     => $validated['fullName'],
            'username'     => $validated['username'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
            'jenis_kelamin' => $validated['gender'],
            'alamat' => $validated['address'],
            'role' => 'pengajar',
        ]);
        return redirect()->route('users.pengajar')->with('success', 'Pengajar berhasil ditambahkan');
    }
    public function data(Request $request)
    {
        if ($request->ajax()) {
            $query = User::query()->where('role', 'user'); // <- query(), bukan get()
            return DataTables::of($query)
                ->addIndexColumn()
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
    }
    public function edit(User $user)
    {
        // Pastikan ini bukan pengajar
        abort_if($user->role !== 'user', 404);
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->role !== 'user', 404);

        // Normalisasi supaya konsisten
        $request->merge([
            'email'    => strtolower(trim($request->email)),
            'username' => trim($request->username),
        ]);

        // NOTE: email TIDAK unique (hanya format)
        $validated = $request->validate([
            'fullName'     => 'required|string|max:255',
            'placeOfBirth' => 'required|string|max:255',
            'dateOfBirth'  => 'required|date',
            'gender'       => 'required|in:Laki-laki,Perempuan',
            'email'        => 'required|email', // <- tidak pakai unique
            'username'     => ['required', \Illuminate\Validation\Rule::unique('users', 'username')->ignore($user->id)],
            'password'     => 'nullable|min:6|confirmed',
            'address'      => 'required|string',
        ]);

        $user->name           = $validated['fullName'];
        $user->username       = $validated['username'];
        $user->email          = $validated['email'];
        $user->tempat_lahir   = $validated['placeOfBirth'];
        $user->tanggal_lahir  = $validated['dateOfBirth'];
        $user->jenis_kelamin  = $validated['gender'];
        $user->alamat         = $validated['address'];
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }
        $user->save();

        return redirect()->route('users.index')->with('success', 'Data user berhasil diupdate');
    }
    public function dataPengajar(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 'pengajar')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
        <a href="' . route('users.editPengajar', $row->id) . '" class="btn btn-sm btn-warning">Edit</a>
        <form action="' . route('users.destroy', $row->id) . '" method="POST" style="display:inline-block;">
            ' . csrf_field() . method_field('DELETE') . '
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Yakin ingin menghapus?\')">Hapus</button>
        </form>
    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
    public function editPengajar(User $user)
    {
        abort_if($user->role !== 'pengajar', 404);
        return view('user.edit-pengajar', compact('user'));
    }

    public function updatePengajar(Request $request, User $user)
    {
        abort_if($user->role !== 'pengajar', 404);

        $request->merge([
            'email'    => strtolower(trim($request->email)),
            'username' => trim($request->username),
        ]);

        $validated = $request->validate([
            'fullName' => 'required|string|max:255',
            'gender'   => ['nullable', Rule::in(['Laki-laki', 'Perempuan'])],
            'email'    => 'required|email',
            'username' => ['required', Rule::unique('users', 'username')->ignore($user->id)],
            'password' => 'nullable|min:6|confirmed',
            'address'  => 'nullable|string',
        ]);

        $user->name         = $validated['fullName'];
        $user->username     = $validated['username'];
        $user->email        = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }
        if (!empty($validated['gender']))  $user->jenis_kelamin = $validated['gender'];
        if (!empty($validated['address'])) $user->alamat        = $validated['address'];
        $user->save();

        return redirect()->route('users.pengajar')->with('success', 'Pengajar berhasil diupdate');
    }
}
