@extends('admin.layout.dosen.main')
@section('title', 'Dashboard')

@section('content')

<div class="container mt-5">
    <h2>Data Bidang Keahlian Dosen</h2>

    <!-- Tombol Tambah -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addExpertModal">
        Tambah Bidang Keahlian
    </button>

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
            @foreach($keahlian as $item)
            @php
            $totalDocs = count($item->dokumen_sertifikat ?? []) +
            count($item->dokumen_lainnya ?? []) +
            count($item->dokumen_pendidikan ?? []) +
            count($item->link ?? []);
            $modalId = md5($item->id . $item->nama_dosen);
            @endphp
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ $item->nama_dosen ?? '-' }}</td>
                <td>{{ is_array($item->bidang_keahlian) ? implode(', ', $item->bidang_keahlian) : $item->bidang_keahlian }}</td>
                <td>
                    @if($totalDocs)
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#docModal-{{ $modalId }}">
                        <i class="fas fa-file-alt"></i> {{ $totalDocs }}
                    </button>
                    @else
                    -
                    @endif
                </td>
                <td>
                    <span class="badge 
                        @if($item->status_akademik=='disetujui') bg-success
                        @elseif($item->status_akademik=='ditolak') bg-danger
                        @else bg-secondary @endif">
                        {{ ucfirst($item->status_akademik) ?? '-' }}
                    </span>
                </td>
                <td>
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewMataModal-{{ $item->id }}">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editExpertModal-{{ $item->id }}">
                        <i class="fas fa-pen"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $item->id }}">
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
                        <h6 class="text-center">Dokumen {{ ucfirst($type) }}</h6>
                        <div class="doc-list"></div>
                        <button type="button" class="btn btn-secondary btn-sm add-doc" data-type="{{ $type }}">Tambah Dokumen</button>
                    </div>
                    @endforeach

                    <div class="doc-container border p-3 mb-3" data-type="link" style="display:none;">
                        <h6 class="text-center">Link Dokumen / Portofolio</h6>
                        <div class="link-list"></div>
                        <button type="button" class="btn btn-secondary btn-sm add-link">Tambah Link</button>
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
                        <h6 class="text-center">Dokumen {{ ucfirst($type) }}</h6>
                        <div class="doc-list">
                            @foreach($item->{'dokumen_'.$type} ?? [] as $i => $doc)
                            <div class="doc-row mb-2">
                                @php $ext = strtolower(pathinfo($doc, PATHINFO_EXTENSION)); @endphp
                                @if($ext=='pdf')
                                <embed src="{{ asset('storage/'.$doc) }}" width="100%" height="150px">
                                @elseif(in_array($ext,['doc','docx','xls','xlsx','ppt','pptx']))
                                <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/'.$doc)) }}" width="100%" height="150px"></iframe>
                                @else
                                <a href="{{ asset('storage/'.$doc) }}" target="_blank">{{ basename($doc) }}</a>
                                @endif
                                <input type="text" name="deskripsi_{{ $type }}[]" class="form-control mt-1" value="{{ $item->{'deskripsi_'.$type}[$i] ?? '' }}" placeholder="Deskripsi">
                                <input type="number" name="tahun_{{ $type }}[]" class="form-control mt-1" value="{{ $item->{'tahun_'.$type}[$i] ?? '' }}" placeholder="Tahun" min="1900" max="2100">
                                <button type="button" class="btn btn-danger btn-sm mt-1 remove-doc-existing">Hapus</button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm add-doc-btn" data-type="{{ $type }}" data-id="{{ $item->id }}">Tambah Dokumen</button>
                    </div>
                    @endforeach

                    <div class="doc-container border p-3 mb-3" data-type="link" style="display:none;">
                        <h6 class="text-center">Link Dokumen / Portofolio</h6>
                        <div class="link-list">
                            @foreach($item->link ?? [] as $l)
                            <div class="link-row mb-2 d-flex">
                                <input type="url" name="link[]" class="form-control me-2" value="{{ $l }}">
                                <button type="button" class="btn btn-danger btn-sm remove-link">Hapus</button>
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

