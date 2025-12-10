@extends('admin.layout.kaprodi.main')
@section('title', 'Data Konsentrasi Jurusan')

@section('content')
<div class="container mt-5">
    <h2>Daftar Konsentrasi Jurusan</h2>

    <!-- FILTER PROGRAM STUDI + PASSWORD -->
    <form method="GET" class="mb-3 d-flex gap-2 align-items-center flex-wrap">
        <label class="mb-0">Program Studi:</label>
        <select name="prodi" class="form-select" style="width: 220px;">
            <option value="">Semua Program Studi</option>
            <optgroup label="Fakultas FEB">
                <option value="S2 Magister Manajemen" {{ ($queryProdi ?? '') == 'S2 Magister Manajemen' ? 'selected' : '' }}>S2 Magister Manajemen</option>
                <option value="S1 Manajemen" {{ ($queryProdi ?? '') == 'S1 Manajemen' ? 'selected' : '' }}>S1 Manajemen</option>
                <option value="S1 Akuntansi" {{ ($queryProdi ?? '') == 'S1 Akuntansi' ? 'selected' : '' }}>S1 Akuntansi</option>
                <option value="S1 Ekonomi Pembangunan" {{ ($queryProdi ?? '') == 'S1 Ekonomi Pembangunan' ? 'selected' : '' }}>S1 Ekonomi Pembangunan</option>
                <option value="D3 Keuangan dan Perbankan" {{ ($queryProdi ?? '') == 'D3 Keuangan dan Perbankan' ? 'selected' : '' }}>D3 Keuangan dan Perbankan</option>
            </optgroup>
            <optgroup label="Fakultas FSTI">
                <option value="S1 Sistem dan Teknologi Informasi (STI)" {{ ($queryProdi ?? '') == 'S1 Sistem dan Teknologi Informasi (STI)' ? 'selected' : '' }}>S1 Sistem dan Teknologi Informasi (STI)</option>
                <option value="S1 Rekayasa Perangkat Lunak (RPL)" {{ ($queryProdi ?? '') == 'S1 Rekayasa Perangkat Lunak (RPL)' ? 'selected' : '' }}>S1 Rekayasa Perangkat Lunak (RPL)</option>
            </optgroup>
        </select>

        <input type="password" name="password" class="form-control" placeholder="kunci" style="width: 150px;">
        <button type="submit" class="btn btn-primary">Tampilkan</button>
    </form>

    @if(isset($error))
    <div class="alert alert-danger">{{ $error }}</div>
    @endif

    @php $isUnlocked = isset($kurikulums) && $kurikulums->count() > 0; @endphp
    <input type="hidden" id="isUnlockedFlag" value="{{ $isUnlocked ? 1 : 0 }}">

    <!-- BUTTON TAMBAH + FILTER + SEARCH -->
    <div class="d-flex justify-content-between mb-3 flex-wrap">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal" @if(!$isUnlocked) disabled @endif>+ Tambah Konsentrasi</button>

        <div class="d-flex gap-2 flex-wrap">
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

            <label>
                Status:
                <select id="statusFilter" class="form-select d-inline-block w-auto">
                    <option value="">Semua Status</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="pending">Pending</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </label>

            <div class="input-group" style="width: 250px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari data...">
                <button class="btn btn-secondary" id="searchButton" type="button">Cari</button>
            </div>
        </div>
    </div>

    <!-- TABEL KONSENTRASI -->
    <table class="my-table table table-striped" id="konsentrasiTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Kurikulum</th>
                <th>Kode</th>
                <th>Nama Konsentrasi</th>
                <th>Sub Konsentrasi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if($isUnlocked)
            @foreach ($data as $item)
            @php $modalId = md5($item->id); @endphp
            <tr>
                <td></td>
                <td>{{ $item->kurikulum->kurikulum ?? '-' }} ({{ $item->kurikulum->program_studi ?? '-' }})</td>
                <td>{{ $item->kode_konsentrasi }}</td>
                <td>{{ $item->nama_konsentrasi }}</td>
                <td>{{ $item->sub_konsentrasi ? implode(', ', $item->sub_konsentrasi) : '-' }}</td>
                <td>
                    <span class="badge {{ $item->status_verifikasi == 'disetujui' ? 'bg-success' : ($item->status_verifikasi == 'ditolak' ? 'bg-danger' : 'bg-secondary') }}">
                        {{ ucfirst($item->status_verifikasi) }}
                    </span>
                </td>
                <td>
                    <!-- Tombol Lihat -->
                    <button class="btn btn-info btn-sm viewBtn"
                        data-nama="{{ $item->nama_konsentrasi }}"
                        data-kode="{{ $item->kode_konsentrasi }}"
                        data-kurikulum="{{ $item->kurikulum->kurikulum ?? '' }} ({{ $item->kurikulum->program_studi ?? '' }})"
                        data-sub="{{ json_encode($item->sub_konsentrasi) }}"
                        data-deskripsi="{{ $item->deskripsi ?? '' }}">
                        <i class="fas fa-eye"></i>
                    </button>

                    <!-- Tombol Edit -->
                    <button class="btn btn-warning btn-sm editBtn"
                        data-id="{{ $item->id }}"
                        data-kurikulum="{{ $item->kurikulum->id ?? '' }}"
                        data-kode="{{ $item->kode_konsentrasi }}"
                        data-nama="{{ $item->nama_konsentrasi }}"
                        data-sub="{{ json_encode($item->sub_konsentrasi) }}"
                        data-deskripsi="{{ $item->deskripsi ?? '' }}">
                        <i class="fas fa-pencil-alt"></i>
                    </button>

                    <!-- Tombol Hapus -->
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#deleteModal-{{ $modalId }}">
                        <i class="fas fa-trash"></i>
                    </button>

                    <!-- Modal Hapus -->
                    <div class="modal fade" id="deleteModal-{{ $modalId }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus konsentrasi <strong>{{ $item->nama_konsentrasi }}</strong>?
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('kaprodi.konsentrasi.destroy', $item->id) }}" method="POST">
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
            @else
            <tr>
                <td></td>
                <td colspan="5" class="text-center">Masukkan program studi dan kunci untuk menampilkan data</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- MODAL CREATE / EDIT -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah / Edit Konsentrasi Jurusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($isUnlocked)
                <form method="POST" action="{{ route('kaprodi.konsentrasi.store') }}" id="konsentrasiForm">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="id" id="konsentrasiId">

                    <div class="mb-3">
                        <label>Kurikulum</label>
                        <select name="kurikulum_id" id="kurikulum_id" class="form-select" required>
                            <option value="">Pilih Kurikulum</option>
                            @foreach($kurikulums as $kurikulum)
                            <option value="{{ $kurikulum->id }}">{{ $kurikulum->kurikulum }} - {{ $kurikulum->program_studi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Kode Konsentrasi</label>
                        <input type="text" name="kode_konsentrasi" id="kode_konsentrasi" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Nama Konsentrasi</label>
                        <input type="text" name="nama_konsentrasi" id="nama_konsentrasi" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" placeholder="Deskripsi konsentrasi"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Sub Konsentrasi</label>
                        <div id="subContainer">
                            <div class="input-group mb-2">
                                <input type="text" name="sub_konsentrasi[]" class="form-control" placeholder="Sub Konsentrasi">
                                <button type="button" class="btn btn-danger removeSub">-</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" id="addSub">+ Tambah Sub</button>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
                @else
                <div class="alert alert-info">Form Tambah Konsentrasi hanya aktif jika kunci prodi sudah dimasukkan benar</div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- MODAL VIEW -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Konsentrasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Kurikulum:</strong> <span id="viewKurikulum"></span></p>
                <p><strong>Kode:</strong> <span id="viewKode"></span></p>
                <p><strong>Nama:</strong> <span id="viewNama"></span></p>
                <p><strong>Sub Konsentrasi:</strong>
                <ul id="viewSub"></ul>
                </p>
                <p><strong>Deskripsi:</strong> <span id="viewDeskripsi"></span></p>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isUnlockedInput = document.getElementById('isUnlockedFlag');
        const isUnlocked = isUnlockedInput && isUnlockedInput.value === '1';
        if (!isUnlocked) return;

        const table = $('#konsentrasiTable').DataTable({
            "order": [
                [1, "asc"]
            ],
            "pageLength": 10,
            "dom": 't<"d-flex justify-content-between mt-3"ip>',
            columnDefs: [{
                    targets: 0,
                    orderable: false
                },
                {
                    targets: [4, 5],
                    orderable: false
                }
            ]
        });

        // Nomor urut otomatis
        table.on('order.dt search.dt draw.dt', function() {
            let i = 1;
            table.column(0, {
                    search: 'applied',
                    order: 'applied',
                    page: 'current'
                })
                .nodes().each(cell => cell.innerHTML = i++);
        }).draw();

        // Show entries
        document.getElementById('entriesSelect').addEventListener('change', function() {
            table.page.len(this.value).draw();
        });

        // Filter status
        document.getElementById('statusFilter').addEventListener('change', function() {
            table.column(4).search(this.value.toLowerCase()).draw();
        });

        // Search
        document.getElementById('searchButton').addEventListener('click', () =>
            table.search(document.getElementById('searchInput').value).draw()
        );
        document.getElementById('searchInput').addEventListener('keyup', e => {
            if (e.key === 'Enter') table.search(e.target.value).draw();
        });

        // Tambah / hapus sub konsentrasi
        document.getElementById('addSub').addEventListener('click', () => {
            const container = document.getElementById('subContainer');
            const div = document.createElement('div');
            div.classList.add('input-group', 'mb-2');
            div.innerHTML = `<input type="text" name="sub_konsentrasi[]" class="form-control" placeholder="Sub Konsentrasi">
                         <button type="button" class="btn btn-danger removeSub">-</button>`;
            container.appendChild(div);
        });
        document.getElementById('subContainer').addEventListener('click', e => {
            if (e.target && e.target.classList.contains('removeSub')) e.target.parentElement.remove();
        });

        // Edit konsentrasi
        document.addEventListener('click', e => {
            const btn = e.target.closest('.editBtn');
            if (!btn) return;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('konsentrasiId').value = btn.dataset.id;
            document.getElementById('kurikulum_id').value = btn.dataset.kurikulum;
            document.getElementById('kode_konsentrasi').value = btn.dataset.kode;
            document.getElementById('nama_konsentrasi').value = btn.dataset.nama;
            document.getElementById('deskripsi').value = btn.dataset.deskripsi;

            const container = document.getElementById('subContainer');
            container.innerHTML = '';
            const subs = JSON.parse(btn.dataset.sub || '[]');
            if (subs.length) {
                subs.forEach(s => {
                    const div = document.createElement('div');
                    div.classList.add('input-group', 'mb-2');
                    div.innerHTML = `<input type="text" name="sub_konsentrasi[]" class="form-control" value="${s}">
                                 <button type="button" class="btn btn-danger removeSub">-</button>`;
                    container.appendChild(div);
                });
            } else {
                const div = document.createElement('div');
                div.classList.add('input-group', 'mb-2');
                div.innerHTML = `<input type="text" name="sub_konsentrasi[]" class="form-control" placeholder="Sub Konsentrasi">
                             <button type="button" class="btn btn-danger removeSub">-</button>`;
                container.appendChild(div);
            }

            new bootstrap.Modal(document.getElementById('createModal')).show();
        });

        // View konsentrasi
        document.addEventListener('click', e => {
            const btn = e.target.closest('.viewBtn');
            if (!btn) return;
            document.getElementById('viewKurikulum').innerText = btn.dataset.kurikulum;
            document.getElementById('viewKode').innerText = btn.dataset.kode;
            document.getElementById('viewNama').innerText = btn.dataset.nama;

            const subList = document.getElementById('viewSub');
            subList.innerHTML = '';
            const subs = JSON.parse(btn.dataset.sub || '[]');
            if (subs.length) {
                subs.forEach(s => {
                    const li = document.createElement('li');
                    li.innerText = s;
                    subList.appendChild(li);
                });
            } else {
                const li = document.createElement('li');
                li.innerText = '-';
                subList.appendChild(li);
            }

            document.getElementById('viewDeskripsi').innerText = btn.dataset.deskripsi || '-';
            new bootstrap.Modal(document.getElementById('viewModal')).show();
        });

    });
</script>
@endsection