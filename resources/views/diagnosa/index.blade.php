<x-app-layout>
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-lg-6 col-xl-6">
                    <button class="btn btn-primary mb-3 mb-lg-0" data-bs-toggle="modal" data-bs-target="#addDiagnosaModal">
                        <i class="bx bxs-plus-square"></i> Tambah Diagnosa
                    </button>
                </div>
            </div>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered" id="users-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Diagnosa</th>
                        <th>Anjuran</th>
                        <th>Pantangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="modal fade" id="addDiagnosaModal" tabindex="-1" aria-labelledby="addDiagnosaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="formTambahDiagnosa" method="POST" action="{{ route('diagnosa.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDiagnosaModalLabel">Tambah Diagnosa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Error Validation -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="diagnosa" class="form-label">Diagnosa</label>
                            <input type="text" class="form-control" name="diagnosa" value="{{ old('diagnosa') }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="anjuran" class="form-label">Anjuran</label>
                            <textarea class="form-control" name="anjuran" required>{{ old('anjuran') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="pantangan" class="form-label">Pantangan</label>
                            <textarea class="form-control" name="pantangan" required>{{ old('pantangan') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(function() {
                $(document).on('click', '.btn-hapus', function() {
                    var id = $(this).data('id');
                    if (confirm("Yakin ingin menghapus diagnosa ini?")) {
                        $.ajax({
                            url: "/diagnosa/" + id,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(res) {
                                alert(res.success);
                                $('#users-table').DataTable().ajax.reload();
                            },
                            error: function(xhr) {
                                alert("Gagal menghapus data!");
                            }
                        });
                    }
                });
                $(document).on('click', '.btn-edit', function() {
                    const id = $(this).data('id');
                    $('input[name="diagnosa"]').val($(this).data('diagnosa'));
                    $('textarea[name="anjuran"]').val($(this).data('anjuran'));
                    $('textarea[name="pantangan"]').val($(this).data('pantangan'));

                    // bersihkan _method lama agar tidak dobel
                    $('#formTambahDiagnosa input[name="_method"]').remove();

                    // set action ke route update + spoof PUT
                    $('#formTambahDiagnosa').attr('action', '/diagnosa/' + id);
                    $('#formTambahDiagnosa').append('<input type="hidden" name="_method" value="PUT">');

                    new bootstrap.Modal('#addDiagnosaModal').show();
                });

                // reset ke mode tambah saat modal ditutup
                $('#addDiagnosaModal').on('hidden.bs.modal', function() {
                    $('#formTambahDiagnosa')[0].reset();
                    $('#formTambahDiagnosa').attr('action', "{{ route('diagnosa.store') }}");
                    $('#formTambahDiagnosa input[name="_method"]').remove();
                });
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('diagnosa.data') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'diagnosa',
                            name: 'diagnosa'
                        },
                        {
                            data: 'anjuran',
                            name: 'anjuran'
                        },
                        {
                            data: 'pantangan',
                            name: 'pantangan'
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
            @if ($errors->any())
                $(document).ready(function() {
                    var addDiagnosaModal = new bootstrap.Modal(document.getElementById('addDiagnosaModal'));
                    addDiagnosaModal.show();
                });
            @endif
        </script>
    @endpush
</x-app-layout>