<!-- MODAL VIEW STATUS -->
@foreach($keahlian as $item)
<div class="modal fade" id="viewMataModal-{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Status Validasi - {{ $item->nama_dosen }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p>Status Akademik: <strong>{{ ucfirst($item->status_akademik) }}</strong></p>
                <p>Waktu Validasi: <strong>{{ $item->validasi_at ? $item->validasi_at->format('d/m/Y H:i') : '-' }}</strong></p>
                @if($item->alasan_validasi)
                <p>Alasan: <em>{{ $item->alasan_validasi }}</em></p>
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- MODAL DOKUMEN -->
@foreach($keahlian as $item)
@php
$allDocs = [
'Sertifikat' => $item->dokumen_sertifikat ?? [],
'Lainnya' => $item->dokumen_lainnya ?? [],
'Pendidikan' => $item->dokumen_pendidikan ?? [],
'Link' => $item->link ?? [],
];
$modalId = md5($item->id . $item->nama_dosen);
@endphp
<div class="modal fade" id="docModal-{{ $modalId }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dokumen Pendukung - {{ $item->nama_dosen }}</h5>
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
                    @if($ext=='pdf')
                    <embed src="{{ asset('storage/'.$doc) }}" type="application/pdf" width="100%" height="400px">
                    @elseif(in_array($ext,['doc','docx','xls','xlsx','ppt','pptx']))
                    <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/'.$doc)) }}" width="100%" height="400px"></iframe>
                    @else
                    <a href="{{ asset('storage/'.$doc) }}" target="_blank">{{ basename($doc) }}</a>
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
    document.addEventListener("DOMContentLoaded", function() {

        // Tambah bidang keahlian
        document.getElementById("add-bidang")?.addEventListener("click", function() {
            let container = document.getElementById("bidang-container");
            let div = document.createElement("div");
            div.classList.add("input-group", "mb-2");
            div.innerHTML = `<input type="text" name="bidang_keahlian[]" class="form-control">
                         <button type="button" class="btn btn-danger remove-bidang">X</button>`;
            container.appendChild(div);
        });

        document.querySelectorAll(".add-bidang-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                let id = this.dataset.id;
                let container = document.getElementById(`edit-bidang-container-${id}`);
                let div = document.createElement("div");
                div.classList.add("input-group", "mb-2");
                div.innerHTML = `<input type="text" name="bidang_keahlian[]" class="form-control">
                             <button type="button" class="btn btn-danger remove-bidang">X</button>`;
                container.appendChild(div);
            });
        });

        // Toggle Dokumen
        document.querySelectorAll(".toggle-doc").forEach(btn => {
            btn.addEventListener("click", function() {
                let type = this.dataset.type;
                this.closest(".modal-body").querySelectorAll(".doc-container").forEach(c => {
                    c.style.display = c.dataset.type === type ? (c.style.display === "none" ? "block" : "none") : "none";
                });
            });
        });

        function createDocRow(type) {
            let div = document.createElement("div");
            div.classList.add("doc-row", "mb-2");
            div.innerHTML = `<div class="drive-upload-wrapper">
                            <label class="drive-upload-box">
                                <i class="fas fa-file-alt"></i> <span class="drive-text">Upload File</span>
                                <input type="file" name="dokumen_${type}[]" class="file-hidden drive-input">
                            </label>
                            <input type="text" name="deskripsi_${type}[]" placeholder="Deskripsi" class="form-control mt-1">
                            <input type="number" name="tahun_${type}[]" placeholder="Tahun" class="form-control mt-1" min="1900" max="2100">
                            <button type="button" class="btn btn-danger btn-sm mt-1 remove-doc">Hapus</button>
                     </div>`;
            return div;
        }

        document.querySelectorAll(".add-doc, .add-doc-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                let type = this.dataset.type;
                let container = this.closest(".doc-container").querySelector(".doc-list");
                container.appendChild(createDocRow(type));
            });
        });

        document.querySelectorAll(".add-link, .add-link-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                let container = this.closest(".doc-container").querySelector(".link-list");
                let div = document.createElement("div");
                div.classList.add("link-row", "mb-2", "d-flex");
                div.innerHTML = `<input type="url" name="link[]" class="form-control me-2">
                             <button type="button" class="btn btn-danger btn-sm remove-link">Hapus</button>`;
                container.appendChild(div);
            });
        });

        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("remove-bidang")) e.target.parentElement.remove();
            if (e.target.classList.contains("remove-doc") || e.target.classList.contains("remove-doc-existing")) e.target.closest(".doc-row").remove();
            if (e.target.classList.contains("remove-link")) e.target.closest(".link-row").remove();
        });

    });
</script>
@endsection