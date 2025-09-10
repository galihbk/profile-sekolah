<x-app-layout>
  <div class="card">
    <div class="card-body p-5">
      <div class="card-title d-flex align-items-center">
        <div><i class="bx bxs-user me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary">Edit Catatan Medis</h5>
      </div>
      <hr>

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <form class="row g-3" action="{{ route('medis.update', $medis->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- HIDDEN user_id --}}
        <input type="hidden" id="user_id" name="user_id" value="{{ $medis->user_id }}">

        {{-- Nama Lengkap (readonly, tanpa autocomplete) --}}
        <div class="col-md-6 position-relative">
          <label for="fullName">Nama Lengkap</label>
          <input type="text" id="fullName" name="fullName" class="form-control" 
                 value="{{ $medis->user->name }}" readonly>
        </div>

        {{-- Tempat, Tanggal Lahir (readonly) --}}
        <div class="col-md-6">
          <label for="ttl">Tempat, Tanggal Lahir</label>
          <input type="text" id="ttl" name="ttl" class="form-control" value="{{ $ttl }}" readonly>
        </div>

        {{-- Umur (readonly) --}}
        <div class="col-md-6">
          <label for="umur">Umur</label>
          <input type="text" id="umur" name="umur" class="form-control" value="{{ $umur }}" readonly>
        </div>

        {{-- Jenis Kelamin (readonly) --}}
        <div class="col-md-6">
          <label for="gender">Jenis Kelamin</label>
          <input type="text" id="gender" name="gender" class="form-control" 
                 value="{{ $medis->user->jenis_kelamin }}" readonly>
        </div>

        {{-- Gula darah --}}
        <div class="col-md-6">
          <label class="form-label">Gula Darah (mg/dL)</label>
          <div class="input-group">
            <input type="number" min="0" step="1" name="gula_darah_mg_dl" class="form-control"
                   value="{{ old('gula_darah_mg_dl', $medis->gula_darah_mg_dl) }}" placeholder="contoh: 95">
            <select name="gula_darah_tipe" class="form-select" style="max-width:160px">
              @php $tipe = old('gula_darah_tipe', $medis->gula_darah_tipe); @endphp
              <option value=""        {{ $tipe=='' ? 'selected':'' }}>- tipe -</option>
              <option value="puasa"   {{ $tipe=='puasa' ? 'selected':'' }}>Puasa</option>
              <option value="jpp"     {{ $tipe=='jpp' ? 'selected':'' }}>JPP (2 jam)</option>
              <option value="sewaktu" {{ $tipe=='sewaktu' ? 'selected':'' }}>Sewaktu</option>
            </select>
          </div>
          <small class="text-muted">Standar: Puasa 70–110; JPP 70–140; Sewaktu ≤170 mg/dL.</small>
        </div>

        {{-- Kolesterol --}}
        <div class="col-md-6">
          <label for="kolesterol_mg_dl" class="form-label">Kolesterol Total (mg/dL)</label>
          <input type="number" min="0" step="1" id="kolesterol_mg_dl" name="kolesterol_mg_dl" class="form-control"
                 value="{{ old('kolesterol_mg_dl', $medis->kolesterol_mg_dl) }}" placeholder="≤200 disarankan">
        </div>

        {{-- Asam urat --}}
        <div class="col-md-6">
          <label for="asam_urat_mg_dl" class="form-label">Asam Urat (mg/dL)</label>
          <input type="number" min="0" step="0.1" id="asam_urat_mg_dl" name="asam_urat_mg_dl" class="form-control"
                 value="{{ old('asam_urat_mg_dl', $medis->asam_urat_mg_dl) }}" placeholder="contoh: 5.6">
          <small class="text-muted">Acuan: P 2.4–5.7 • L 3.4–7.0 mg/dL.</small>
        </div>

        {{-- BB / TB / IMT --}}
        <div class="col-md-3">
          <label for="berat_kg" class="form-label">Berat Badan (kg)</label>
          <input type="number" min="1" step="0.1" id="berat_kg" name="berat_kg" class="form-control"
                 value="{{ old('berat_kg', $medis->berat_kg) }}">
        </div>
        <div class="col-md-3">
          <label for="tinggi_cm" class="form-label">Tinggi Badan (cm)</label>
          <input type="number" min="30" step="0.1" id="tinggi_cm" name="tinggi_cm" class="form-control"
                 value="{{ old('tinggi_cm', $medis->tinggi_cm) }}">
        </div>
        <div class="col-md-3">
          <label for="imt" class="form-label">IMT</label>
          <input type="text" id="imt" name="imt" class="form-control"
                 value="{{ old('imt', $medis->imt) }}" readonly>
          <small id="imtLabel" class="text-muted"></small>
        </div>

        {{-- Tensi --}}
        <div class="col-md-6">
          <label class="form-label">Tekanan Darah (mmHg)</label>
          <div class="input-group">
            <input type="number" min="60" max="260" step="1" class="form-control" name="tensi_sistolik" id="tensi_sistolik"
                   value="{{ old('tensi_sistolik', $medis->tensi_sistolik) }}" placeholder="Sistolik">
            <span class="input-group-text">/</span>
            <input type="number" min="30" max="180" step="1" class="form-control" name="tensi_diastolik" id="tensi_diastolik"
                   value="{{ old('tensi_diastolik', $medis->tensi_diastolik) }}" placeholder="Diastolik">
          </div>
          <small class="text-muted">Acuan umum: 90/60–120/80 mmHg.</small>
        </div>

        {{-- SpO2 --}}
        <div class="col-md-6">
          <label for="spo2" class="form-label">Kadar Oksigen (SpO₂ %)</label>
          <div class="input-group">
            <input type="number" min="50" max="100" step="1" id="spo2" name="spo2" class="form-control"
                   value="{{ old('spo2', $medis->spo2) }}" placeholder="mis. 98">
            <span class="input-group-text">%</span>
          </div>
        </div>

        {{-- Keluhan --}}
        <div class="col-md-12">
          <label for="keluhan">Keluhan</label>
          <textarea id="keluhan" name="keluhan" class="form-control" rows="3">{{ old('keluhan', $medis->keluhan) }}</textarea>
        </div>

        {{-- Diagnosa --}}
        <div class="col-md-6">
          <label for="diagnosa">Diagnosa</label>
          <select id="diagnosa_id" name="diagnosa_id" class="form-control">
            <option value="">Pilih Diagnosa...</option>
            @foreach ($diagnosa as $d)
              <option value="{{ $d['id'] }}" {{ old('diagnosa_id', $medis->diagnosa_id) == $d['id'] ? 'selected' : '' }}>
                {{ $d['diagnosa'] }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- Tanggal Periksa --}}
        <div class="col-md-6">
          <label for="tanggal_periksa">Tanggal Periksa</label>
          <input type="date" id="tanggal_periksa" name="tanggal_periksa" class="form-control"
                 value="{{ old('tanggal_periksa', \Carbon\Carbon::parse($medis->tanggal_periksa)->format('Y-m-d')) }}">
        </div>

        {{-- Tambahan --}}
        <div class="col-md-12">
          <label for="tambahan">Tambahan dari Dokter</label>
          <textarea id="tambahan" name="tambahan" class="form-control" rows="3">{{ old('tambahan', $medis->tambahan) }}</textarea>
        </div>

        <div class="col-12">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <a href="{{ route('medis') }}" class="btn btn-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>

  @push('scripts')
  <script>
    // Flag: mode edit -> jangan pasang autocomplete handlers
    window.isEdit = true;

    function hitungIMT() {
      const bb = parseFloat($('#berat_kg').val());
      const tb = parseFloat($('#tinggi_cm').val());
      if (!isNaN(bb) && !isNaN(tb) && tb > 0) {
        const imt = bb / Math.pow(tb/100, 2);
        $('#imt').val(imt.toFixed(2));
        let kategori = '';
        if (imt < 18.5) kategori = 'Kurus';
        else if (imt < 23) kategori = 'Normal (Asia)';
        else if (imt < 27.5) kategori = 'Overweight';
        else kategori = 'Obesitas';
        $('#imtLabel').text('Kategori: ' + kategori);
      } else {
        $('#imt').val(''); $('#imtLabel').text('');
      }
    }
    // hitung IMT awal saat halaman terbuka
    $(document).ready(function(){
      hitungIMT();
      $('#berat_kg, #tinggi_cm').on('input', hitungIMT);
    });
  </script>
  @endpush
</x-app-layout>
