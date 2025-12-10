@extends('admin.layout.akademik.main')
@section('title', 'Data Konsentrasi Jurusan')

@section('content')
<div class="container mt-5">
    <h2>Verifikasi Konsentrasi Jurusan</h2>

    @if(isset($error))
    <div class="alert alert-danger">{{ $error }}</div>
    @endif

    @php $isUnlocked = true; @endphp
    <input type="hidden" id="isUnlockedFlag" value="1">

    <!-- FILTER + SEARCH TANPA TAMBAH -->
    <div class="d-flex justify-content-end mb-3 flex-wrap">
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
                <th>Nama</th>
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
                    @php
                        $status = $item->status_verifikasi;
                        $badgeClass = $status === 'disetujui' ? 'bg-success' : ($status === 'ditolak' ? 'bg-danger' : 'bg-secondary');
                        $statusLabel = $status === 'disetujui' ? 'Disetujui' : ($status === 'ditolak' ? 'Ditolak' : ucfirst($status ?? 'pending'));
                    @endphp
                    <span class="badge {{ $badgeClass }}">
                        {{ $statusLabel }}
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

                    <!-- Tombol Setujui -->
                    @if($item->status_verifikasi!='disetujui')
                    <form method="POST" action="{{ route('akademik.konsentrasi.verifikasi', $item->id) }}" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status_verifikasi" value="disetujui">
                        <button type="submit" class="btn btn-success btn-sm" 
                                onclick="return confirm('Setujui data ini?')">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                    @endif

                    <!-- Tombol Tolak -->
                    @if($item->status_verifikasi!='ditolak')
                    <form method="POST" action="{{ route('akademik.konsentrasi.verifikasi', $item->id) }}" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status_verifikasi" value="ditolak">
                        <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Tolak data ini?')">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                    @endif

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

<!-- MODAL VIEW (tetap ada) -->
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