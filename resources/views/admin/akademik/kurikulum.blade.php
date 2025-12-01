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
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#formModal" id="addBtn">
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
                        data-bs-toggle="modal"
                        data-bs-target="#formModal">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <form action="{{ route('akademik.kurikulum.destroy', $item->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
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
                        <input type="file" name="dokumen_kurikulum" class="form-control">
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
        // ---------------- DataTable ----------------
        const table = $('#kurikulumTable').DataTable({
            "order": [
                [1, "desc"]
            ],
            "pageLength": 10,
            "dom": 't<"d-flex justify-content-between mt-3"ip>',
        });

        // Search & entries
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');
        const entriesSelect = document.getElementById('entriesSelect');

        searchButton.addEventListener('click', () => table.search(searchInput.value).draw());
        searchInput.addEventListener('keyup', (e) => {
            if (e.key === 'Enter') table.search(searchInput.value).draw();
        });
        entriesSelect.addEventListener('change', function() {
            table.page.len(this.value).draw();
        });

        // ---------------- Modal Tambah/Edit ----------------
        const formModalEl = document.getElementById('formModal');
        const form = document.getElementById('kurikulumForm');

        // Mode Tambah
        document.getElementById('addBtn').addEventListener('click', () => {
            form.reset();
            document.getElementById('formTitle').innerText = 'Tambah Kurikulum';
            form.action = '{{ route("akademik.kurikulum.store") }}';
            document.getElementById('kurikulumId').value = '';
            document.getElementById('formMethod').value = 'POST';
        });

        // Mode Edit
        document.querySelectorAll('.editBtn').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                const kode = btn.getAttribute('data-kode');
                const tahun = btn.getAttribute('data-tahun');
                const prodi = btn.getAttribute('data-prodi');
                const status = btn.getAttribute('data-status');

                document.getElementById('formTitle').innerText = 'Edit Kurikulum';
                document.getElementById('kurikulumId').value = id;
                document.getElementById('kode_identitas').value = kode;
                document.getElementById('tahun').value = tahun;
                document.getElementById('program_studi').value = prodi;
                document.getElementById('status').value = status;

                form.action = '{{ route("akademik.kurikulum.update", ":id") }}'.replace(':id', id);
                document.getElementById('formMethod').value = 'PUT';
            });
        });
    });
</script>
@endsection