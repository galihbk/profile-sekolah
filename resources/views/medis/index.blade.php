<x-app-layout>
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-lg-6 col-xl-6">
                    <a href="{{ route('medis.create') }}" class="btn btn-primary mb-3 mb-lg-0"><i
                            class="bx bxs-plus-square"></i>Tambah Rekam Medis</a>
                </div>
            </div>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered" id="medis-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Tanggal Periksa</th>
                        <th>Jenis Kelamin</th>
                        <th>Umur</th>
                        <th>Keluhan</th>
                        <th>Tambahan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    @push('scripts')
        <script>
            $(function() {
                $('#medis-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('medis.data') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'tanggal_periksa',
                            name: 'tanggal_periksa',
                            render: function(data) {
                                if (!data) return '';
                                // ambil YYYY-MM-DD di awal string agar aman dari timezone
                                const m = String(data).match(/^(\d{4})-(\d{2})-(\d{2})/);
                                if (!m) return data;
                                return `${m[3]}/${m[2]}/${m[1]}`; // 10/09/2025
                            }
                        },
                        {
                            data: 'jenis_kelamin',
                            name: 'jenis_kelamin'
                        },
                        {
                            data: 'umur',
                            name: 'umur',
                            render: function(data, type, row) {
                                // kalau server belum kirim umur, hitung dari row.tanggal_lahir
                                if (data) return data;
                                const birth = row.tanggal_lahir;
                                if (!birth) return '';
                                const mm = String(birth).match(/^(\d{4})-(\d{2})-(\d{2})/);
                                if (!mm) return '';
                                const d = new Date(parseInt(mm[1]), parseInt(mm[2]) - 1, parseInt(mm[
                                    3]));
                                const today = new Date();
                                let age = today.getFullYear() - d.getFullYear();
                                const mth = today.getMonth() - d.getMonth();
                                if (mth < 0 || (mth === 0 && today.getDate() < d.getDate())) age--;
                                return `${age} tahun`;
                            }
                        },
                        {
                            data: 'keluhan',
                            name: 'keluhan'
                        },
                        {
                            data: 'tambahan',
                            name: 'tambahan'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });

            });
        </script>
    @endpush
</x-app-layout>
