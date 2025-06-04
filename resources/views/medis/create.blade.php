<x-app-layout>
    <div class="card">
        <div class="card-body p-5">
            <div class="card-title d-flex align-items-center">
                <div><i class="bx bxs-user me-1 font-22 text-primary"></i></div>
                <h5 class="mb-0 text-primary">Tambah Catatan Medis</h5>
            </div>
            <hr>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form class="row g-3" action="{{ route('medis.store') }}" method="POST">
                @csrf

                {{-- HIDDEN user_id --}}
                <input type="hidden" id="user_id" name="user_id" value="{{ old('user_id') }}">

                {{-- Nama Lengkap --}}
                <div class="col-md-6 position-relative">
                    <label for="fullName">Nama Lengkap</label>
                    <input type="text" id="fullName" name="fullName"
                        class="form-control @error('fullName') is-invalid @enderror" value="{{ old('fullName') }}"
                        autocomplete="off" placeholder="Masukan kata kunci">
                    <ul id="userList" class="list-group position-absolute w-100" style="z-index:1000; display:none;">
                    </ul>
                    @error('fullName')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tempat, Tanggal Lahir --}}
                <div class="col-md-6">
                    <label for="ttl">Tempat, Tanggal Lahir</label>
                    <input type="text" id="ttl" name="ttl"
                        class="form-control @error('ttl') is-invalid @enderror" value="{{ old('ttl') }}" readonly>
                    @error('ttl')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Umur --}}
                <div class="col-md-6">
                    <label for="umur">Umur</label>
                    <input type="text" id="umur" name="umur" readonly
                        class="form-control @error('umur') is-invalid @enderror" value="{{ old('umur') }}">
                    @error('umur')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jenis Kelamin --}}
                <div class="col-md-6">
                    <label for="gender">Jenis Kelamin</label>
                    <input type="text" id="gender" name="gender" readonly
                        class="form-control @error('gender') is-invalid @enderror" value="{{ old('gender') }}">
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Keluhan --}}
                <div class="col-md-12">
                    <label for="keluhan">Keluhan</label>
                    <textarea id="keluhan" name="keluhan" class="form-control @error('keluhan') is-invalid @enderror" rows="3">{{ old('keluhan') }}</textarea>
                    @error('keluhan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Diagnosa --}}
                <div class="col-md-6">
                    <label for="diagnosa">Diagnosa</label>
                    <select id="diagnosa_id" name="diagnosa_id"
                        class="form-control @error('diagnosa') is-invalid @enderror">
                        <option disabled selected>Pilih Diagnosa...</option>
                        @foreach ($diagnosa as $d)
                            <option value="{{ $d['id'] }}" {{ old('diagnosa') == $d['id'] ? 'selected' : '' }}>
                                {{ $d['diagnosa'] }}</option>
                        @endforeach
                    </select>
                    @error('diagnosa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="tanggal_periksa">Tanggal Periksa</label>
                    <input type="date" id="tanggal_periksa" name="tanggal_periksa"
                        class="form-control @error('tanggal_periksa') is-invalid @enderror"
                        value="{{ old('tanggal_periksa', date('Y-m-d')) }}">
                    @error('tanggal_periksa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tambahan dari Dokter --}}
                <div class="col-md-12">
                    <label for="tambahan">Tambahan dari Dokter</label>
                    <textarea id="tambahan" name="tambahan" class="form-control @error('tambahan') is-invalid @enderror" rows="3">{{ old('tambahan') }}</textarea>
                    @error('tambahan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol Submit --}}
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#fullName').on('keyup', function() {
                    let query = $(this).val();
                    if (query.length > 1) {
                        $.ajax({
                            url: '{{ route('user.autocomplete') }}',
                            type: 'GET',
                            data: {
                                query: query
                            },
                            success: function(data) {
                                let html = '';
                                data.forEach(user => {
                                    html += `<li class="list-group-item list-user"
                                        data-id="${user.id}"
                                        data-name="${user.name}"
                                        data-place="${user.tempat_lahir || ''}"
                                        data-dob="${user.tanggal_lahir || ''}"
                                        data-gender="${user.jenis_kelamin || ''}">
                                        ${user.name}
                                    </li>`;
                                });
                                $('#userList').html(html).show();
                            }
                        });
                    } else {
                        $('#userList').hide();
                    }
                });

                $(document).on('click', '.list-user', function() {
                    $('#fullName').val($(this).data('name'));
                    $('#user_id').val($(this).data('id'));
                    const tempat = $(this).data('place');
                    const tanggal = $(this).data('dob');
                    const gender = $(this).data('gender');

                    $('#ttl').val(`${tempat}, ${tanggal}`);
                    $('#gender').val(gender);

                    if (tanggal) {
                        const age = getAge(tanggal);
                        $('#umur').val(age + ' tahun');
                    } else {
                        $('#umur').val('');
                    }

                    $('#userList').hide();
                });

                $(document).click(function(e) {
                    if (!$(e.target).closest('#fullName, #userList').length) {
                        $('#userList').hide();
                    }
                });

                function getAge(birthdate) {
                    const today = new Date();
                    const birth = new Date(birthdate);
                    let age = today.getFullYear() - birth.getFullYear();
                    const m = today.getMonth() - birth.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) {
                        age--;
                    }
                    return age;
                }
            });
        </script>
    @endpush
</x-app-layout>
