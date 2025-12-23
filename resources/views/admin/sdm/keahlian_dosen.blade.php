@extends('admin.layout.sdm.main')
@section('title', 'Data Bidang Keahlian Dosen')

@section('content')
<div class="container mt-5">
    <h2>Daftar Bidang Keahlian Dosen</h2>

    {{-- ALERT --}}
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
    </div>
    @endif

    {{-- FILTER --}}
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

    {{-- ================= DATA PROCESSING ================= --}}
    @php
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

    {{-- ================= TABEL ================= --}}
    <table class="my-table table table-striped" id="keahlianTable">
        <thead>
            <tr>
                <th style="width:20px;text-align:center;">No</th>
                <th>Nama Dosen</th>
                <th>Bidang Keahlian</th>
                <th>Dokumen Pendukung</th>
                <th>Status</th>
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

            $badgeClass = match(strtolower($item->status_akademik ?? '')) {
            'disetujui' => 'bg-success',
            'ditolak' => 'bg-danger',
            default => 'bg-secondary',
            };

            $modalId = md5($item->nama_dosen);
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

        $('#searchButton').on('click', function() {
            table.search($('#searchInput').val()).draw();
        });

        $('#entriesSelect').on('change', function() {
            table.page.len(this.value).draw();
        });

    });
</script>
@endsection