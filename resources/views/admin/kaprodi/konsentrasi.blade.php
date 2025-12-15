@extends('admin.layout.kaprodi.main')
@section('title', 'Data Konsentrasi Jurusan')

@section('content')
<div class="container mt-5">
    <h2>Daftar Konsentrasi Jurusan</h2>

    <!-- FILTER PROGRAM STUDI + PASSWORD (POJOK KANAN) -->
    <div class="d-flex justify-content-end mb-3">
        <form method="GET" class="d-flex gap-2 align-items-center flex-wrap">
            <label class="mb-0">Program Studi:</label>
            <select name="prodi" class="form-select" style="width: 220px;">
                <option value="">Semua Program Studi</option>
                <optgroup label="Fakultas Ekonomi dan Bisnis (FEB)">
                    <option value="S2 Magister Manajemen" {{ ($queryProdi ?? '') == 'S2 Magister Manajemen' ? 'selected' : '' }}>S2 Magister Manajemen</option>
                    <option value="S1 Manajemen" {{ ($queryProdi ?? '') == 'S1 Manajemen' ? 'selected' : '' }}>S1 Manajemen</option>
                    <option value="S1 Akuntansi" {{ ($queryProdi ?? '') == 'S1 Akuntansi' ? 'selected' : '' }}>S1 Akuntansi</option>
                    <option value="S1 Ekonomi Pembangunan" {{ ($queryProdi ?? '') == 'S1 Ekonomi Pembangunan' ? 'selected' : '' }}>S1 Ekonomi Pembangunan</option>
                    <option value="D3 Keuangan dan Perbankan" {{ ($queryProdi ?? '') == 'D3 Keuangan dan Perbankan' ? 'selected' : '' }}>D3 Keuangan dan Perbankan</option>
                </optgroup>
                <optgroup label="Fakultas FSTI">
                    <option value="S1 Sistem dan Teknologi Informasi" {{ ($queryProdi ?? '') == 'S1 Sistem dan Teknologi Informasi' ? 'selected' : '' }}>S1 Sistem dan Teknologi Informasi</option>
                    <option value="S1 Rekayasa Perangkat Lunak" {{ ($queryProdi ?? '') == 'S1 Rekayasa Perangkat Lunak' ? 'selected' : '' }}>S1 Rekayasa Perangkat Lunak</option>
                </optgroup>
            </select>

            <input type="password" name="password" class="form-control" placeholder="kunci" style="width: 150px;">
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </form>
    </div>

    @if(isset($error))
    <div class="alert alert-danger">{{ $error }}</div>
    @endif

    @php $isUnlocked = isset($kurikulums) && $kurikulums->count() > 0; @endphp
    <input type="hidden" id="isUnlockedFlag" value="{{ $isUnlocked ? 1 : 0 }}">

    <!-- FILTER TABEL: Tampilkan entri (kiri) + Status & Cari (kanan) -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <div class="d-flex align-items-center gap-2">
            <label class="d-flex align-items-center gap-1 mb-0">
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

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <label class="d-flex align-items-center gap-1 mb-0">
                Status:
                <select id="statusFilter" class="form-select d-inline-block w-auto">
                    <option value="">Semua</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="menunggu">Menunggu</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </label>

            <div class="input-group" style="width: 280px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari data...">
                <button class="btn btn-primary" id="searchButton">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- BUTTON TAMBAH KONSENTRASI (DI BAWAH FILTER) -->
    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal" @if(!$isUnlocked) disabled @endif>
            + Tambah Konsentrasi
        </button>
    </div>

    <!-- TABEL -->
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
            <tr>
                <td></td>
                <td>
                    <strong>{{ $item->kurikulum?->kurikulum }}</strong><br>
                    Prodi: {{ $item->kurikulum?->program_studi }}
                </td>
                <td>{{ $item->kode_konsentrasi }}</td>
                <td>{{ $item->nama_konsentrasi }}</td>
                <td>{{ !empty($item->sub_konsentrasi) ? implode(', ', $item->sub_konsentrasi) : '-' }}</td>

                <td>
                    <span class="badge 
                        {{ $item->status_verifikasi == 'disetujui' ? 'bg-success' : 
                           ($item->status_verifikasi == 'ditolak' ? 'bg-danger' : 'bg-secondary') }}">
                        {{ ucfirst($item->status_verifikasi) }}
                    </span>
                </td>

                <td>
                    <button class="btn btn-info btn-sm viewBtn"
                        data-nama="{{ $item->nama_konsentrasi }}"
                        data-kode="{{ $item->kode_konsentrasi }}"
                        data-kurikulum="{{ $item->kurikulum?->kurikulum }} ({{ $item->kurikulum?->program_studi }})"
                        data-sub="{{ json_encode($item->sub_konsentrasi ?? []) }}"
                        data-deskripsi="{{ $item->deskripsi ?? '' }}"
                        data-status="{{ $item->status_verifikasi }}"
                        data-alasan="{{ $item->alasan_verifikasi }}"
                        data-waktu="{{ $item->verifikasi_at ?? '' }}">
                        <i class="fas fa-eye"></i>
                    </button>

                    <button class="btn btn-warning btn-sm editBtn"
                        data-id="{{ $item->id }}"
                        data-kurikulum="{{ $item->kurikulum?->id ?? '' }}"
                        data-kode="{{ $item->kode_konsentrasi }}"
                        data-nama="{{ $item->nama_konsentrasi }}"
                        data-sub="{{ json_encode($item->sub_konsentrasi ?? []) }}"
                        data-deskripsi="{{ $item->deskripsi ?? '' }}">
                        <i class="fas fa-pencil-alt"></i>
                    </button>

                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#deleteModal-{{ md5($item->id) }}">
                        <i class="fas fa-trash"></i>
                    </button>

                    <div class="modal fade" id="deleteModal-{{ md5($item->id) }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus konsentrasi
                                    <strong>{{ $item->nama_konsentrasi }}</strong>?
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
                <td colspan="6" class="text-center">Masukkan program studi dan kunci untuk menampilkan data</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- MODAL CREATE -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Konsentrasi Jurusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($isUnlocked)
                <form method="POST" action="{{ route('kaprodi.konsentrasi.store') }}" id="createForm">
                    @csrf
                    <div class="mb-3">
                        <label>Kurikulum</label>
                        <select name="kurikulum_id" class="form-select" required>
                            <option value="">Pilih Kurikulum</option>
                            @foreach($kurikulums as $kurikulum)
                            <option value="{{ $kurikulum->id }}">
                                {{ $kurikulum->kurikulum }} ({{ $kurikulum->program_studi }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Kode Konsentrasi</label>
                        <input type="text" name="kode_konsentrasi" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Nama Konsentrasi</label>
                        <input type="text" name="nama_konsentrasi" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Sub Konsentrasi</label>
                        <div id="createSubContainer">
                            <div class="input-group mb-2">
                                <input type="text" name="sub_konsentrasi[]" class="form-control" placeholder="Sub Konsentrasi">
                                <button type="button" class="btn btn-danger removeSub">-</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" id="addCreateSub">+ Tambah Sub</button>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
                @else
                <div class="alert alert-info">
                    Form Tambah Konsentrasi hanya aktif jika kunci prodi sudah dimasukkan benar
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Konsentrasi Jurusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="editForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editKonsentrasiId" name="id">

                    <div class="mb-3">
                        <label>Kurikulum</label>
                        <select name="kurikulum_id" id="editKurikulum" class="form-select" required>
                            <option value="">Pilih Kurikulum</option>
                            @foreach($kurikulums as $kurikulum)
                            <option value="{{ $kurikulum->id }}">
                                {{ $kurikulum->kurikulum }} ({{ $kurikulum->program_studi }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Kode Konsentrasi</label>
                        <input type="text" name="kode_konsentrasi" id="editKode" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Nama Konsentrasi</label>
                        <input type="text" name="nama_konsentrasi" id="editNama" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" id="editDeskripsi" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Sub Konsentrasi</label>
                        <div id="editSubContainer"></div>
                        <button type="button" class="btn btn-secondary" id="addEditSub">+ Tambah Sub</button>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
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
                <p><strong>Sub Konsentrasi:</strong></p>
                <ul id="viewSub"></ul>
                <p><strong>Deskripsi:</strong> <span id="viewDeskripsi"></span></p>
                <p><strong>Status Verifikasi:</strong> <span id="viewStatus"></span></p>
                <p><strong>Alasan Verifikasi:</strong> <span id="viewAlasan"></span></p>
                <p><strong>Waktu Verifikasi:</strong> <span id="viewWaktu"></span></p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isUnlocked = document.getElementById('isUnlockedFlag').value === '1';
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
                    targets: [4, 5, 6],
                    orderable: false
                }
            ]
        });

        table.on('order.dt search.dt draw.dt', function() {
            let i = 1;
            table.column(0, {
                    search: 'applied',
                    order: 'applied',
                    page: 'current'
                })
                .nodes().each(cell => cell.innerHTML = i++);
        }).draw();

        document.getElementById('entriesSelect').addEventListener('change', function() {
            table.page.len(this.value).draw();
        });

        document.getElementById('statusFilter').addEventListener('change', function() {
            table.column(5).search(this.value.toLowerCase()).draw();
        });

        document.getElementById('searchButton').addEventListener('click', () =>
            table.search(document.getElementById('searchInput').value).draw()
        );

        document.getElementById('searchInput').addEventListener('keyup', e => {
            if (e.key === 'Enter') table.search(e.target.value).draw();
        });

        // Tambah Sub Create
        document.getElementById('addCreateSub').addEventListener('click', function() {
            let container = document.getElementById('createSubContainer');
            container.insertAdjacentHTML('beforeend', `
        <div class="input-group mb-2">
            <input type="text" name="sub_konsentrasi[]" class="form-control" placeholder="Sub Konsentrasi">
            <button type="button" class="btn btn-danger removeSub">-</button>
        </div>
        `);
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeSub')) e.target.closest('.input-group').remove();
        });

        // EDIT BUTTON
        document.querySelectorAll('.editBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                let id = this.dataset.id;
                let kode = this.dataset.kode;
                let nama = this.dataset.nama;
                let deskripsi = this.dataset.deskripsi;
                let kurikulumId = this.dataset.kurikulum;
                let subList = JSON.parse(this.dataset.sub);

                document.getElementById('editKonsentrasiId').value = id;
                document.getElementById('editKode').value = kode;
                document.getElementById('editNama').value = nama;
                document.getElementById('editDeskripsi').value = deskripsi;
                document.getElementById('editKurikulum').value = kurikulumId;

                let container = document.getElementById('editSubContainer');
                container.innerHTML = '';
                subList.forEach(sub => {
                    container.insertAdjacentHTML('beforeend', `
                <div class="input-group mb-2">
                    <input type="text" name="sub_konsentrasi[]" class="form-control" value="${sub}">
                    <button type="button" class="btn btn-danger removeSub">-</button>
                </div>
                `);
                });

                document.getElementById('editForm').action = `/kaprodi/konsentrasi/${id}/update`;
                new bootstrap.Modal(document.getElementById('editModal')).show();
            });
        });

        document.getElementById('addEditSub').addEventListener('click', function() {
            let container = document.getElementById('editSubContainer');
            container.insertAdjacentHTML('beforeend', `
        <div class="input-group mb-2">
            <input type="text" name="sub_konsentrasi[]" class="form-control" placeholder="Sub Konsentrasi">
            <button type="button" class="btn btn-danger removeSub">-</button>
        </div>
        `);
        });

        // VIEW BUTTON
        document.querySelectorAll('.viewBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('viewKurikulum').innerText = this.dataset.kurikulum;
                document.getElementById('viewKode').innerText = this.dataset.kode;
                document.getElementById('viewNama').innerText = this.dataset.nama;
                document.getElementById('viewDeskripsi').innerText = this.dataset.deskripsi;
                document.getElementById('viewStatus').innerText = this.dataset.status;
                document.getElementById('viewAlasan').innerText = this.dataset.alasan;
                document.getElementById('viewWaktu').innerText = this.dataset.waktu;

                let subUl = document.getElementById('viewSub');
                subUl.innerHTML = '';
                JSON.parse(this.dataset.sub).forEach(sub => {
                    let li = document.createElement('li');
                    li.textContent = sub;
                    subUl.appendChild(li);
                });

                new bootstrap.Modal(document.getElementById('viewModal')).show();
            });
        });

    });
</script>
@endsection