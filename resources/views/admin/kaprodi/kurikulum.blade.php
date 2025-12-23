@extends('admin.layout.kaprodi.main')
@section('title', 'Data Kurikulum')

@section('content')
<div class="container mt-5">
    <h2>Daftar Kurikulum</h2>

    <!-- FORM PRODI + PASSWORD (POKOK KANAN) -->
    <form method="GET" class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div></div> <!-- kosong biar geser kanan -->
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <label class="mb-0">Program Studi:</label>
            <select name="prodi" class="form-select" style="width: 220px;" required>
                <option value="">Pilih Program Studi</option>
                <optgroup label="Fakultas Ekonomi dan Bisnis (FEB)">
                    <option value="S2 Magister Manajemen" {{ (isset($prodi) && $prodi=='S2 Magister Manajemen')?'selected':'' }}>S2 Magister Manajemen</option>
                    <option value="S1 Manajemen" {{ (isset($prodi) && $prodi=='S1 Manajemen')?'selected':'' }}>S1 Manajemen</option>
                    <option value="S1 Akuntansi" {{ (isset($prodi) && $prodi=='S1 Akuntansi')?'selected':'' }}>S1 Akuntansi</option>
                    <option value="S1 Ekonomi Pembangunan" {{ (isset($prodi) && $prodi=='S1 Ekonomi Pembangunan')?'selected':'' }}>S1 Ekonomi Pembangunan</option>
                    <option value="D3 Keuangan dan Perbankan" {{ (isset($prodi) && $prodi=='D3 Keuangan dan Perbankan')?'selected':'' }}>D3 Keuangan dan Perbankan</option>
                </optgroup>
                <optgroup label="Fakultas Sains, Teknologi dan Industri (FSTI)">
                    <option value="S1 Sistem dan Teknologi Informasi" {{ (isset($prodi) && $prodi=='S1 Sistem dan Teknologi Informasi')?'selected':'' }}>S1 Sistem dan Teknologi Informasi</option>
                    <option value="S1 Rekayasa Perangkat Lunak" {{ (isset($prodi) && $prodi=='S1 Rekayasa Perangkat Lunak')?'selected':'' }}>S1 Rekayasa Perangkat Lunak</option>
                </optgroup>
            </select>

            <input type="password" name="password" class="form-control" placeholder="kunci" style="width:150px;" required>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </div>
    </form>

    @if(isset($error))
    <div class="alert alert-danger">{{ $error }}</div>
    @endif

    @php $isUnlocked = isset($data) && $data->count()>0; @endphp
    <input type="hidden" id="isUnlockedFlag" value="{{ $isUnlocked?1:0 }}">

    <!-- FILTER BAR -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <label class="mb-0">Tampilkan</label>
            <select id="entriesSelect" class="form-select d-inline-block w-auto">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            entri
        </div>

        <div class="d-flex align-items-center gap-2">
            <label class="mb-0">Status:</label>
            <select id="statusFilter" class="form-select" style="width:150px;">
                <option value="">Semua</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>

            <div class="input-group" style="width:280px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari data...">
                <button class="btn btn-primary" id="searchButton"><i class="fas fa-search"></i></button>
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
            @if($isUnlocked)
            @foreach ($data as $index => $item)
            @php $modalId = md5($item->id); @endphp
            <tr>
                <td>{{ $index+1 }}</td>
                <td>{{ $item->id_kurikulum }}</td>
                <td>{{ $item->tahun }}</td>
                <td>{{ $item->program_studi }}</td>
                <td>
                    @if($item->dokumen_kurikulum)
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#docModal-{{ $modalId }}"><i class="fas fa-book"></i></button>
                    @else
                    <span class="text-muted">Tidak ada dokumen</span>
                    @endif
                </td>
                <td>
                    <span class="badge {{ $item->status=='aktif'?'bg-success':'bg-secondary' }}">{{ ucfirst($item->status) }}</span>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="6" class="text-center text-muted">Masukkan program studi dan kunci untuk menampilkan data</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- MODAL DOKUMEN -->
@if($isUnlocked)
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
                @php $ext = strtolower(pathinfo($item->dokumen_kurikulum, PATHINFO_EXTENSION)); @endphp
                @if($ext=='pdf')
                <embed src="{{ asset('storage/'.$item->dokumen_kurikulum) }}" type="application/pdf" width="100%" height="500px">
                @elseif(in_array($ext,['doc','docx','xls','xlsx','ppt','pptx']))
                <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/'.$item->dokumen_kurikulum)) }}" width="100%" height="500px"></iframe>
                @else
                <a href="{{ asset('storage/'.$item->dokumen_kurikulum) }}" target="_blank">{{ basename($item->dokumen_kurikulum) }}</a>
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isUnlocked = document.getElementById('isUnlockedFlag').value === '1';
        if (!isUnlocked) return;

        const table = $('#kurikulumTable').DataTable({
            ordering: false,
            pageLength: 10,
            dom: 't<"d-flex justify-content-between mt-3"ip>',
            language: {
                emptyTable: "Tidak ada data untuk ditampilkan"
            }
        });

        table.on('order.dt search.dt', function() {
            table.column(0, {
                page: 'current'
            }).nodes().each((cell, i) => cell.innerHTML = i + 1);
        }).draw();

        document.getElementById('entriesSelect').addEventListener('change', function() {
            table.page.len(this.value).draw();
        });
        document.getElementById('searchButton').addEventListener('click', () => table.search(document.getElementById('searchInput').value).draw());
        document.getElementById('searchInput').addEventListener('keyup', e => {
            if (e.key === 'Enter') table.search(e.target.value).draw();
        });
        $('#statusFilter').on('change', function() {
            table.column(5).search($(this).val().toLowerCase()).draw();
        });
    });
</script>
@endsection