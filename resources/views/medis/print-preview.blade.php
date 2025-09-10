<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Preview PDF Rekam Medis</title>
<style>
  body{font-family:system-ui,Segoe UI,Roboto,Arial,sans-serif; margin:0; background:#f6f7fb}
  .bar{display:flex; gap:8px; align-items:center; padding:12px; background:#fff; border-bottom:1px solid #e5e7eb; position:sticky; top:0; z-index:10}
  .wrap{padding:12px}
  .iframe { width:100%; height: calc(100vh - 58px); border:none; background:#fff }
  select,button{padding:8px 10px; border-radius:8px; border:1px solid #d1d5db; background:#fff}
  button.primary{background:#2563eb; color:#fff; border-color:#2563eb}
</style>
</head>
<body>
  <div class="bar">
    <label>Pilihan Kertas:</label>
    <select id="paper">
      <option value="A4">A4 (210×297 mm)</option>
      <option value="F4">F4 (210×330 mm)</option>
    </select>
    <button id="btnRefresh">Refresh</button>
    <button id="btnDownload" class="primary">Download PDF</button>
    <div style="margin-left:auto; color:#6b7280">Preview</div>
  </div>

  <div class="wrap">
    <iframe id="pdfFrame" class="iframe"
      src="{{ route('medis.print.stream', $medis->id) }}?paper=A4"
      title="Preview PDF"></iframe>
  </div>

<script>
  const frame = document.getElementById('pdfFrame');
  const paper = document.getElementById('paper');

  // load default dari query jika ada
  const urlParams = new URLSearchParams(location.search);
  if (urlParams.get('paper')) paper.value = urlParams.get('paper');

  document.getElementById('btnRefresh').onclick = () => {
    frame.src = `{{ route('medis.print.stream', $medis->id) }}?paper=${paper.value}`;
  };

  document.getElementById('btnDownload').onclick = () => {
    window.location.href = `{{ route('medis.print.download', $medis->id) }}?paper=${paper.value}`;
  };
</script>
</body>
</html>
