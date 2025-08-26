<x-app-layout>
    <div class="card">
        <div class="card-body p-5">
            <div class="card-title d-flex align-items-center">
                <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                </div>
                <h5 class="mb-0 text-primary">Tambah Pengajar</h5>
            </div>
            <hr>
            <form class="row g-3" action="{{ route('users.storePengajar') }}" method="POST">
                @csrf

                <div class="col-md-6">
                    <label for="fullName" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="fullName" name="fullName" class="form-control @error('fullName') is-invalid @enderror" value="{{ old('fullName') }}">
                    @error('fullName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="gender">Jenis Kelamin</label>
                    <select id="gender" name="gender" class="form-control @error('gender') is-invalid @enderror">
                        <option disabled selected>Pilih...</option>
                        <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="email">Username</label>
                    <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}">
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                </div>

                <div class="col-md-12">
                    <label for="address">Alamat</label>
                    <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address') }}</textarea>
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-span-full">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>