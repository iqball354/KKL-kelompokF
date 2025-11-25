@extends('admin.layout.dosen.main')
@section('title', 'Dashboard')

@section('content')

<div class="container mt-5">
    <h2>Manajemen Bidang Keahlian Dosen</h2>

    <!-- Tombol Tambah -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addExpertModal">
        Tambah Bidang Keahlian
    </button>

    <!-- Tabel Ringkas -->
    <table class="my-table">
        <thead>
            <tr>
                <th>Nama Dosen</th>
                <th>Bidang Keahlian</th>
                <th>Dokumen Pendukung</th>
                <th>Status Kaprodi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($keahlian as $item)
            <tr>
                <td>{{ $item->nama_dosen ?? '-' }}</td>
                <td>{{ is_array($item->bidang_keahlian) ? implode(', ', $item->bidang_keahlian) : $item->bidang_keahlian }}</td>

                <td>
                    @php
                    $totalDocs = count($item->dokumen_sertifikat ?? [])
                    + count($item->dokumen_lainnya ?? [])
                    + count($item->dokumen_pendidikan ?? [])
                    + count($item->link ?? []);
                    @endphp

                    @if($totalDocs)
                    <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#docModal-{{ $item->id }}">
                        <i class="fas fa-file-alt"></i> {{ $totalDocs }}
                    </button>
                    @else
                    -
                    @endif
                </td>

                <td>
                    <span class="badge 
                        @if($item->status_kaprodi=='disetujui') bg-success
                        @elseif($item->status_kaprodi=='ditolak') bg-danger
                        @else bg-secondary @endif">
                        {{ ucfirst($item->status_kaprodi) }}
                    </span>
                </td>

                <td>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editExpertModal-{{ $item->id }}">
                        <i class="fas fa-pen"></i>
                    </button>

                    <form action="{{ route('keahlian.destroy', $item->id) }}" method="POST" class="d-inline-block">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="addExpertModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('keahlian.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Bidang Keahlian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- Nama Dosen -->
                    <label>Nama Dosen</label>
                    <input type="text" name="nama_dosen" class="form-control mb-3">

                    <!-- Bidang Keahlian -->
                    <label>Bidang Keahlian</label>
                    <input type="text" name="bidang_keahlian[]" class="form-control mb-3">

                    <div class="text-center mb-3">
                        @foreach(['sertifikat','lainnya','pendidikan','link'] as $type)
                        <button type="button" class="btn btn-outline-dark btn-sm toggle-doc" data-type="{{ $type }}">
                            {{ ucfirst($type) }}
                        </button>
                        @endforeach
                    </div>

                    <!-- Dokumen: sertifikat, lainnya, pendidikan -->
                    @foreach(['sertifikat','lainnya','pendidikan'] as $type)
                    <div id="container-{{ $type }}" class="border p-3 mb-3 doc-container" data-type="{{ $type }}" style="display:none;">
                        <h6 class="text-center">Dokumen {{ ucfirst($type) }}</h6>

                        <div class="doc-row" data-type-row="{{ $type }}">
                            <div class="drive-upload-wrapper">
                                <label class="drive-upload-box">
                                    <i class="fas fa-file-alt"></i>
                                    <span class="drive-text">Upload File</span>
                                    <input type="file" name="dokumen_{{ $type }}[]" class="file-hidden drive-input">
                                </label>
                                <div class="preview-file"></div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-6"><input type="text" name="deskripsi_{{ $type }}[]" class="form-control" placeholder="Deskripsi"></div>
                                <div class="col-4"><input type="number" name="tahun_{{ $type }}[]" class="form-control" placeholder="Tahun" min="1900" max="2100"></div>
                                <div class="col-2 d-flex align-items-start">
                                    <button type="button" class="btn btn-danger btn-sm remove-doc ms-auto">Hapus</button>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary btn-sm add-doc" data-type="{{ $type }}">Tambah Dokumen</button>
                    </div>
                    @endforeach

                    <!-- Link -->
                    <div id="container-link" class="border p-3 mb-3 doc-container" data-type="link" style="display:none;">
                        <h6 class="text-center">Link Dokumen / Portofolio</h6>
                        <div class="link-list"></div>
                        <button type="button" class="btn btn-secondary btn-sm add-link" data-type="link">Tambah Link</button>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
            <form action="{{ route('keahlian.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Bidang Keahlian</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- Nama Dosen -->
                    <label>Nama Dosen</label>
                    <input type="text" name="nama_dosen" class="form-control mb-3" value="{{ $item->nama_dosen ?? '' }}">

                    <!-- Bidang Keahlian -->
                    <label>Bidang Keahlian</label>
                    @foreach((array)$item->bidang_keahlian as $bk)
                    <input type="text" name="bidang_keahlian[]" class="form-control mb-2" value="{{ $bk }}">
                    @endforeach

                    <div class="text-center mb-3">
                        @foreach(['sertifikat','lainnya','pendidikan','link'] as $type)
                        <button type="button" class="btn btn-outline-dark btn-sm toggle-doc-edit" data-type="{{ $type }}" data-id="{{ $item->id }}">
                            {{ ucfirst($type) }}
                        </button>
                        @endforeach
                    </div>

                    @foreach(['sertifikat','lainnya','pendidikan'] as $type)
                    <div id="edit-container-{{ $type }}-{{ $item->id }}" class="border p-3 mb-3 doc-container" data-type="{{ $type }}" style="display:none;">
                        <h6 class="text-center">Dokumen {{ ucfirst($type) }}</h6>

                        @foreach($item->{'dokumen_'.$type} ?? [] as $i => $doc)
                        <div class="row mb-2 doc-row">
                            <div class="col-12">
                                @php $ext = strtolower(pathinfo($doc, PATHINFO_EXTENSION)); @endphp
                                @if($ext == 'pdf')
                                <embed src="{{ asset('storage/'.$doc) }}" type="application/pdf" width="100%" height="400px">
                                @elseif(in_array($ext, ['doc','docx','xls','xlsx','ppt','pptx']))
                                <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/'.$doc)) }}" width="100%" height="400px" frameborder="0"></iframe>
                                @else
                                <a href="{{ asset('storage/'.$doc) }}" target="_blank">{{ basename($doc) }}</a>
                                @endif
                                <div>Deskripsi: {{ $item->{'deskripsi_'.$type}[$i] ?? '-' }}</div>
                                <div>Tahun: {{ $item->{'tahun_'.$type}[$i] ?? '-' }}</div>
                            </div>
                            <div class="col-12 text-end mt-1"><button type="button" class="btn btn-danger btn-sm remove-doc-existing">Hapus</button></div>
                        </div>
                        @endforeach

                        <div class="doc-new-list" data-id="{{ $item->id }}" data-type="{{ $type }}"></div>
                        <button type="button" class="btn btn-secondary btn-sm add-doc-btn" data-type="{{ $type }}" data-id="{{ $item->id }}">Tambah Dokumen</button>
                    </div>
                    @endforeach

                    <div id="edit-container-link-{{ $item->id }}" class="border p-3 mb-3 doc-container" data-type="link" style="display:none;">
                        <h6 class="text-center">Link Dokumen / Portofolio</h6>
                        <div class="link-list">
                            @foreach($item->link ?? [] as $l)
                            <div class="card mb-2 p-2 link-row">
                                <div class="d-flex justify-content-between align-items-center">
                                    <input type="url" name="link[]" class="form-control me-2" value="{{ $l }}">
                                    <button type="button" class="btn btn-danger btn-sm remove-link">Hapus</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm add-link-btn" data-id="{{ $item->id }}">Tambah Link</button>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- MODAL DOKUMEN GABUNGAN -->
@foreach($keahlian as $item)
@php
$allDocs = [
'Sertifikat' => $item->dokumen_sertifikat ?? [],
'Lainnya' => $item->dokumen_lainnya ?? [],
'Pendidikan' => $item->dokumen_pendidikan ?? [],
'Link' => $item->link ?? [],
];
@endphp
<div class="modal fade" id="docModal-{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dokumen Pendukung - {{ is_array($item->bidang_keahlian) ? implode(', ', $item->bidang_keahlian) : $item->bidang_keahlian }}</h5>
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