@extends('admin.layout.dekan.main')
@section('title', 'Dashboard')

@section('content')
<h4>Selamat Datang di Halaman Dashboard Dekan</h4>
<p>Fitur Dekan di sistem ini mencakup:</p>

<div class="row row-cols-1 row-cols-md-3 g-4 mt-3">

    <div class="col">
        <div class="card h-100" style="background-color:#007bff; border-radius:10px; color:white;">
            <div class="card-body">
                <h5 class="card-title">Bidang Keahlian Dosen</h5>
                <p class="card-text">
                    Total dosen yang terdaftar di sistem akademik </p>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card h-100" style="background-color:#ffc107; border-radius:10px; color:white;">
            <div class="card-body">
                <h5 class="card-title">Kurikulum</h5>
                <p class="card-text">
                    Melihat kurikulum dan memasukkan kunci kurikulum sesuai fakultas
                </p>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card h-100" style="background-color:#28a745; border-radius:10px; color:white;">
            <div class="card-body">
                <h5 class="card-title">Konsentrasi Jurusan</h5>
                <p class="card-text">
                    Melihat konsentrasi jurusan dan memasukkan kunci sesuai fakultas
                </p>
            </div>
        </div>
    </div>

</div>

<h5 class="mt-4">Keterangan Fitur</h5>

<h6>Bidang Keahlian Dosen</h6>
<ol>
    <li>Melihat daftar bidang keahlian dosen</li>
    <li>Melihat dokumen pendukung bidang keahlian</li>
    <li>Tidak memiliki akses tambah, edit, hapus, atau verifikasi</li>
</ol>

<h6>Kurikulum</h6>
<ol>
    <li>Memasukkan kunci kurikulum sesuai fakultas</li>
    <li>Melihat daftar kurikulum berdasarkan fakultas</li>
    <li>Melihat status kurikulum (aktif / nonaktif)</li>
</ol>

<h6>Konsentrasi Jurusan</h6>
<ol>
    <li>Memasukkan kunci konsentrasi jurusan sesuai fakultas</li>
    <li>Melihat konsentrasi jurusan pada fakultas terkait</li>
    <li>Melihat status konsentrasi jurusan dari Akademik</li>
</ol>

@endsection