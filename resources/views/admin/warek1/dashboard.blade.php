@extends('admin.layout.warek1.main')
@section('title', 'Dashboard')

@section('content')
<h4>Selamat Datang di Halaman Dashboard Wakil Rektor I</h4>
<p>Fitur Wakil Rektor I di sistem ini mencakup:</p>

<div class="row row-cols-1 row-cols-md-3 g-4 mt-3">

    <!-- Bidang Keahlian Dosen -->
    <div class="col">
        <div class="card h-100" style="background-color:#007bff; border-radius:10px; color:white;">
            <div class="card-body">
                <h5 class="card-title">Bidang Keahlian Dosen</h5>
                <p class="card-text">
                    Total dosen yang terdaftar di sistem akademik </p>
            </div>
        </div>
    </div>

    <!-- Kurikulum -->
    <div class="col">
        <div class="card h-100" style="background-color:#ffc107; border-radius:10px; color:white;">
            <div class="card-body">
                <h5 class="card-title">Kurikulum</h5>
                <p class="card-text">
                    Melihat kurikulum aktif dan nonaktif pada setiap fakultas
                    dan program studi
                </p>
            </div>
        </div>
    </div>

    <!-- Konsentrasi Jurusan -->
    <div class="col">
        <div class="card h-100" style="background-color:#28a745; border-radius:10px; color:white;">
            <div class="card-body">
                <h5 class="card-title">Konsentrasi Jurusan</h5>
                <p class="card-text">
                    Melihat konsentrasi jurusan beserta status yang ditetapkan
                    oleh Akademik
                </p>
            </div>
        </div>
    </div>

</div>

<h5 class="mt-4">Keterangan Fitur</h5>

<h6>Bidang Keahlian Dosen</h6>
<ol>
    <li>Melihat daftar bidang keahlian dosen</li>
    <li>Melihat detail bidang keahlian dosen</li>
    <li>Melihat dokumen pendukung bidang keahlian dosen</li>
    <li>Mencakup seluruh program studi pada setiap fakultas</li>
</ol>

<h6>Kurikulum</h6>
<ol>
    <li>Melihat daftar kurikulum</li>
    <li>Melihat status kurikulum (aktif / nonaktif)</li>
    <li>Melihat kurikulum berdasarkan fakultas dan program studi</li>
</ol>

<h6>Konsentrasi Jurusan</h6>
<ol>
    <li>Melihat daftar konsentrasi jurusan</li>
    <li>Melihat detail konsentrasi jurusan</li>
    <li>Melihat status konsentrasi jurusan dari Akademik</li>
</ol>

@endsection