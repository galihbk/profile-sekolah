<x-app-layout>
    <div class="card">
        <div class="card-body p-5">
            <div class="card-title d-flex align-items-center">
                <div><i class="bx bxs-user me-1 font-22 text-primary"></i></div>
                <h5 class="mb-0 text-primary">Edit User</h5>
            </div>
            <hr>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="row g-3" action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label for="fullName" class="form-label">Nama Lengkap</label>
                    <input type="text" id="fullName" name="fullName"
                        class="form-control @error('fullName') is-invalid @enderror"
                        value="{{ old('fullName', $user->name) }}" required>
                    @error('fullName')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="placeOfBirth" class="form-label">Tempat Lahir</label>
                    <input type="text" id="placeOfBirth" name="placeOfBirth"
                        class="form-control @error('placeOfBirth') is-invalid @enderror"
                        value="{{ old('placeOfBirth', $user->tempat_lahir) }}" required>
                    @error('placeOfBirth')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="dateOfBirth" class="form-label">Tanggal Lahir</label>
                    <input type="date" id="dateOfBirth" name="dateOfBirth"
                        class="form-control @error('dateOfBirth') is-invalid @enderror"
                        value="{{ old('dateOfBirth', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}">

                    @error('dateOfBirth')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="gender" class="form-label">Jenis Kelamin</label>
                    <select id="gender" name="gender" class="form-control @error('gender') is-invalid @enderror"
                        required>
                        <option disabled value="">— Pilih —</option>
                        <option value="Laki-laki"
                            {{ old('gender', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan"
                            {{ old('gender', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username"
                        class="form-control @error('username') is-invalid @enderror"
                        value="{{ old('username', $user->username) }}" required readonly>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label">Password (kosongkan jika tidak ganti)</label>
                    <input type="password" id="password" name="password"
                        class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                </div>

                <div class="col-md-12">
                    <label for="address" class="form-label">Alamat</label>
                    <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3"
                        required>{{ old('address', $user->alamat) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
