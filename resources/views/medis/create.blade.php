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
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <form class="row g-3" action="{{ route('medis.store') }}" method="POST">
        @csrf

        <input type="hidden" id="user_id" name="user_id" value="{{ old('user_id') }}">

        <div class="col-md-6 position-relative">
          <label for="fullName">Nama Lengkap</label>
          <input type="text" id="fullName" name="fullName"
                 class="form-control @error('fullName') is-invalid @enderror"
                 value="{{ old('fullName') }}" autocomplete="off" placeholder="Ketik nama pasien...">
          <ul id="userList" class="list-group position-absolute w-100" style="z-index:1000; display:none;"></ul>
        </div>

        <div class="col-md-6">
          <label for="ttl">Tempat, Tanggal Lahir</label>
          <input type="text" id="ttl" name="ttl" class="form-control" value="{{ old('ttl') }}" readonly>
        </div>

        <div class="col-md-6">
          <label for="umur">Umur</label>
          <input type="text" id="umur" name="umur" class="form-control" value="{{ old('umur') }}" readonly>
        </div>

        <div class="col-md-6">
          <label for="gender">Jenis Kelamin</label>
          <input type="text" id="gender" name="gender" class="form-control" value="{{ old('gender') }}" readonly>
        </div>

        <div class="col-md-6">
          <label class="form-label">Gula Darah (mg/dL)</label>
          <div class="input-group">
            <input type="text" name="gula_darah_mg_dl" class="form-control"
                   value="{{ old('gula_darah_mg_dl') }}" placeholder="mis. 95 atau -">
            <select name="gula_darah_tipe" class="form-select" style="max-width: 160px">
              <option value="" {{ old('gula_darah_tipe')=='' ? 'selected':'' }}>- tipe -</option>
              <option value="puasa"   {{ old('gula_darah_tipe')=='puasa' ? 'selected':'' }}>Puasa</option>
              <option value="jpp"     {{ old('gula_darah_tipe')=='jpp' ? 'selected':'' }}>JPP (2 jam)</option>
              <option value="sewaktu" {{ old('gula_darah_tipe')=='sewaktu' ? 'selected':'' }}>Sewaktu</option>
            </select>
          </div>
          <small class="text-muted">Standar: Puasa 70–110; JPP 70–140; Sewaktu ≤170 mg/dL.</small>
        </div>

        <div class="col-md-6">
          <label for="kolesterol_mg_dl" class="form-label">Kolesterol Total (mg/dL)</label>
          <input type="text" id="kolesterol_mg_dl" name="kolesterol_mg_dl" class="form-control"
                 value="{{ old('kolesterol_mg_dl') }}" placeholder="≤200 disarankan atau -">
        </div>

        <div class="col-md-6">
          <label for="asam_urat_mg_dl" class="form-label">Asam Urat (mg/dL)</label>
          <input type="text" id="asam_urat_mg_dl" name="asam_urat_mg_dl" class="form-control"
                 value="{{ old('asam_urat_mg_dl') }}" placeholder="mis. 5.6 atau -">
          <small class="text-muted">Acuan: P 2.4–5.7 • L 3.4–7.0 mg/dL.</small>
        </div>

        <div class="col-md-3">
          <label for="berat_kg" class="form-label">Berat Badan (kg)</label>
          <input type="text" id="berat_kg" name="berat_kg" class="form-control" value="{{ old('berat_kg') }}" placeholder="mis. 60.5 atau -">
        </div>
        <div class="col-md-3">
          <label for="tinggi_cm" class="form-label">Tinggi Badan (cm)</label>
          <input type="text" id="tinggi_cm" name="tinggi_cm" class="form-control" value="{{ old('tinggi_cm') }}" placeholder="mis. 165 atau -">
        </div>
        <div class="col-md-3">
          <label for="imt" class="form-label">IMT</label>
          <input type="text" id="imt" name="imt" class="form-control" value="{{ old('imt') }}" readonly>
          <small id="imtLabel" class="text-muted"></small>
        </div>

        <div class="col-md-6">
          <label class="form-label">Tekanan Darah (mmHg)</label>
          <div class="input-group">
            <input type="text" class="form-control" name="tensi_sistolik" id="tensi_sistolik"
                   value="{{ old('tensi_sistolik') }}" placeholder="Sistolik atau -">
            <span class="input-group-text">/</span>
            <input type="text" class="form-control" name="tensi_diastolik" id="tensi_diastolik"
                   value="{{ old('tensi_diastolik') }}" placeholder="Diastolik atau -">
          </div>
          <small class="text-muted">Acuan umum: 90/60–120/80 mmHg.</small>
        </div>

        <div class="col-md-6">
          <label for="spo2" class="form-label">Kadar Oksigen (SpO₂ %)</label>
          <div class="input-group">
            <input type="text" id="spo2" name="spo2" class="form-control" value="{{ old('spo2') }}" placeholder="mis. 98 atau -">
            <span class="input-group-text">%</span>
          </div>
          <small class="text-muted">Normal 95–100%.</small>
        </div>

        <div class="col-md-12">
          <label for="keluhan">Keluhan</label>
          <textarea id="keluhan" name="keluhan" class="form-control" rows="3" placeholder="Boleh kosong atau -">{{ old('keluhan') }}</textarea>
        </div>

        <div class="col-md-6">
          <label for="diagnosa">Diagnosa</label>
          <select id="diagnosa_id" name="diagnosa_id" class="form-control">
            <option value="">Pilih Diagnosa...</option>
            @foreach ($diagnosa as $d)
              <option value="{{ $d->id }}" {{ old('diagnosa_id')==$d->id ? 'selected' : '' }}>
                {{ $d->diagnosa }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6">
          <label for="tanggal_periksa">Tanggal Periksa</label>
          <input type="date" id="tanggal_periksa" name="tanggal_periksa"
                 class="form-control" value="{{ old('tanggal_periksa', date('Y-m-d')) }}">
        </div>

        <div class="col-md-12">
          <label for="tambahan">Tambahan dari Dokter</label>
          <textarea id="tambahan" name="tambahan" class="form-control" rows="3" placeholder="- jika tidak ada">{{ old('tambahan') }}</textarea>
        </div>

        <div class="col-12">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  @push('scripts')
  <script>
    function parseLooseNumber(v) {
      if (v === undefined || v === null) return null;
      v = String(v).trim();
      if (v === '' || v === '-') return null;
      v = v.replace(',', '.');
      const n = Number(v);
      return Number.isFinite(n) ? n : null;
    }

    function parseBirthDate(str) {
      if (!str) return null;
      const m = String(str).match(/^(\d{4})-(\d{2})-(\d{2})/);
      if (m) return new Date(+m[1], +m[2]-1, +m[3]);
      const d = new Date(str);
      if (isNaN(d)) return null;
      return new Date(d.getFullYear(), d.getMonth(), d.getDate());
    }

    function formatDateID(date, mode="long") {
      const bulan = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
      const dd = String(date.getDate()).padStart(2,'0');
      const mm = String(date.getMonth()+1).padStart(2,'0');
      const yyyy = date.getFullYear();
      if (mode === "short") return `${dd}/${mm}/${yyyy}`;
      return `${dd} ${bulan[date.getMonth()]} ${yyyy}`;
    }

    function getAgeFromDate(birthDate) {
      if (!birthDate) return "";
      const today = new Date();
      let age = today.getFullYear() - birthDate.getFullYear();
      const m = today.getMonth() - birthDate.getMonth();
      if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) age--;
      return age;
    }

    function hitungIMT() {
      const bb = parseLooseNumber($('#berat_kg').val());
      const tb = parseLooseNumber($('#tinggi_cm').val());
      if (bb !== null && tb !== null && tb > 0) {
        const imt = bb / Math.pow(tb/100, 2);
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

    $(document).ready(function(){
      $('#berat_kg, #tinggi_cm').on('input', hitungIMT);

      $('#fullName').on('keyup', function() {
        let query = $(this).val();
        if (query.length > 1) {
          $.ajax({
            url: '{{ route('user.autocomplete') }}',
            type: 'GET',
            data: { query },
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
        const gender  = ($(this).data('gender') || '').toString().trim();

        const d = parseBirthDate(tanggal);
        const tanggalFormatted = d ? formatDateID(d, "long") : "";

        let ttlText = "";
        if (tempat && tanggalFormatted) ttlText = `${tempat}, ${tanggalFormatted}`;
        else if (tempat) ttlText = tempat;
        else if (tanggalFormatted) ttlText = tanggalFormatted;
        $('#ttl').val(ttlText);

        $('#gender').val(gender);

        const age = d ? getAgeFromDate(d) : "";
        $('#umur').val(age !== "" ? `${age} tahun` : "");

        $('#userList').hide();
      });

      $(document).click(function(e) {
        if (!$(e.target).closest('#fullName, #userList').length) {
          $('#userList').hide();
        }
      });
    });
  </script>
  @endpush
</x-app-layout>
