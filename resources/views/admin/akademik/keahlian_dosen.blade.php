@extends('admin.layout.akademik.main')
@section('title', 'Validasi Keahlian Dosen')

@section('content')
<div class="container mt-5">
    <h2>Validasi Keahlian Dosen</h2>

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

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

    @php
    $keahlianUnique = collect($keahlian)
    ->groupBy('nama_dosen')
    ->map(function ($group) {
    $first = $group->first();
    $first->bidang_keahlian = collect($group)->pluck('bidang_keahlian')->flatten()->unique()->toArray();
    foreach (['dokumen_sertifikat', 'dokumen_lainnya', 'dokumen_pendidikan', 'link'] as $docType) {
    $first->$docType = collect($group)->pluck($docType)->flatten()->filter()->toArray();
    }
    return $first;
    });
    @endphp

    <!-- TABEL -->
    <table class="my-table table table-striped" id="keahlianTable">
        <thead>
            <tr>
                <th style="width: 20px; text-align: center;">No</th>
                <th>Nama Dosen</th>
                <th>Bidang Keahlian</th>
                <th>Jumlah Dokumen</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($keahlianUnique as $item)
            @php
            $bidangGabunganStr = is_array($item->bidang_keahlian) ? implode(', ', $item->bidang_keahlian) : '-';
            $count_sertifikat = count($item->dokumen_sertifikat ?? []);
            $count_lainnya = count($item->dokumen_lainnya ?? []);
            $count_pendidikan = count($item->dokumen_pendidikan ?? []);
            $count_link = count($item->link ?? []);
            $totalDocs = $count_sertifikat + $count_lainnya + $count_pendidikan + $count_link;
            $modalId = md5($item->nama_dosen);
            @endphp
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ $item->nama_dosen }}</td>
                <td>{{ $bidangGabunganStr }}</td>
                <td>
                    Sertifikat: {{ $count_sertifikat }}<br>
                    Lainnya: {{ $count_lainnya }}<br>
                    Pendidikan: {{ $count_pendidikan }}<br>
                    Link: {{ $count_link }}<br>
                    <strong>Total: {{ $totalDocs }}</strong>
                </td>
                <td class="text-center">
                    <span class="badge
                        @if ($item->status_akademik == 'disetujui') bg-success
                        @elseif($item->status_akademik == 'ditolak') bg-danger
                        @else bg-secondary @endif">
                        {{ ucfirst($item->status_akademik) ?? '-' }}
                    </span>
                </td>
                <td class="text-center">
                    <!-- VIEW Dokumen -->
                    @if ($totalDocs > 0)
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                        data-bs-target="#docModal-{{ $modalId }}">
                        <i class="fas fa-file-alt"></i>
                    </button>
                    @else
                    <span class="text-muted">Tidak ada dokumen</span>
                    @endif

                    <!-- APPROVE -->
                    @if($item->status_akademik != 'disetujui')
                    <button class="btn btn-success btn-sm approveBtn"
                        data-id="{{ $item->id }}"
                        data-nama="{{ $item->nama_dosen }}"
                        data-alasan="{{ $item->alasan_validasi ?? '' }}">
                        <i class="fas fa-check"></i>
                    </button>
                    @endif

                    <!-- REJECT -->
                    @if($item->status_akademik != 'ditolak')
                    <button class="btn btn-danger btn-sm rejectBtn"
                        data-id="{{ $item->id }}"
                        data-nama="{{ $item->nama_dosen }}"
                        data-alasan="{{ $item->alasan_validasi ?? '' }}">
                        <i class="fas fa-times"></i>
                    </button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Dokumen -->
