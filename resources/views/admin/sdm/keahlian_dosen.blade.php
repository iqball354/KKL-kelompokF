@extends('admin.layout.sdm.main')
@section('title', 'Bidang Keahlian Dosen')

@section('content')
<div class="container mt-5">
    <h2>Daftar Bidang Keahlian Dosen</h2>

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

    <table class="my-table" id="keahlianTable">
        <thead>
            <tr>
                <th style="width: 20px; text-align: center;">No</th>
                <th>Nama Dosen</th>
                <th>Bidang Keahlian</th>
                <th>Jumlah Dokumen</th>
                <th>Status</th>
                <th>Dokumen</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($keahlianUnique as $item)
            @php
            $bidangGabunganStr = is_array($item->bidang_keahlian)
            ? implode(', ', $item->bidang_keahlian)
            : '-';

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
                <td>
                    <span class="badge
                        @if ($item->status_akademik == 'disetujui') bg-success
                        @elseif($item->status_akademik == 'ditolak') bg-danger
                        @else bg-secondary @endif">
                        {{ ucfirst($item->status_akademik) ?? '-' }}
                    </span>
                </td>
                <td>
                    @if ($totalDocs > 0)
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                        data-bs-target="#docModal-{{ $modalId }}">
                        <i class="fas fa-file-alt"></i>
                    </button>
                    @else
                    <span class="text-muted">Tidak ada dokumen</span>
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
                    <embed src="{{ asset('storage/' . $doc) }}" type="application/pdf"
                        width="100%" height="400px">
                    @elseif(in_array($ext, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']))
                    <iframe
                        src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/' . $doc)) }}"
                        width="100%" height="400px"></iframe>
                    @else
                    <a href="{{ asset('storage/' . $doc) }}"
                        target="_blank">{{ basename($doc) }}</a>
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
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        let table = $('#keahlianTable').DataTable({
            "order": [
                [1, "asc"]
            ],
            "pageLength": 10,
            "dom": 't<"d-flex justify-content-between mt-3"ip>',
            columnDefs: [{
                    targets: 0,
                    orderable: false
                } // Kolom nomor urut
            ]
        });

        // NOMOR URUT OTOMATIS
        table.on('order.dt search.dt draw.dt', function() {
            let i = 1;
            table.column(0, {
                search: 'applied',
                order: 'applied',
                page: 'current'
            }).nodes().each(function(cell) {
                cell.innerHTML = i++;
            });
        }).draw();


        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');
        const entriesSelect = document.getElementById('entriesSelect');

        // Search dengan tombol & enter
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === "Enter") table.search(this.value).draw();
        });
        searchButton.addEventListener('click', function() {
            table.search(searchInput.value).draw();
        });

        // Ubah jumlah entries
        entriesSelect.addEventListener('change', function() {
            table.page.len(this.value).draw();
        });

    });
</script>
@endsection