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
                        <th>Nama Materi</th>
                        <th>File</th>
                        <th>Tanggal Ditambahkan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- Modal Tambah Materi -->
    <div class="modal fade" id="tambahMateriModal" tabindex="-1" aria-labelledby="tambahMateriLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-tambah-materi" class="modal-content" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahMateriLabel">Tambah Materi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Materi</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">Upload File</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div id="form-errors" class="text-danger small"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>


    @push('scripts')
    <script>
        $(function() {
            const table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('materi.data') }}",
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
                        data: 'file',
                        name: 'file'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
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