@foreach ($keahlianUnique as $item)
@php
$allDocs = [
'Sertifikat' => $item->dokumen_sertifikat ?? [],
'Lainnya' => $item->dokumen_lainnya ?? [],
'Pendidikan' => $item->dokumen_pendidikan ?? [],
'Link' => $item->link ?? [],
];
$bidangGabunganStr = is_array($item->bidang_keahlian) ? implode(', ', $item->bidang_keahlian) : '-';
$modalId = md5($item->nama_dosen);
@endphp
<div class="modal fade" id="docModal-{{ $modalId }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dokumen Pendukung - {{ $bidangGabunganStr }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @foreach ($allDocs as $type => $docs)
                @if (count($docs))
                <h6 class="mt-3">{{ $type }}</h6>
                @foreach ($docs as $i => $doc)
                <div class="card mb-2 p-2">
                    @if ($type == 'Link')
                    <a href="{{ $doc }}" target="_blank">{{ $doc }}</a>
                    @else
                    @php $ext = strtolower(pathinfo($doc, PATHINFO_EXTENSION)); @endphp

                    @if ($ext == 'pdf')
                    <embed src="{{ asset('storage/' . $doc) }}" type="application/pdf" width="100%" height="400px">
                    @elseif(in_array($ext, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']))
                    <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/' . $doc)) }}" width="100%" height="400px"></iframe>
                    @else
                    <a href="{{ asset('storage/' . $doc) }}" target="_blank">{{ basename($doc) }}</a>
                    @endif

                    <div>Deskripsi: {{ $item->{'deskripsi_' . strtolower($type)}[$i] ?? '-' }}</div>
                    <div>Tahun: {{ $item->{'tahun_' . strtolower($type)}[$i] ?? '-' }}</div>
                    @endif
                </div>
                @endforeach
                @endif
                @endforeach
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal APPROVE dan REJECT -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="approveForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Setujui Keahlian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah yakin menyetujui keahlian <strong id="approveNama"></strong>?</p>
                    <div class="mb-3">
                        <label>Alasan (opsional)</label>
                        <textarea name="alasan_validasi" id="approveAlasan" class="form-control" rows="2"></textarea>
                    </div>
                    <input type="hidden" name="status_akademik" value="disetujui">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Setujui</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="rejectForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Keahlian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah yakin menolak keahlian <strong id="rejectNama"></strong>?</p>
                    <div class="mb-3">
                        <label>Alasan Penolakan</label>
                        <textarea name="alasan_validasi" id="rejectAlasan" class="form-control" rows="2" required></textarea>
                    </div>
                    <input type="hidden" name="status_akademik" value="ditolak">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Tolak</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = $('#keahlianTable').DataTable({
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
                    targets: 2,
                    orderable: false
                },
                {
                    targets: 3,
                    orderable: false
                },
                {
                    targets: 5,
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
                .nodes()
                .each(cell => cell.innerHTML = i++);
        }).draw();

        $('#entriesSelect').on('change', function() {
            table.page.len(this.value).draw();
        });
        $('#statusFilter').on('change', function() {
            table.column(4).search(this.value.toLowerCase()).draw();
        });
        $('#searchButton').on('click', () => table.search($('#searchInput').val()).draw());
        $('#searchInput').on('keyup', e => {
            if (e.key === 'Enter') table.search(e.target.value).draw();
        });

        // VIEW modal
        document.querySelectorAll('.approveBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('approveForm').action = `/akademik/keahlian-dosen/${this.dataset.id}/validasi`;
                document.getElementById('approveNama').innerText = this.dataset.nama;
                document.getElementById('approveAlasan').value = this.dataset.alasan || '';
                new bootstrap.Modal(document.getElementById('approveModal')).show();
            });
        });

        document.querySelectorAll('.rejectBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('rejectForm').action = `/akademik/keahlian-dosen/${this.dataset.id}/validasi`;
                document.getElementById('rejectNama').innerText = this.dataset.nama;
                document.getElementById('rejectAlasan').value = this.dataset.alasan || '';
                new bootstrap.Modal(document.getElementById('rejectModal')).show();
            });
        });
    });
</script>
@endsection