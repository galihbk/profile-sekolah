<x-app-layout>
    @if (auth()->user()->role !== 'user')
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-xl-6">
                        <a href="{{ route('medis.create') }}" class="btn btn-primary mb-3 mb-lg-0">
                            <i class="bx bxs-plus-square"></i> Tambah Rekam Medis
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
                            name: 'tanggal_periksa'
                        },
                        {
                            data: 'jenis_kelamin',
                            name: 'jenis_kelamin'
                        },
                        {
                            data: 'umur',
                            name: 'umur'
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
