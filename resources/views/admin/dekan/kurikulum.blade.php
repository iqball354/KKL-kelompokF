@extends('admin.layout.dekan.main')
@section('title', 'Data Kurikulum')

@section('content')
<div class="container mt-5">
    <h2>Daftar Kurikulum</h2>

    <!-- FORM FAKULTAS + PASSWORD (Dipindah ke Kanan) -->
    <form method="GET" class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-3">

        <!-- KOSONGKAN BAGIAN KIRI (biar form ke kanan) -->
        <div></div>

        <!-- FORM DI KANAN -->
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <label class="mb-0">Fakultas:</label>
            <select name="fakultas" class="form-select" style="width: 220px;" required>
                <option value="">Pilih Fakultas</option>
                <option value="FEB" {{ (isset($fakultas) && $fakultas == 'FEB') ? 'selected' : '' }}>
                    Fakultas Ekonomi dan Bisnis (FEB)
                </option>
                <option value="FSTI" {{ (isset($fakultas) && $fakultas == 'FSTI') ? 'selected' : '' }}>
                    Fakultas Sains, Teknologi dan Industri (FSTI)
                </option>
            </select>

            <input type="password" name="password" class="form-control" placeholder="kunci Dekan" style="width:150px;" required>

            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </div>
    </form>

    @if(isset($error))
    <div class="alert alert-danger">{{ $error }}</div>
    @endif

    <!-- FILTER BAR -->
    <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">

        <!-- KIRI -->
        <div class="d-flex align-items-center gap-2">
            <label class="mb-0">Tampilkan</label>
            <select id="entriesSelect" class="form-select d-inline-block w-auto">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span>entri</span>
        </div>

        <!-- KANAN -->
        <div class="d-flex align-items-center gap-2">
            <label class="mb-0">Status:</label>
            <select id="statusFilter" class="form-select" style="width: 130px;">
                <option value="">Semua</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>

            <div class="input-group" style="width: 280px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari data...">
                <button class="btn btn-primary" id="searchButton">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <table class="my-table table table-striped" id="kurikulumTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Kurikulum</th>
                <th>Tahun</th>
                <th>Program Studi</th>
                <th>Dokumen</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            @php $modalId = md5($item->id); @endphp
            <tr>
                <td></td>
                <td>{{ $item->id_kurikulum }}</td>
                <td>{{ $item->tahun }}</td>
                <td>{{ $item->program_studi }}</td>
                <td>
                    @if ($item->dokumen_kurikulum)
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#docModal-{{ $modalId }}">
                        <i class="fas fa-book"></i>
                    </button>
                    @else
                    <span class="text-muted">Tidak ada dokumen</span>
                    @endif
                </td>
                <td>
                    <span class="badge {{ $item->status == 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                        {{ ucfirst($item->status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Dokumen -->
@foreach ($data as $item)
@php $modalId = md5($item->id); @endphp
<div class="modal fade" id="docModal-{{ $modalId }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dokumen Kurikulum - {{ $item->program_studi }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                @if ($item->dokumen_kurikulum)
                @php $ext = strtolower(pathinfo($item->dokumen_kurikulum, PATHINFO_EXTENSION)); @endphp

                @if ($ext == 'pdf')
                <embed src="{{ asset('storage/' . $item->dokumen_kurikulum) }}" type="application/pdf" width="100%" height="500px">

                @elseif(in_array($ext, ['doc','docx','xls','xlsx','ppt','pptx']))
                <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/' . $item->dokumen_kurikulum)) }}" width="100%" height="500px"></iframe>

                @else
                <a href="{{ asset('storage/' . $item->dokumen_kurikulum) }}" target="_blank">
                    {{ basename($item->dokumen_kurikulum) }}
                </a>
                @endif

                @else
                <p>Tidak ada dokumen</p>
                @endif
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

        const table = $('#kurikulumTable').DataTable({
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
                .nodes()
                .each(function(cell) {
                    cell.innerHTML = i++;
                });
        }).draw();

        document.getElementById('searchButton').addEventListener('click', () =>
            table.search(document.getElementById('searchInput').value).draw()
        );

        document.getElementById('searchInput').addEventListener('keyup', (e) => {
            if (e.key === 'Enter') table.search(e.target.value).draw();
        });

        $('#statusFilter').on('change', function() {
            table.column(5).search($(this).val()).draw();
        });

        document.getElementById('entriesSelect').addEventListener('change', function() {
            table.page.len(this.value).draw();
        });

    });
</script>
@endsection