@extends('admin.layout.dekan.main')
@section('title', 'Data Konsentrasi Jurusan')

@section('content')
<div class="container mt-5">
    <h2>Daftar Konsentrasi Jurusan</h2>

    <!-- FORM FAKULTAS + PASSWORD -->
    <form method="GET" class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div></div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <label class="mb-0">Fakultas:</label>
            <select name="fakultas" class="form-select" style="width: 220px;" required>
                <option value="">Pilih Fakultas</option>
                <option value="FEB" {{ (isset($fakultas) && $fakultas == 'FEB') ? 'selected' : '' }}>Fakultas Ekonomi dan Bisnis (FEB)</option>
                <option value="FSTI" {{ (isset($fakultas) && $fakultas == 'FSTI') ? 'selected' : '' }}>Fakultas Sains, Teknologi dan Industri (FSTI)</option>
            </select>

            <input type="password" name="password" class="form-control" placeholder="Kunci Dekan" style="width:150px;" required>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </div>
    </form>

    @if(isset($error))
    <div class="alert alert-danger">{{ $error }}</div>
    @endif

    @php $isUnlocked = isset($fakultas) && empty($error); @endphp
    <input type="hidden" id="isUnlockedFlag" value="{{ $isUnlocked ? 1 : 0 }}">

    <!-- FILTER + SEARCH -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <div class="d-flex align-items-center gap-2">
            <label class="mb-0">
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

        <div class="d-flex align-items-center gap-2">
            <select id="statusFilter" class="form-select w-auto">
                <option value="">Semua</option>
                <option value="disetujui">Disetujui</option>
                <option value="menunggu">Menunggu</option>
                <option value="ditolak">Ditolak</option>
            </select>

            <input type="text" id="searchInput" class="form-control" placeholder="Cari data...">
            <button class="btn btn-primary" id="searchButton">
                <i class="fas fa-search"></i>
            </button>
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
            @foreach ($data as $item)
            @php
            // Aman untuk array atau string JSON
            $subs = is_array($item->sub_konsentrasi)
            ? $item->sub_konsentrasi
            : (is_string($item->sub_konsentrasi) ? json_decode($item->sub_konsentrasi, true) : []);
            @endphp
            <tr>
                <td></td>
                <td>
                    <strong>{{ $item->kurikulum->kurikulum ?? '-' }}</strong><br>
                    Prodi: {{ $item->kurikulum->program_studi ?? '-' }}
                </td>
                <td>{{ $item->kode_konsentrasi }}</td>
                <td>{{ $item->nama_konsentrasi }}</td>

                <td>
                    @if(count($subs))
                    @foreach($subs as $i => $sub)
                    <div>{{ $i + 1 }}) {{ $sub }}</div>
                    @endforeach
                    @else
                    -
                    @endif
                </td>

                <td>
                    @php
                    $status = $item->status_verifikasi;
                    $badgeClass = $status === 'disetujui' ? 'bg-success' : ($status === 'ditolak' ? 'bg-danger' : 'bg-secondary');
                    $statusLabel = $status === 'disetujui' ? 'Disetujui' : ($status === 'ditolak' ? 'Ditolak' : ucfirst($status ?? 'menunggu'));
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                </td>

                <td>
                    <button class="btn btn-info btn-sm viewBtn"
                        data-nama="{{ $item->nama_konsentrasi }}"
                        data-kode="{{ $item->kode_konsentrasi }}"
                        data-kurikulum="{{ $item->kurikulum->kurikulum ?? '-' }} ({{ $item->kurikulum->program_studi ?? '-' }})"
                        data-sub="{{ json_encode($subs) }}"
                        data-deskripsi="{{ $item->deskripsi ?? '' }}">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="7" class="text-center text-muted">
                    Masukkan fakultas dan kunci untuk menampilkan data
                </td>
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
                <div id="viewSub"></div>

                <p><strong>Deskripsi:</strong> <span id="viewDeskripsi"></span></p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('isUnlockedFlag').value !== '1') return;

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
                page: 'current'
            }).nodes().each(cell => cell.innerHTML = i++);
        }).draw();

        document.getElementById('entriesSelect').addEventListener('change', e => {
            table.page.len(e.target.value).draw();
        });

        document.getElementById('statusFilter').addEventListener('change', e => {
            table.column(5).search(e.target.value.toLowerCase()).draw();
        });

        document.getElementById('searchButton').addEventListener('click', () => {
            table.search(document.getElementById('searchInput').value).draw();
        });

        document.getElementById('searchInput').addEventListener('keyup', e => {
            if (e.key === 'Enter') table.search(e.target.value).draw();
        });

        // VIEW MODAL
        document.addEventListener('click', e => {
            const btn = e.target.closest('.viewBtn');
            if (!btn) return;

            document.getElementById('viewKurikulum').innerText = btn.dataset.kurikulum;
            document.getElementById('viewKode').innerText = btn.dataset.kode;
            document.getElementById('viewNama').innerText = btn.dataset.nama;
            document.getElementById('viewDeskripsi').innerText = btn.dataset.deskripsi || '-';

            const container = document.getElementById('viewSub');
            container.innerHTML = '';

            const subs = JSON.parse(btn.dataset.sub || '[]');
            if (subs.length) {
                subs.forEach((s, i) => {
                    const div = document.createElement('div');
                    div.innerText = (i + 1) + ') ' + s;
                    container.appendChild(div);
                });
            } else {
                container.innerText = '-';
            }

            new bootstrap.Modal(document.getElementById('viewModal')).show();
        });
    });
</script>
@endsection