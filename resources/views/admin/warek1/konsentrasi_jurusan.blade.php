@extends('admin.layout.warek1.main')
@section('title', 'Data Konsentrasi Jurusan')

@section('content')
<div class="container mt-5">
    <h2>Daftar Konsentrasi Jurusan</h2>

    @if(isset($error))
    <div class="alert alert-danger">{{ $error }}</div>
    @endif

    @php $isUnlocked = true; @endphp
    <input type="hidden" id="isUnlockedFlag" value="1">

    <!-- FILTER TABEL -->
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
                    <option value="">Semua Status</option>
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
            @foreach($data as $item)
            <tr>
                <td></td>
                <td><strong>{{ $item->kurikulum->kurikulum ?? '-' }}</strong><br>
                    Prodi: {{ $item->kurikulum->program_studi ?? '-' }}
                </td>
                <td>{{ $item->kode_konsentrasi }}</td>
                <td>{{ $item->nama_konsentrasi }}</td>
                <td>{{ $item->sub_konsentrasi ? implode(', ', $item->sub_konsentrasi) : '-' }}</td>
                <td>
                    @php
                    $status = $item->status_verifikasi;
                    $badgeClass = $status === 'disetujui' ? 'bg-success' : ($status === 'ditolak' ? 'bg-danger' : 'bg-secondary');
                    $statusLabel = $status === 'disetujui' ? 'Disetujui' : ($status === 'ditolak' ? 'Ditolak' : ucfirst($status ?? 'Menunggu'));
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                </td>
                <td>
                    <!-- Tombol View -->
                    <button class="btn btn-info btn-sm viewBtn"
                        data-nama="{{ $item->nama_konsentrasi }}"
                        data-kode="{{ $item->kode_konsentrasi }}"
                        data-kurikulum="{{ $item->kurikulum->kurikulum ?? '' }} ({{ $item->kurikulum->program_studi ?? '' }})"
                        data-sub="{{ json_encode($item->sub_konsentrasi) }}"
                        data-deskripsi="{{ $item->deskripsi ?? '' }}">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="7" class="text-center">Masukkan program studi dan kunci untuk menampilkan data</td>
            </tr>
            @endif
        </tbody>
    </table>
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
            order: [
                [1, 'asc']
            ],
            pageLength: 10,
            dom: 't<"d-flex justify-content-between mt-3"ip>',
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

        document.getElementById('searchButton').addEventListener('click', () => {
            table.search(document.getElementById('searchInput').value).draw();
        });

        document.getElementById('searchInput').addEventListener('keyup', e => {
            if (e.key === 'Enter') table.search(e.target.value).draw();
        });

        // VIEW modal
        document.addEventListener('click', e => {
            const btn = e.target.closest('.viewBtn');
            if (!btn) return;

            document.getElementById('viewKurikulum').innerText = btn.dataset.kurikulum;
            document.getElementById('viewKode').innerText = btn.dataset.kode;
            document.getElementById('viewNama').innerText = btn.dataset.nama;
            document.getElementById('viewDeskripsi').innerText = btn.dataset.deskripsi || '-';

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

            new bootstrap.Modal(document.getElementById('viewModal')).show();
        });
    });
</script>
@endsection