@extends('admin.layout.akademik.main')
@section('title', 'Data Kurikulum')

@section('content')
<div class="container mt-5">
    <h2>Daftar Kurikulum</h2>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">

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

        <!-- Grup Search + Status di kanan -->
        <div class="d-flex align-items-center gap-3 ms-auto">

            <!-- Status -->
            <div>
                <label>
                    Status:
                    <select id="filterStatus" class="form-select d-inline-block w-auto">
                        <option value="">Semua</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </label>
            </div>

            <!-- Search -->
            <div class="input-group" style="width: 280px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari data...">
                <button class="btn btn-primary" id="searchButton" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Tombol Tambah -->
    <button class="btn btn-primary mb-3" id="addBtn">
        + Tambah Kurikulum
    </button>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- TABLE -->
    <table class="my-table table table-striped" id="kurikulumTable">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Kurikulum</th>
                <th>Tahun</th>
                <th>Program Studi</th>
                <th>Dokumen</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            @php $modalId = md5($item->id); @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->id_kurikulum }}</td>
                <td>{{ $item->tahun }}</td>
                <td>{{ $item->program_studi }}</td>
                <td>
                    @if($item->dokumen_kurikulum)
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                        data-bs-target="#docModal-{{ $modalId }}">
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
                    <!-- Tombol Edit -->
                    <button class="btn btn-warning btn-sm editBtn"
                        data-id="{{ $item->id }}"
                        data-id-kurikulum="{{ $item->id_kurikulum }}"
                        data-tahun="{{ $item->tahun }}"
                        data-prodi="{{ $item->program_studi }}"
                        data-status="{{ $item->status }}"
                        data-doc="{{ $item->dokumen_kurikulum ?? '' }}"
                        data-docname="{{ $item->dokumen_kurikulum ? basename($item->dokumen_kurikulum) : '' }}">
                        <i class="fas fa-pencil-alt"></i>
                    </button>

                    <!-- Tombol Hapus -->
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#deleteModal-{{ $item->id }}">
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
                                    Apakah Anda yakin ingin menghapus kurikulum <strong>{{ $item->id_kurikulum }}</strong>?
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('akademik.kurikulum.destroy', $item->id) }}" method="POST">
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

<!-- Modal Dokumen -->
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

<!-- Modal Tambah/Edit -->
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
                        <label>id Kurikulum</label>
                        <input type="text" name="id_kurikulum" id="id_kurikulum" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Tahun</label>
                        <input type="number" name="tahun" id="tahun" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Program Studi</label>
                        <select name="program_studi" id="program_studi" class="form-select" required>
                            <optgroup label="Fakultas Ekonomi dan Bisnis (FEB)">
                                <option value="S2 Magister Manajemen">S2 Magister Manajemen</option>
                                <option value="S1 Manajemen">S1 Manajemen</option>
                                <option value="S1 Akuntansi">S1 Akuntansi</option>
                                <option value="S1 Ekonomi Pembangunan">S1 Ekonomi Pembangunan</option>
                                <option value="D3 Keuangan dan Perbankan">D3 Keuangan dan Perbankan</option>
                            </optgroup>

                            <optgroup label="Fakultas Sains, Teknologi dan Industri (FSTI)">
                                <option value="S1 Sistem dan Teknologi Informasi">S1 Sistem dan Teknologi Informasi</option>
                                <option value="S1 Rekayasa Perangkat Lunak">S1 Rekayasa Perangkat Lunak</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Dokumen Kurikulum (PDF/Office)</label>
                        <input type="file" name="dokumen_kurikulum" id="dokumen_kurikulum" class="form-control">
                        <div id="currentDocWrapper" class="mt-2" style="display:none;">
                            <small>Dokumen saat ini: <a href="#" target="_blank" id="currentDocLink"></a></small>
                            <div>
                                <small class="text-muted">Jika tidak ingin mengganti dokumen, biarkan file kosong.</small>
                            </div>
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

        const table = $('#kurikulumTable').DataTable({
            ordering: false,
            pageLength: 10,
            dom: 't<"d-flex justify-content-between mt-3"ip>',
            language: {
                emptyTable: "Tidak ada data untuk ditampilkan"
            }
        });

        // Nomor otomatis
        table.on('order.dt search.dt', function() {
            table.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();

        // Search
        document.getElementById('searchButton').addEventListener('click', () =>
            table.search(document.getElementById('searchInput').value).draw()
        );
        document.getElementById('searchInput').addEventListener('keyup', e => {
            if (e.key === 'Enter') table.search(e.target.value).draw();
        });

        // Entries per page
        document.getElementById('entriesSelect').addEventListener('change', function() {
            table.page.len(this.value).draw();
        });

        // Filter Status
        document.getElementById('filterStatus').addEventListener('change', function() {
            table.column(5).search(this.value).draw();
        });

        const form = document.getElementById('kurikulumForm');
        const modalEl = document.getElementById('formModal');
        const bsModal = new bootstrap.Modal(modalEl);

        function resetFileInput(input) {
            try {
                input.value = null;
            } catch (err) {
                input.type = 'text';
                input.type = 'file';
            }
        }

        // Tambah
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

        // Edit
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.editBtn');
            if (!btn) return;

            document.getElementById('formTitle').innerText = 'Edit Kurikulum';
            document.getElementById('kurikulumId').value = btn.dataset.id;
            document.getElementById('id_kurikulum').value = btn.dataset.idKurikulum || '';
            document.getElementById('tahun').value = btn.dataset.tahun;
            document.getElementById('program_studi').value = btn.dataset.prodi;
            document.getElementById('status').value = btn.dataset.status;

            if (btn.dataset.doc) {
                document.getElementById('existingDocument').value = btn.dataset.doc;
                const url = '{{ asset("storage/") }}/' + btn.dataset.doc;
                const link = document.getElementById('currentDocLink');
                link.href = url;
                link.innerText = btn.dataset.docname;
                document.getElementById('currentDocWrapper').style.display = 'block';
            } else {
                document.getElementById('existingDocument').value = '';
                document.getElementById('currentDocWrapper').style.display = 'none';
            }

            resetFileInput(document.getElementById('dokumen_kurikulum'));
            form.action = '{{ route("akademik.kurikulum.update", ":id") }}'.replace(':id', btn.dataset.id);
            document.getElementById('formMethod').value = 'PUT';
            bsModal.show();
        });

    });
</script>
@endsection