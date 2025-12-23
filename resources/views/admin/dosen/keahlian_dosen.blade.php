@extends('admin.layout.dosen.main')
@section('title', 'Data Bidang Keahlian Dosen')

@section('content')

<div class="container mt-5">
    <h2>Data Bidang Keahlian Dosen</h2>

    <!-- Tombol Tambah -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addExpertModal">
        + Tambah Bidang Keahlian
    </button>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <!-- Tabel Ringkas -->
    <table class="my-table" id="keahlianTable">
        <thead>
            <tr>
                <th style="width: 20px; text-align: center;">No</th>
                <th>Nama Dosen</th>
                <th>Bidang Keahlian</th>
                <th>Dokumen Pendukung</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($keahlian as $item)
            @php
            $totalDocs = count($item->dokumen_sertifikat ?? []) +
            count($item->dokumen_lainnya ?? []) +
            count($item->dokumen_pendidikan ?? []) +
            count($item->link ?? []);
            $modalId = md5($item->id . $item->nama_dosen);
            $status = strtolower($item->status_akademik ?? '');
            $badgeClass = match($status) {
            'disetujui', 'approved' => 'bg-success',
            'ditolak', 'rejected' => 'bg-danger',
            default => 'bg-secondary',
            };
            @endphp
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ $item->nama_dosen ?? '-' }}</td>
                <td>{{ is_array($item->bidang_keahlian) ? implode(', ', $item->bidang_keahlian) : $item->bidang_keahlian }}</td>
                <td>
                    <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#docModal-{{ $modalId }}">
                        <i class="fas fa-file-alt"></i> {{ $totalDocs }}
                    </button>
                </td>
                <td>
                    <span class="badge {{ $badgeClass }}">{{ $item->status_akademik ?? '-' }}</span>
                </td>
                <td>
                    <button class="btn btn-sm" style="background-color:#00cfff; color:black;" data-bs-toggle="modal" data-bs-target="#viewMataModal-{{ $item->id }}">
                        <i class="fas fa-eye"></i>
                    </button>

                    <button class="btn btn-sm" style="background-color:#ffc107; color:black;" data-bs-toggle="modal" data-bs-target="#editExpertModal-{{ $item->id }}">
                        <i class="fas fa-pen"></i>
                    </button>

                    <button class="btn btn-sm" style="background-color:#dc3545; color:white;" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $item->id }}">
                        <i class="fas fa-trash"></i>
                    </button>

                    <!-- Modal Hapus -->
                    <div class="modal fade" id="deleteModal-{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus bidang keahlian <strong>{{ $item->nama_dosen }}</strong>?
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('keahlian.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data untuk ditampilkan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="addExpertModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formAddExpert" action="{{ route('keahlian.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Bidang Keahlian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <label>Nama Dosen</label>
                    <input type="text" name="nama_dosen" class="form-control mb-3">

                    <label>Bidang Keahlian</label>
                    <div class="bidang-container" id="bidang-container">
                        <div class="input-group mb-2">
                            <input type="text" name="bidang_keahlian[]" class="form-control">
                            <button type="button" class="btn btn-danger remove-bidang">X</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" id="add-bidang">Tambah Bidang</button>

                    <div class="text-center mt-3 mb-3">
                        @foreach(['sertifikat','lainnya','pendidikan','link'] as $type)
                        <button type="button" class="btn btn-outline-dark btn-sm toggle-doc" data-type="{{ $type }}">
                            {{ ucfirst($type) }}
                        </button>
                        @endforeach
                    </div>

                    @foreach(['sertifikat','lainnya','pendidikan'] as $type)
                    <div class="doc-container border p-3 mb-3" data-type="{{ $type }}" style="display:none;">
                        <h6 class="text-center">Untuk menambahkan Dokumen {{ ucfirst($type) }}</h6>
                        <div class="doc-list"></div>
                        <button type="button" class="btn btn-secondary btn-sm add-doc" data-type="{{ $type }}">Tambah Dokumen</button>
                    </div>
                    @endforeach

                    <div class="doc-container border p-3 mb-3" data-type="link" style="display:none;">
                        <h6 class="text-center">Untuk menambahkan Link website / link Portofolio</h6>
                        <div class="link-list" style="display:flex; flex-wrap:wrap; gap:15px;"></div>
                        <button type="button" class="btn btn-secondary btn-sm add-link">Tambah Link</button>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT -->
@foreach($keahlian as $item)
<div class="modal fade" id="editExpertModal-{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEditExpert-{{ $item->id }}" action="{{ route('keahlian.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Bidang Keahlian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <label>Nama Dosen</label>
                    <input type="text" name="nama_dosen" class="form-control mb-3" value="{{ $item->nama_dosen ?? '' }}">

                    <label>Bidang Keahlian</label>
                    <div class="bidang-container" id="edit-bidang-container-{{ $item->id }}">
                        @foreach((array)$item->bidang_keahlian as $bk)
                        <div class="input-group mb-2">
                            <input type="text" name="bidang_keahlian[]" class="form-control" value="{{ $bk }}">
                            <button type="button" class="btn btn-danger remove-bidang">X</button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm add-bidang-btn" data-id="{{ $item->id }}">Tambah Bidang</button>

                    <div class="text-center mt-3 mb-3">
                        @foreach(['sertifikat','lainnya','pendidikan','link'] as $type)
                        <button type="button" class="btn btn-outline-dark btn-sm toggle-doc" data-type="{{ $type }}">
                            {{ ucfirst($type) }}
                        </button>
                        @endforeach
                    </div>

                    @foreach(['sertifikat','lainnya','pendidikan'] as $type)
                    <div class="doc-container border p-3 mb-3" data-type="{{ $type }}" style="display:none;">
                        <h6 class="text-center">Untuk menambahkan Dokumen {{ ucfirst($type) }}</h6>
                        <div class="doc-list">
                            @foreach($item->{'dokumen_'.$type} ?? [] as $i => $doc)
                            <div class="doc-row mb-2">
                                <a href="{{ asset('storage/'.$doc) }}" target="_blank" style="display:flex; flex-direction:column; align-items:center;">
                                    <embed src="{{ asset('storage/'.$doc) }}" type="application/pdf" width="180px" height="180px" style="margin-bottom:5px;">
                                    <div><small>{{ $item->{'deskripsi_'.$type}[$i] ?? '-' }}</small></div>
                                    <div><small>{{ $item->{'tahun_'.$type}[$i] ?? '-' }}</small></div>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm mt-1 remove-doc-existing">Hapus</button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm add-doc-btn" data-type="{{ $type }}" data-id="{{ $item->id }}">Tambah Dokumen</button>
                    </div>
                    @endforeach

                    <!-- Link editable -->
                    <div class="doc-container border p-3 mb-3" data-type="link" style="display:none;">
                        <h6 class="text-center">Untuk menambahkan Link website / link Portofolio</h6>
                        <div class="link-list" style="display:flex; flex-wrap:wrap; gap:15px;">
                            @foreach($item->link ?? [] as $i => $l)
                            <div class="link-row d-flex flex-column align-items-start mb-2" style="width:100%;">
                                <div class="d-flex gap-2">
                                    <input type="text" name="link[]" class="form-control" value="{{ $l }}" placeholder="URL Link">
                                    <input type="text" name="deskripsi_link[]" class="form-control" value="{{ $item->deskripsi_link[$i] ?? '' }}" placeholder="Deskripsi Link">
                                    <button type="button" class="btn btn-danger btn-sm remove-link">Hapus</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm add-link-btn mt-2" data-id="{{ $item->id }}">Tambah Link</button>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- MODAL VIEW STATUS -->
@foreach($keahlian as $item)
@php
$status = strtolower($item->status_akademik ?? '');
$badgeClass = match($status) {
'disetujui', 'approved' => 'bg-success',
'ditolak', 'rejected' => 'bg-danger',
default => 'bg-secondary',
};
@endphp
<div class="modal fade" id="viewMataModal-{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Status Validasi - {{ $item->nama_dosen }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p>Status Akademik: <span class="badge {{ $badgeClass }}">{{ $item->status_akademik ?? '-' }}</span></p>
                <p>Waktu Validasi: <strong>{{ $item->validasi_at ? $item->validasi_at->format('d/m/Y H:i') : '-' }}</strong></p>
                <p>Alasan: <em>{{ $item->alasan_validasi ?? '-' }}</em></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- MODAL DOKUMEN PENDUKUNG PREVIEW -->
@foreach($keahlian as $item)
@php $modalId = md5($item->id . $item->nama_dosen); @endphp
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
                $docs = $type === 'link' ? ($item->link ?? []) : ($item->{'dokumen_'.$type} ?? []);
                $deskripsi = $item->{'deskripsi_'.$type} ?? [];
                $tahun = $type==='link' ? [] : ($item->{'tahun_'.$type} ?? []);
                @endphp

                @if(count($docs))
                <h6 class="mt-3 mb-2">{{ ucfirst($type) }}</h6>
                <div style="display:flex; flex-wrap:wrap; gap:15px;">
                    @foreach($docs as $i => $doc)
                    <div style="width:220px; border:1px solid #dee2e6; border-radius:5px; padding:8px; display:flex; flex-direction:column; align-items:center;">
                        @if($type==='link')
                        @php $host = parse_url($doc, PHP_URL_HOST); $logo = 'https://www.google.com/s2/favicons?sz=64&domain=' . $host; @endphp
                        <a href="{{ $doc }}" target="_blank" style="text-align:center;">
                            <img src="{{ $logo }}" alt="Logo" style="width:32px; height:32px; object-fit:contain;">
                            <div><small>{{ $deskripsi[$i] ?? '-' }}</small></div>
                        </a>
                        @else
                        <a href="{{ asset('storage/'.$doc) }}" target="_blank" style="text-align:center;">
                            <embed src="{{ asset('storage/'.$doc) }}" type="application/pdf" width="180px" height="180px" style="margin-bottom:5px;">
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

@endsection