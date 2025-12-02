@extends('admin.layout.akademik.main')
@section('title', 'Data Kurikulum')

@section('content')
<div class="container mt-5">
    <h2>Daftar Kurikulum</h2>

    <div class="d-flex justify-content-between mb-3">
        <!-- Show entries -->
        <div>
            <label>
                Tampilkan
                <select id="entriesSelect" class="form-select d-inline-block w-auto">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                entri
            </label>
        </div>

        <!-- Search kanan -->
        <div class="input-group" style="width: 280px;">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari data...">
            <button class="btn btn-primary" id="searchButton">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>

    <!-- Tombol Tambah Kurikulum -->
    <button class="btn btn-primary mb-3" id="addBtn">
        + Tambah Kurikulum
    </button>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="my-table table table-striped" id="kurikulumTable">
        <thead>
            <tr>
                <th>Kode Identitas</th>
                <th>Tahun</th>
                <th>Program Studi</th>
                <th>Dokumen</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            @php $modalId = md5($item->id); @endphp
            <tr>
                <td>{{ $item->kode_identitas }}</td>
                <td>{{ $item->tahun }}</td>
                <td>{{ $item->program_studi }}</td>
                <td>
                    @if($item->dokumen_kurikulum)
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#docModal-{{ $modalId }}">
                        <i class="fas fa-book"></i>
                    </button>
                    @else
                    <span class="text-muted">Tidak ada dokumen</span>
                    @endif
                </td>
                <td>
                    <span class="badge {{ $item->status=='aktif' ? 'bg-success' : 'bg-secondary' }}">
                        {{ ucfirst($item->status) }}
                    </span>
                </td>
                <td>
                    <button class="btn btn-warning btn-sm editBtn"
                        data-id="{{ $item->id }}"
                        data-kode="{{ $item->kode_identitas }}"
                        data-tahun="{{ $item->tahun }}"
                        data-prodi="{{ $item->program_studi }}"
                        data-status="{{ $item->status }}"
                        data-doc="{{ $item->dokumen_kurikulum ?? '' }}"
                        data-docname="{{ $item->dokumen_kurikulum ? basename($item->dokumen_kurikulum) : '' }}"
                        title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <!-- Tombol hapus trigger modal -->
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $item->id }}">
                        <i class="fas fa-trash"></i>
                    </button>

                    <!-- Modal konfirmasi hapus -->
                    <div class="modal fade" id="deleteModal-{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus bidang keahlian <strong>{{ $item->nama_dosen }}</strong>?
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('keahlian.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Dokumen Kurikulum -->
@foreach($data as $item)
@php $modalId = md5($item->id); @endphp
<div class="modal fade" id="docModal-{{ $modalId }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dokumen Kurikulum - {{ $item->program_studi }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($item->dokumen_kurikulum)
                @php $ext = strtolower(pathinfo($item->dokumen_kurikulum, PATHINFO_EXTENSION)); @endphp
                @if($ext == 'pdf')
                <embed src="{{ asset('storage/'.$item->dokumen_kurikulum) }}" type="application/pdf" width="100%" height="500px">
                @elseif(in_array($ext, ['doc','docx','xls','xlsx','ppt','pptx']))
                <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/'.$item->dokumen_kurikulum)) }}" width="100%" height="500px"></iframe>
                @else
                <a href="{{ asset('storage/'.$item->dokumen_kurikulum) }}" target="_blank">{{ basename($item->dokumen_kurikulum) }}</a>
                @endif
                @else
                <p>Tidak ada dokumen</p>
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Popup Tambah/Edit Kurikulum -->
<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="kurikulumForm" method="POST" enctype="multipart/form-data" action="{{ route('akademik.kurikulum.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="kurikulumId">
                <input type="hidden" name="existing_dokumen" id="existingDocument">
                <div class="modal-header">
                    <h5 id="formTitle" class="modal-title">Tambah Kurikulum</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Kode Identitas</label>
                        <input type="text" name="kode_identitas" id="kode_identitas" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Tahun</label>
                        <input type="number" name="tahun" id="tahun" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Program Studi</label>
                        <input type="text" name="program_studi" id="program_studi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Dokumen Kurikulum (PDF/Office)</label>
                        <input type="file" name="dokumen_kurikulum" id="dokumen_kurikulum" class="form-control">
                        <div id="currentDocWrapper" class="mt-2" style="display:none;">
                            <small>Dokumen saat ini: <a href="#" target="_blank" id="currentDocLink"></a></small>
                            <div><small class="text-muted">Jika tidak ingin mengganti dokumen, biarkan file kosong.</small></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DataTable
        const table = $('#kurikulumTable').DataTable({
            "order": [[1, "desc"]],
            "pageLength": 10,
            "dom": 't<"d-flex justify-content-between mt-3"ip>',
        });

        // Search & Entries
        document.getElementById('searchButton').addEventListener('click', () =>
            table.search(document.getElementById('searchInput').value).draw()
        );

        document.getElementById('searchInput').addEventListener('keyup', (e) => {
            if (e.key === 'Enter') table.search(e.target.value).draw();
        });

        document.getElementById('entriesSelect').addEventListener('change', function() {
            table.page.len(this.value).draw();
        });

        const form = document.getElementById('kurikulumForm');
        const modalEl = document.getElementById('formModal');
        const bsModal = new bootstrap.Modal(modalEl);

        // Helper to reset file input (cross-browser)
        function resetFileInput(input) {
            try {
                input.value = null;
            } catch (err) {
                // fallback
                input.type = 'text';
                input.type = 'file';
            }
        }

        // Mode Tambah
        document.getElementById('addBtn').addEventListener('click', () => {
            form.reset();
            document.getElementById('formTitle').innerText = 'Tambah Kurikulum';
            form.action = '{{ route("akademik.kurikulum.store") }}';
            document.getElementById('kurikulumId').value = '';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('existingDocument').value = '';
            document.getElementById('currentDocWrapper').style.display = 'none';
            resetFileInput(document.getElementById('dokumen_kurikulum'));
            bsModal.show();
        });

        // Mode Edit - Event Delegation supaya tetap jalan walau DataTable merender ulang
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.editBtn');
            if (!btn) return;

            const id = btn.getAttribute('data-id');
            const kode = btn.getAttribute('data-kode');
            const tahun = btn.getAttribute('data-tahun');
            const prodi = btn.getAttribute('data-prodi');
            const status = btn.getAttribute('data-status');
            const doc = btn.getAttribute('data-doc') || '';
            const docname = btn.getAttribute('data-docname') || '';

            document.getElementById('formTitle').innerText = 'Edit Kurikulum';
            document.getElementById('kurikulumId').value = id;
            document.getElementById('kode_identitas').value = kode;
            document.getElementById('tahun').value = tahun;
            document.getElementById('program_studi').value = prodi;
            document.getElementById('status').value = status;

            // show existing document link if present
            if (doc) {
                document.getElementById('existingDocument').value = doc;
                const url = '{{ asset("storage/") }}' + '/' + doc;
                const link = document.getElementById('currentDocLink');
                link.href = url;
                link.innerText = docname;
                document.getElementById('currentDocWrapper').style.display = 'block';
            } else {
                document.getElementById('existingDocument').value = '';
                document.getElementById('currentDocWrapper').style.display = 'none';
            }

            // reset file input so old file isn't shown
            resetFileInput(document.getElementById('dokumen_kurikulum'));

            form.action = '{{ route("akademik.kurikulum.update", ":id") }}'.replace(':id', id);
            document.getElementById('formMethod').value = 'PUT';

            // show modal AFTER values are set
            bsModal.show();
        });

    });
</script>
@endsection
