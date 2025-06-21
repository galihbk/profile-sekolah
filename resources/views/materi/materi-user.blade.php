<x-app-layout>
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-lg-6 col-xl-6">
                    <a href="javascript:void(0)" class="btn btn-primary mb-3 mb-lg-0" data-bs-toggle="modal" data-bs-target="#tambahMateriModal">
                        <i class="bx bxs-plus-square"></i> Tambah Materi
                    </a>
                </div>
            </div>
        </div>
    </div>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered" id="users-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Pengajar</th>
                        <th>Nama Materi</th>
                        <th>File</th>
                        <th>Tanggal Ditambahkan</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>


    @push('scripts')
    <script>
        $(function() {
            const table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('materi.dataMateri') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'pengajar',
                        name: 'pengajar'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'file',
                        name: 'file'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                ]
            });
            $('#form-tambah-materi').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('materi.store') }}",
                    method: "POST",
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        if (res.success) {
                            $('#tambahMateriModal').modal('hide');
                            $('#form-tambah-materi')[0].reset();
                            $('#form-errors').html('');
                            table.ajax.reload();
                            alert(res.message);
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        let html = '<ul>';
                        $.each(errors, function(key, value) {
                            html += `<li>${value}</li>`;
                        });
                        html += '</ul>';
                        $('#form-errors').html(html);
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>