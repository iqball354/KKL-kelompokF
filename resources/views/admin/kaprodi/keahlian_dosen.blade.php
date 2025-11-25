@extends('admin.layout.kaprodi.main')
@section('title', 'Bidang Keahlian Dosen')

@section('content')
<div class="container mt-5">
    <h5>Manajemen Bidang Keahlian Dosen</h5>

    <!-- Search input -->
    <div class="mb-3 d-flex justify-content-end">
        <input type="text" id="searchInput" class="form-control w-25 me-2" placeholder="Cari data...">
        <button class="btn btn-primary" id="searchButton">Cari Data</button>
    </div>

    @php
    // Gabungkan keahlian berdasarkan nama dosen menggunakan Collection
    $keahlianUnique = collect($keahlian)
    ->groupBy('nama_dosen')
    ->map(function ($group) {
    $first = $group->first();
    $first->bidang_keahlian = collect($group)->pluck('bidang_keahlian')->flatten()->unique()->toArray();
    foreach(['dokumen_sertifikat','dokumen_lainnya','dokumen_pendidikan','link'] as $docType){
    $first->$docType = collect($group)->pluck($docType)->flatten()->filter()->toArray();
    }
    return $first;
    });
    @endphp

    <table class="my-table" id="keahlianTable">
        <thead>
            <tr>
                <th>Nama Dosen</th>
                <th>Bidang Keahlian</th>
                <th>Jumlah Dokumen</th>
                <th>Status Kaprodi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($keahlianUnique as $item)
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
                <td>{{ $item->nama_dosen ?? 'Nama tidak tersedia' }}</td>
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
                        @if($item->status_kaprodi=='disetujui') bg-success
                        @elseif($item->status_kaprodi=='ditolak') bg-danger
                        @else bg-secondary @endif">
                        {{ ucfirst($item->status_kaprodi) ?? '-' }}
                    </span>
                </td>
                <td>
                    @if($totalDocs > 0)
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#docModal-{{ $modalId }}">
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

<!-- MODAL DOKUMEN GABUNGAN -->
@foreach($keahlianUnique as $item)
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
                @foreach($allDocs as $type => $docs)
                @if(count($docs))
                <h6 class="mt-3">{{ $type }}</h6>
                @foreach($docs as $i => $doc)
                <div class="card mb-2 p-2">
                    @if($type == 'Link')
                    <a href="{{ $doc }}" target="_blank">{{ $doc }}</a>
                    @else
                    @php $ext = strtolower(pathinfo($doc, PATHINFO_EXTENSION)); @endphp
                    @if($ext == 'pdf')
                    <embed src="{{ asset('storage/'.$doc) }}" type="application/pdf" width="100%" height="400px">
                    @elseif(in_array($ext, ['doc','docx','xls','xlsx','ppt','pptx']))
                    <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/'.$doc)) }}" width="100%" height="400px" frameborder="0"></iframe>
                    @else
                    <a href="{{ asset('storage/'.$doc) }}" target="_blank">{{ basename($doc) }}</a>
                    @endif
                    <div>Deskripsi: {{ $item->{'deskripsi_'.strtolower($type)}[$i] ?? '-' }}</div>
                    <div>Tahun: {{ $item->{'tahun_'.strtolower($type)}[$i] ?? '-' }}</div>
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
        $('#keahlianTable').DataTable({
            "order": [
                [0, "asc"]
            ],
            "pageLength": 10
        });

        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');
        const table = document.getElementById('keahlianTable');
        const tbody = table.getElementsByTagName('tbody')[0];
        const rows = tbody.getElementsByTagName('tr');

        function filterTable() {
            const filter = searchInput.value.toLowerCase().trim();
            for (let i = 0; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName('td');
                let rowText = '';
                for (let j = 0; j < cells.length; j++) {
                    rowText += cells[j].textContent.toLowerCase() + ' ';
                }
                rows[i].style.display = rowText.includes(filter) ? '' : 'none';
            }
        }

        searchInput.addEventListener('keyup', filterTable);
        searchButton.addEventListener('click', filterTable);
    });
</script>
@endsection