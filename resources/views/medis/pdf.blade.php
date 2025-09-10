@php $u = $medis->user; @endphp
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Rekam Medis</title>
<style>
  @page { margin: 24mm 18mm; } /* margin cetak */
  * { box-sizing: border-box; }
  body { font-family: DejaVu Sans, sans-serif; color:#111; background:#fff; font-size:12pt; }
  .title { font-weight:800; font-size:16pt; margin-bottom:10pt; }
  .section { margin: 14pt 0 10pt; font-weight:700; background:#eef6ff; padding:6pt 8pt; border-radius:6pt; }
  .card { border:1pt solid #dfe3e8; border-radius:8pt; padding:10pt 12pt; margin-bottom:10pt; }
  .row { display:flex; margin:4pt 0; }
  .label { width:140pt; color:#555; }
  ul { margin:6pt 0 0 14pt; padding:0; }
  li { margin:4pt 0; }
  .muted { color:#666; }
</style>
</head>
<body>
  <div class="title">HASIL PEMERIKSAAN KESEHATAN LANSIA</div>
  <div class="card">
    <div class="row"><div class="label">Nama</div><div>: {{ $u?->name ?? '-' }}</div></div>
    <div class="row"><div class="label">Umur</div><div>: {{ $umur }}</div></div>
    <div class="row"><div class="label">Tanggal</div><div>: {{ $tanggal }}</div></div>
  </div>

  <div class="section">HASIL PEMERIKSAAN</div>
  <div class="card">
    <div class="row"><div class="label">Gula Darah</div><div>: {{ $medis->gula_darah_mg_dl ? $medis->gula_darah_mg_dl.' mg/dL' : '................' }} @if($medis->gula_darah_tipe) ({{ strtoupper($medis->gula_darah_tipe) }}) @endif</div></div>
    <div class="row"><div class="label">Kolesterol</div><div>: {{ $medis->kolesterol_mg_dl ? $medis->kolesterol_mg_dl.' mg/dL' : '................' }}</div></div>
    <div class="row"><div class="label">Asam Urat</div><div>: {{ $medis->asam_urat_mg_dl ? $medis->asam_urat_mg_dl.' mg/dL' : '................' }}</div></div>
    <div class="row"><div class="label">Tekanan Darah</div><div>: @if($medis->tensi_sistolik && $medis->tensi_diastolik) {{ $medis->tensi_sistolik }} / {{ $medis->tensi_diastolik }} mmHg @else ................ @endif</div></div>
    <div class="row"><div class="label">Berat Badan</div><div>: {{ $medis->berat_kg ? number_format($medis->berat_kg,1).' kg' : '........ kg' }}</div></div>
    <div class="row"><div class="label">Tinggi Badan</div><div>: {{ $medis->tinggi_cm ? number_format($medis->tinggi_cm,1).' cm' : '........ cm' }}</div></div>
    <div class="row"><div class="label">Oksigen</div><div>: {{ $medis->spo2 ? $medis->spo2.' %' : '........ %' }}</div></div>
  </div>

  <div class="section">ANJURAN UNTUK LANSIA</div>
  <div class="card">
    @if($medis->diagnosa?->anjuran)
      <div class="muted">{!! nl2br(e($medis->diagnosa->anjuran)) !!}</div>
    @else
      <ul class="muted">
        <li>Banyak makan sayur, buah, dan lauk sehat.</li>
        <li>Minum air putih cukup.</li>
        <li>Jalan santai atau senam ringan rutin.</li>
        <li>Berjemur pagi dan tidur cukup.</li>
      </ul>
    @endif
  </div>

  <div class="section">PANTANGAN UNTUK LANSIA</div>
  <div class="card">
    @if($medis->diagnosa?->pantangan)
      <div class="muted">{!! nl2br(e($medis->diagnosa->pantangan)) !!}</div>
    @else
      <ul class="muted">
        <li>Batasi gula, gorengan, makanan instan/asin.</li>
        <li>Hindari rokok dan asap rokok.</li>
      </ul>
    @endif
  </div>

  <div class="section">Catatan Dokter/Perawat</div>
  <div class="card">
    <div class="muted" style="min-height:60pt">{!! nl2br(e($medis->tambahan ?? '..........................................................')) !!}</div>
  </div>
</body>
</html>
