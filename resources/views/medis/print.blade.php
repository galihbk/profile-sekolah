@php
  $u = $medis->user;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Hasil Pemeriksaan Kesehatan Lansia</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  :root { --fg:#111; --muted:#666; --accent:#0ea5e9; }
  @media (prefers-color-scheme: dark) {
    :root { --fg:#f5f5f5; --muted:#c7c7c7; --accent:#38bdf8; background:#0b0b0b; }
    body { background:#0b0b0b; color:var(--fg) }
    .card { background:#111; }
    .divider { border-color:#333; }
  }
  *{box-sizing:border-box}
  body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,'Helvetica Neue',Arial,'Noto Sans',sans-serif; line-height:1.45; margin:0; padding:24px; color:var(--fg)}
  .sheet{max-width:800px; margin:0 auto}
  .header{display:flex; align-items:center; gap:12px; margin-bottom:8px}
  .title{font-size:20px; font-weight:800; letter-spacing:.3px}
  .section-title{display:flex; align-items:center; gap:8px; font-weight:800; margin:22px 0 10px}
  .emoji{font-size:20px}
  .grid{display:grid; grid-template-columns:1.2fr 1fr; gap:16px}
  .card{padding:18px; border-radius:12px; background:#fff; box-shadow:0 1px 0 rgba(0,0,0,.05)}
  .row{display:flex; gap:10px; align-items:center; margin:6px 0}
  .label{min-width:150px; color:var(--muted)}
  ul{margin:8px 0 0 18px}
  li{margin:6px 0}
  .divider{border:none; border-top:1px solid #e5e7eb; margin:18px 0}
  .muted{color:var(--muted)}
  .print-btn{position:fixed; right:24px; top:20px; padding:8px 12px; border-radius:8px; background:var(--accent); color:white; border:none; cursor:pointer}
  @media print {
    .print-btn{display:none}
    body{padding:0}
    .sheet{margin:0; max-width:100%}
    .card{box-shadow:none}
  }
</style>
</head>
<body>
<button class="print-btn" onclick="window.print()">Print</button>

<div class="sheet">
  <div class="header">
    <div class="emoji">📅</div>
    <div class="title">HASIL PEMERIKSAAN KESEHATAN LANSIA</div>
  </div>
  <div class="card">
    <div class="row"><div class="label">Nama</div><div>: {{ $u?->name ?? '-' }}</div></div>
    <div class="row"><div class="label">Umur</div><div>: {{ $umur }}</div></div>
    <div class="row"><div class="label">Tanggal</div><div>: {{ $tanggal }}</div></div>
  </div>

  <div class="section-title"><span class="emoji">🧪</span> HASIL PEMERIKSAAN</div>
  <div class="card">
    <div class="row"><div class="label">Gula Darah</div><div>: {{ $medis->gula_darah_mg_dl ? $medis->gula_darah_mg_dl.' mg/dL' : '................' }} @if($medis->gula_darah_tipe) ({{ strtoupper($medis->gula_darah_tipe) }}) @endif</div></div>
    <div class="row"><div class="label">Kolesterol</div><div>: {{ $medis->kolesterol_mg_dl ? $medis->kolesterol_mg_dl.' mg/dL' : '................' }}</div></div>
    <div class="row"><div class="label">Asam Urat</div><div>: {{ $medis->asam_urat_mg_dl ? $medis->asam_urat_mg_dl.' mg/dL' : '................' }}</div></div>
    <div class="row"><div class="label">Tekanan Darah</div><div>: 
      @if($medis->tensi_sistolik && $medis->tensi_diastolik)
        {{ $medis->tensi_sistolik }} / {{ $medis->tensi_diastolik }} mmHg
      @else
        ................
      @endif
    </div></div>
    <div class="row"><div class="label">Berat Badan</div><div>: {{ $medis->berat_kg ? number_format($medis->berat_kg,1).' kg' : '........ kg' }}</div></div>
    <div class="row"><div class="label">Tinggi Badan</div><div>: {{ $medis->tinggi_cm ? number_format($medis->tinggi_cm,1).' cm' : '........ cm' }}</div></div>
    <div class="row"><div class="label">Oksigen</div><div>: {{ $medis->spo2 ? $medis->spo2.' %' : '........ %' }}</div></div>
  </div>

  <div class="section-title"><span class="emoji">✅</span> ANJURAN UNTUK LANSIA</div>
  <div class="card">
    @if($medis->diagnosa?->anjuran)
      {!! nl2br(e($medis->diagnosa->anjuran)) !!}
    @else
      <ul class="muted">
        <li>Banyak makan sayur, buah, dan lauk sehat (ikan, tempe, tahu, ayam tanpa kulit).</li>
        <li>Minum air putih cukup.</li>
        <li>Jalan santai atau senam ringan secara rutin.</li>
        <li>Berjemur pagi untuk tulang sehat.</li>
        <li>Tidur cukup, jangan sering begadang.</li>
        <li>Posisi duduk dan berdiri tegak agar tidak cepat bongkok.</li>
      </ul>
    @endif
  </div>

  <div class="section-title"><span class="emoji">❌</span> PANTANGAN UNTUK LANSIA</div>
  <div class="card">
    @if($medis->diagnosa?->pantangan)
      {!! nl2br(e($medis->diagnosa->pantangan)) !!}
    @else
      <ul class="muted">
        <li>Hindari makanan manis berlebihan (teh manis, sirup, kue, permen).</li>
        <li>Kurangi jeroan, kulit ayam, udang, kepiting, makanan asin/instan dan gorengan.</li>
        <li>Jangan merokok atau menghirup asap rokok.</li>
        <li>Jangan terlalu lama duduk/tiduran; tetap bergerak ringan.</li>
        <li>Kelola emosi, jauhi stres berlebihan.</li>
      </ul>
    @endif
  </div>

  <div class="section-title"><span class="emoji">📝</span> Catatan Dokter/Perawat</div>
  <div class="card">
    <div class="muted" style="min-height:80px">
      {!! nl2br(e($medis->tambahan ?? '..........................................................')) !!}
    </div>
  </div>

  <hr class="divider">
  <div class="muted" style="font-size:12px">
    Dicetak pada {{ now()->locale('id')->translatedFormat('d F Y H:i') }}
  </div>
</div>
</body>
</html>
