@extends('admin.layout.akademik.main')
@section('title', 'Dashboard')

@section('content')
<h4>Selamat Datang di Halaman Dashboard Akademik</h4>
<p>Fitur akademik di sistem ini mencakup:</p>

<div class="row row-cols-1 row-cols-md-3 g-4 mt-3">

    <div class="col">
        <div class="card h-100" style="background-color:#007bff; border-radius:10px; color:white;">
            <div class="card-body">
                <h5 class="card-title">Bidang Keahlian</h5>
                <p class="card-text">Validasi bidang keahlian dosen dan dokumen pendukung</p>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card h-100" style="background-color:#ffc107; border-radius:10px; color:white;">
            <div class="card-body">
                <h5 class="card-title">Kurikulum</h5>
                <p class="card-text">Total kurikulum yang ada / Aktif / Nonaktif</p>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card h-100" style="background-color:#28a745; border-radius:10px; color:white;">
            <div class="card-body">
                <h5 class="card-title">Konsentrasi Jurusan</h5>
                <p class="card-text">Verifikasi konsentrasi jurusan milik Kaprodi</p>
            </div>
        </div>
    </div>

</div>

<h5 class="mt-4">Keterangan Fitur</h5>

<h6>Bidang Keahlian Dosen</h6>
<ol>
    <li>Melihat total dokumen pendukung yang telah diunggah</li>
    <li>Menyetujui bidang keahlian dosen</li>
    <li>Menolak bidang keahlian dosen</li>
    <li>Mengirim pesan terkait bidang keahlian milik dosen</li>
</ol>

<h6>Kurikulum</h6>
<ol>
    <li>Menambahkan kurikulum baru</li>
    <li>Mengedit kurikulum</li>
    <li>Menghapus kurikulum</li>
    <li>Mengaktifkan / menonaktifkan kurikulum</li>
</ol>

<h6>Konsentrasi Jurusan Kaprodi</h6>
<ol>
    <li>Melihat konsentrasi jurusan milik kaprodi</li>
    <li>Menyetujui konsentrasi jurusan milik kaprodi</li>
    <li>Menolak konsentrasi jurusan milik kaprodi</li>
    <li>Mengirim pesan terkait konsentrasi jurusan milik kaprodi</li>
</ol>

@endsection