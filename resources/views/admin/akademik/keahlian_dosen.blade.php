@extends('admin.layout.akademik.main')
@section('title', 'Validasi Data Bidang Keahlian Dosen')

@section('content')
<div class="container mt-5">
    <h2>Validasi Keahlian Dosen</h2>

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
    </div>
    @endif

    <!-- FILTER -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
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

        <div class="input-group" style="width:280px">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari data...">
            <button class="btn btn-primary" id="searchButton">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>

    @php
    // Gabungkan data per dosen
    $keahlianUnique = collect($keahlian)
    ->groupBy('nama_dosen')
    ->map(function ($group) {
    $first = $group->first();
    $first->bidang_keahlian = collect($group)
    ->pluck('bidang_keahlian')
    ->flatten()
    ->unique()
    ->toArray();

    foreach (['dokumen_sertifikat','dokumen_lainnya','dokumen_pendidikan','link'] as $type) {
    $first->$type = collect($group)->pluck($type)->flatten()->filter()->toArray();
    $first->{'deskripsi_'.$type} = collect($group)->pluck('deskripsi_'.$type)->flatten()->toArray();
    $first->{'tahun_'.$type} = collect($group)->pluck('tahun_'.$type)->flatten()->toArray();
    }
    return $first;
    });
    @endphp

    <!-- TABEL -->
    <table class="my-table table table-striped" id="keahlianTable">
        <thead>
            <tr>
                <th style="width:20px;text-align:center;">No</th>
                <th>Nama Dosen</th>
                <th>Bidang Keahlian</th>
                <th>Dokumen Pendukung</th>
                <th>Status</th>
                <th style="width:160px;text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($keahlianUnique as $item)
            @php
            $totalDocs =
            count($item->dokumen_sertifikat ?? []) +
            count($item->dokumen_lainnya ?? []) +
            count($item->dokumen_pendidikan ?? []) +
            count($item->link ?? []);

            $modalId = md5($item->nama_dosen);

            $badgeClass = match(strtolower($item->status_akademik ?? '')) {
            'disetujui' => 'bg-success',
            'ditolak' => 'bg-danger',
            default => 'bg-secondary',
            };
            @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $item->nama_dosen }}</td>
                <td>{{ implode(', ', (array)$item->bidang_keahlian) }}</td>

                <td class="text-center">
                    @if($totalDocs)
                    <button class="btn btn-secondary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#docModal-{{ $modalId }}">
                        <i class="fas fa-file-alt"></i> {{ $totalDocs }}
                    </button>
                    @else
                    -
                    @endif
                </td>

                <td class="text-center">
                    <span class="badge {{ $badgeClass }}">
                        {{ ucfirst($item->status_akademik ?? '-') }}
                    </span>
                </td>

                <td class="text-center">
                    {{-- Tombol view (mata) dihapus --}}
                    @if($item->status_akademik !== 'disetujui')
                    <button class="btn btn-success btn-sm approveBtn"
                        data-id="{{ $item->id }}"
                        data-nama="{{ $item->nama_dosen }}">
                        <i class="fas fa-check"></i>
                    </button>
                    @endif

                    @if($item->status_akademik !== 'ditolak')
                    <button class="btn btn-danger btn-sm rejectBtn"
                        data-id="{{ $item->id }}"
                        data-nama="{{ $item->nama_dosen }}">
                        <i class="fas fa-times"></i>
                    </button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

{{-- ================= MODAL DOKUMEN (DISAMAKAN DENGAN DOSEN) ================= --}}
@foreach ($keahlianUnique as $item)
@php
$modalId = md5($item->nama_dosen);
@endphp

<div class="modal fade" id="docModal-{{ $modalId }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dokumen Pendukung - {{ $item->nama_dosen }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                @foreach(['sertifikat','lainnya','pendidikan','link'] as $type)
                @php
                $docs = $type === 'link'
                ? ($item->link ?? [])
                : ($item->{'dokumen_'.$type} ?? []);

                $deskripsi = $item->{'deskripsi_'.$type} ?? [];
                $tahun = $item->{'tahun_'.$type} ?? [];
                @endphp

                @if(count($docs))
                <h6 class="mt-3 mb-2">{{ ucfirst($type) }}</h6>

                <div style="display:flex; flex-wrap:wrap; gap:15px;">
                    @foreach($docs as $i => $doc)
                    <div style="width:220px; border:1px solid #dee2e6; border-radius:6px; padding:8px; display:flex; flex-direction:column; align-items:center;">

                        @if($type === 'link')
                        @php
                        $host = parse_url($doc, PHP_URL_HOST);
                        $logo = 'https://www.google.com/s2/favicons?sz=64&domain=' . $host;
                        @endphp
                        <a href="{{ $doc }}" target="_blank" style="text-align:center;">
                            <img src="{{ $logo }}" style="width:32px;height:32px;">
                            <div><small>{{ $deskripsi[$i] ?? '-' }}</small></div>
                        </a>
                        @else
                        <a href="{{ asset('storage/'.$doc) }}" target="_blank" style="text-align:center;">
                            <embed src="{{ asset('storage/'.$doc) }}"
                                type="application/pdf"
                                width="180px"
                                height="180px"
                                style="margin-bottom:5px;">
                            <div><small>{{ $deskripsi[$i] ?? '-' }}</small></div>
                            <div><small>{{ $tahun[$i] ?? '-' }}</small></div>
                        </a>
                        @endif

                    </div>
                    @endforeach
                </div>
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

{{-- ================= MODAL APPROVE ================= --}}
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="approveForm" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Setujui Keahlian</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>
                    Apakah Anda yakin ingin menyetujui keahlian dosen
                    <strong id="approveNama"></strong>?
                </p>
                <div class="mb-2">
                    <label>Alasan (opsional)</label>
                    <textarea name="alasan_validasi" class="form-control" rows="2"></textarea>
                </div>
                <input type="hidden" name="status_akademik" value="disetujui">
            </div>
            <div class="modal-footer">
                <button class="btn btn-success">Setujui</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL REJECT ================= --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="rejectForm" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Tolak Keahlian</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>
                    Apakah Anda yakin ingin menolak keahlian dosen
                    <strong id="rejectNama"></strong>?
                </p>
                <div class="mb-2">
                    <label>Alasan Penolakan</label>
                    <textarea name="alasan_validasi" class="form-control" rows="2" required></textarea>
                </div>
                <input type="hidden" name="status_akademik" value="ditolak">
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger">Tolak</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        let table = $('#keahlianTable').DataTable({
            ordering: false,
            pageLength: 10,
            dom: 't<"d-flex justify-content-between mt-3"ip>',
            language: {
                emptyTable: "Tidak ada data untuk ditampilkan"
            }
        });

        $('#searchButton').click(() => table.search($('#searchInput').val()).draw());
        $('#entriesSelect').change(e => table.page.len(e.target.value).draw());

        $('.approveBtn').click(function() {
            $('#approveForm').attr('action', `/akademik/keahlian-dosen/${this.dataset.id}/validasi`);
            $('#approveNama').text(this.dataset.nama);
            new bootstrap.Modal(document.getElementById('approveModal')).show();
        });

        $('.rejectBtn').click(function() {
            $('#rejectForm').attr('action', `/akademik/keahlian-dosen/${this.dataset.id}/validasi`);
            $('#rejectNama').text(this.dataset.nama);
            new bootstrap.Modal(document.getElementById('rejectModal')).show();
        });

    });
</script>
@endsection