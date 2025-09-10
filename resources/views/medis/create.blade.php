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
                <div class="col-md-6">
                    <label class="form-label">Gula Darah (mg/dL)</label>
                    <div class="input-group">
                        <input type="number" min="0" step="1" name="gula_darah_mg_dl" class="form-control"
                            value="{{ old('gula_darah_mg_dl') }}" placeholder="contoh: 95">
                        <select name="gula_darah_tipe" class="form-select" style="max-width: 160px">
                            <option value="" {{ old('gula_darah_tipe') == '' ? 'selected' : '' }}>- tipe -
                            </option>
                            <option value="puasa" {{ old('gula_darah_tipe') == 'puasa' ? 'selected' : '' }}>Puasa
                            </option>
                            <option value="jpp" {{ old('gula_darah_tipe') == 'jpp' ? 'selected' : '' }}>JPP (2 jam)
                            </option>
                            <option value="sewaktu" {{ old('gula_darah_tipe') == 'sewaktu' ? 'selected' : '' }}>Sewaktu
                            </option>
                        </select>
                    </div>
                    <small class="text-muted">Standar: Puasa 70–110; JPP 70–140; Sewaktu ≤170 mg/dL.</small>
                </div>

                {{-- Kolesterol --}}
                <div class="col-md-6">
                    <label for="kolesterol_mg_dl" class="form-label">Kolesterol Total (mg/dL)</label>
                    <input type="number" min="0" step="1" id="kolesterol_mg_dl" name="kolesterol_mg_dl"
                        class="form-control" value="{{ old('kolesterol_mg_dl') }}" placeholder="≤200 disarankan">
                </div>

                {{-- Asam urat --}}
                <div class="col-md-6">
                    <label for="asam_urat_mg_dl" class="form-label">Asam Urat (mg/dL)</label>
                    <input type="number" min="0" step="0.1" id="asam_urat_mg_dl" name="asam_urat_mg_dl"
                        class="form-control" value="{{ old('asam_urat_mg_dl') }}" placeholder="contoh: 5.6">
                    <small class="text-muted">Acuan: P 2.4–5.7 • L 3.4–7.0 mg/dL.</small>
                </div>

                {{-- Berat & Tinggi + IMT --}}
                <div class="col-md-3">
                    <label for="berat_kg" class="form-label">Berat Badan (kg)</label>
                    <input type="number" min="1" step="0.1" id="berat_kg" name="berat_kg"
                        class="form-control" value="{{ old('berat_kg') }}">
                </div>
                <div class="col-md-3">
                    <label for="tinggi_cm" class="form-label">Tinggi Badan (cm)</label>
                    <input type="number" min="30" step="0.1" id="tinggi_cm" name="tinggi_cm"
                        class="form-control" value="{{ old('tinggi_cm') }}">
                </div>
                <div class="col-md-3">
                    <label for="imt" class="form-label">IMT</label>
                    <input type="text" id="imt" name="imt" class="form-control"
                        value="{{ old('imt') }}" readonly>
                    <small id="imtLabel" class="text-muted"></small>
                </div>

                {{-- Tensi --}}
                <div class="col-md-6">
                    <label class="form-label">Tekanan Darah (mmHg)</label>
                    <div class="input-group">
                        <input type="number" min="60" max="260" step="1" class="form-control"
                            name="tensi_sistolik" id="tensi_sistolik" value="{{ old('tensi_sistolik') }}"
                            placeholder="Sistolik">
                        <span class="input-group-text">/</span>
                        <input type="number" min="30" max="180" step="1" class="form-control"
                            name="tensi_diastolik" id="tensi_diastolik" value="{{ old('tensi_diastolik') }}"
                            placeholder="Diastolik">
                    </div>
                    <small class="text-muted">Acuan umum: 90/60–120/80 mmHg.</small>
                </div>

                {{-- SpO₂ --}}
                <div class="col-md-6">
                    <label for="spo2" class="form-label">Kadar Oksigen (SpO₂ %)</label>
                    <div class="input-group">
                        <input type="number" min="50" max="100" step="1" id="spo2"
                            name="spo2" class="form-control" value="{{ old('spo2') }}" placeholder="mis. 98">
                        <span class="input-group-text">%</span>
                    </div>
                    <small class="text-muted">Normal 95–100%.</small>
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
                    <textarea id="tambahan" name="tambahan" class="form-control @error('tambahan') is-invalid @enderror"
                        rows="3">{{ old('tambahan') }}</textarea>
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
                function hitungIMT() {
                    const bb = parseFloat($('#berat_kg').val());
                    const tb = parseFloat($('#tinggi_cm').val());
                    if (!isNaN(bb) && !isNaN(tb) && tb > 0) {
                        const imt = bb / Math.pow(tb / 100, 2);
                        $('#imt').val(imt.toFixed(2));
                        let kategori = '';
                        if (imt < 18.5) kategori = 'Kurus';
                        else if (imt < 23) kategori = 'Normal (Asia)';
                        else if (imt < 27.5) kategori = 'Overweight';
                        else kategori = 'Obesitas';
                        $('#imtLabel').text('Kategori: ' + kategori);
                    } else {
                        $('#imt').val('');
                        $('#imtLabel').text('');
                    }
                }
                $('#berat_kg, #tinggi_cm').on('input', hitungIMT);
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

                    const tempat = ($(this).data('place') || '').toString().trim();
                    const tanggal = ($(this).data('dob') || '').toString().trim();
                    const gender = ($(this).data('gender') || '').toString().trim();

                    const d = parseBirthDate(tanggal);

                    // Pilih mode tampilan: "long" => 10 September 2025, "short" => 10/09/2025
                    const tanggalFormatted = d ? formatDateID(d, "long") : "";

                    // Tampilkan "Tempat, Tanggal Lahir" dengan format yang rapi
                    let ttlText = "";
                    if (tempat && tanggalFormatted) ttlText = `${tempat}, ${tanggalFormatted}`;
                    else if (tempat) ttlText = tempat;
                    else if (tanggalFormatted) ttlText = tanggalFormatted;
                    $('#ttl').val(ttlText);

                    // Isi jenis kelamin
                    $('#gender').val(gender);

                    // Hitung umur
                    const age = d ? getAgeFromDate(d) : "";
                    $('#umur').val(age !== "" ? `${age} tahun` : "");

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
            // --- Util: parse tanggal aman (ISO / YYYY-MM-DD) tanpa offset timezone
            function parseBirthDate(str) {
                if (!str) return null;
                // Tangkap pola YYYY-MM-DD di awal string
                const m = String(str).match(/^(\d{4})-(\d{2})-(\d{2})/);
                if (m) {
                    const y = Number(m[1]),
                        mo = Number(m[2]) - 1,
                        d = Number(m[3]);
                    // buat local date (tanpa jam) supaya tidak geser hari
                    return new Date(y, mo, d);
                }
                // fallback ke Date biasa (untuk format lain/ISO penuh)
                const d2 = new Date(str);
                if (isNaN(d2)) return null;
                return new Date(d2.getFullYear(), d2.getMonth(), d2.getDate());
            }

            // --- Util: format tanggal Indonesia (pilih salah satu style)
            function formatDateID(date, mode = "long") {
                const bulan = [
                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                ];
                const dd = String(date.getDate()).padStart(2, '0');
                const mm = String(date.getMonth() + 1).padStart(2, '0');
                const mlong = bulan[date.getMonth()];
                const yyyy = date.getFullYear();
                if (mode === "short") return `${dd}/${mm}/${yyyy}`; // 10/09/2025
                return `${dd} ${mlong} ${yyyy}`; // 10 September 2025
            }

            // --- Util: hitung umur dari tanggal lahir
            function getAgeFromDate(birthDate) {
                if (!birthDate) return "";
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) age--;
                return age;
            }
        </script>
    @endpush
</x-app-layout>
