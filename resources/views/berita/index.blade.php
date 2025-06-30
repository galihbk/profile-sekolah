
<x-app-layout>
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-lg-6 col-xl-6">
                    <a href="javascript:void(0)" class="btn btn-primary mb-3 mb-lg-0" data-bs-toggle="modal" data-bs-target="#tambahMateriModal" id="btn-tambah">
                        <i class="bx bxs-plus-square"></i> Tambah Berita
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
            <table class="table table-bordered" id="berita-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judul Berita / Informasi</th>
                        <th>Tanggal Publish</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal Tambah/Edit -->
    <div class="modal fade" id="tambahMateriModal" tabindex="-1" aria-labelledby="tambahMateriLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="form-tambah-materi" class="modal-content" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahMateriLabel">Tambah Berita Atau Informasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="berita_id">
                    <div class="mb-3">
                        <label class="form-label">Judul Berita / Informasi</label>
                        <input type="text" name="judul" id="judul" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" id="image" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
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
         CKEDITOR.replace('deskripsi', {
                toolbar: [{
                        name: 'basicstyles',
                        items: ['Bold', 'Italic']
                    },
                    {
                        name: 'paragraph',
                        items: ['NumberedList', 'BulletedList']
                    },
                    {
                        name: 'links',
                        items: ['Link', 'Unlink']
                    },
                    {
                        name: 'undo',
                        items: ['Undo', 'Redo']
                    }
                ],
                removePlugins: 'elementspath',
                resize_enabled: false
            });

        $(function () {
            const table = $('#berita-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('berita.data') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'judul', name: 'judul' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            $('#btn-tambah').on('click', function () {
                $('#form-tambah-materi')[0].reset();
                CKEDITOR.instances['deskripsi'].setData('');
                $('#form-errors').html('');
                $('#berita_id').val('');
                $('#form-tambah-materi').attr('action', "{{ route('berita.store') }}");
                $('input[name="_method"]').remove();
            });

            $('#form-tambah-materi').on('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.set('deskripsi', CKEDITOR.instances['deskripsi'].getData());

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).find('input[name="_method"]').val() || 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        $('#tambahMateriModal').modal('hide');
                        table.ajax.reload();
                        alert(res.message || 'Berhasil disimpan');
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorList = Object.values(errors).map(e => `<li>${e[0]}</li>`).join('');
                            $('#form-errors').html(`<ul>${errorList}</ul>`);
                        } else {
                            $('#form-errors').text('Terjadi kesalahan!');
                        }
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
