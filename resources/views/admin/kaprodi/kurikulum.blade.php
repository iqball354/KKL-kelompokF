@extends('admin.layout.kaprodi.main')
@section('title', 'Data Kurikulum')

@section('content')
<div class="container mt-5">
    <h2>Daftar Kurikulum</h2>

    <!-- KIRI ATAS: Form Prodi + Password -->
    <form method="GET" class="mb-3 d-flex gap-2 align-items-center flex-wrap">
        <label class="mb-0">Program Studi:</label>
        <select name="prodi" class="form-select" style="width: 220px;" required>
            <option value="">Pilih Program Studi</option>
            <!-- Fakultas FEB -->
            <optgroup label="Fakultas Ekonomi dan Bisnis (FEB)">
                <option value="S2 Magister Manajemen">S2 Magister Manajemen</option>
                <option value="S1 Manajemen">S1 Manajemen</option>
                <option value="S1 Akuntansi">S1 Akuntansi</option>
                <option value="S1 Ekonomi Pembangunan">S1 Ekonomi Pembangunan</option>
                <option value="D3 Keuangan dan Perbankan">D3 Keuangan dan Perbankan</option>
            </optgroup>
            <!-- Fakultas FSTI -->
            <optgroup label="Fakultas Sains, Teknologi dan Industri (FSTI)">
                <option value="S1 Sistem dan Teknologi Informasi (STI)">S1 Sistem dan Teknologi Informasi (STI)</option>
                <option value="S1 Rekayasa Perangkat Lunak (RPL)">S1 Rekayasa Perangkat Lunak (RPL)</option>
            </optgroup>
        </select>

        <input type="password" name="password" class="form-control" placeholder="kunci" style="width: 150px;" required>
        <button type="submit" class="btn btn-primary">Tampilkan</button>
    </form>

    @if(isset($error))
    <div class="alert alert-danger">{{ $error }}</div>
    @endif

    <!-- FILTER BAR: Entries kiri, Status+Search kanan -->
    <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
        <!-- KIRI: Show entries -->
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

        <!-- KANAN: Status + Search -->
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
                <td>{{ $item->kurikulum }}</td>
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
                <a href="{{ asset('storage/' . $item->dokumen_kurikulum) }}" target="_blank">{{ basename($item->dokumen_kurikulum) }}</a>
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
                [1, "desc"]
            ],
            "pageLength": 10,
            "dom": 't<"d-flex justify-content-between mt-3"ip>',
            columnDefs: [{
                targets: [3, 4],
                orderable: false
            }]
        });

        // Search
        document.getElementById('searchButton').addEventListener('click', () =>
            table.search(document.getElementById('searchInput').value).draw()
        );
        document.getElementById('searchInput').addEventListener('keyup', (e) => {
            if (e.key === 'Enter') table.search(e.target.value).draw();
        });

        // Status filter
        $('#statusFilter').on('change', function() {
            const val = $(this).val().toLowerCase();
            table.column(4).search(val).draw();
        });

        // Entries per page
        document.getElementById('entriesSelect').addEventListener('change', function() {
            table.page.len(this.value).draw();
        });
    });
</script>
@endsection