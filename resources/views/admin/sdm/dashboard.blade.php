@extends('admin.layout.sdm.main')
@section('title', 'Dashboard')

@section('content')
<h4>Selamat Datang di Halaman Dashboard SDM</h4>
<p>Fitur SDM di sistem ini mencakup:</p>

<div class="row row-cols-1 row-cols-md-3 g-4 mt-3">

    <!-- Total Dosen -->
    <div class="col">
        <div class="card h-100" style="background-color:#007bff; color:white; border-radius:10px;">
            <div class="card-body">
                <h5 class="card-title">Total Dosen</h5>
                <p class="card-text">Melihat total dosen yang terdaftar di sistem akademik</p>
            </div>
        </div>
    </div>

    <!-- Bidang Keahlian Dosen -->
    <div class="col">
        <div class="card h-100" style="background-color:#28a745; color:white; border-radius:10px;">
            <div class="card-body">
                <h5 class="card-title">Bidang Keahlian Dosen</h5>
                <p class="card-text">Melihat bidang keahlian yang dimiliki dosen beserta dokumen pendukung</p>
            </div>
        </div>
    </div>

    <!-- Dokumen & Status Validasi -->
    <div class="col">
        <div class="card h-100" style="background-color:#ffc107; color:white; border-radius:10px;">
            <div class="card-body">
                <h5 class="card-title">Dokumen & Validasi</h5>
                <p class="card-text">Melihat dokumen pendukung dan status validasi bidang keahlian dosen</p>
            </div>
        </div>
    </div>

</div>

<h5 class="mt-4">Keterangan Fitur</h5>

<!-- Modul Bidang Keahlian Dosen -->
<h6>Bidang Keahlian Dosen</h6>
<ol>
    <li>Melihat daftar bidang keahlian dosen</li>
    <li>Melihat status validasi akademik bidang keahlian dosen</li>
    <li>Melihat dokumen pendukung bidang keahlian dosen, seperti:
        <ul style="list-style-type: disc; margin-left: 20px;">
            <li>Dokumen sertifikat, pelatihan, dan penelitian</li>
            <li>Dokumen lainnya, seperti penempatan atau keputusan</li>
            <li>Dokumen jenjang pendidikan (D3 sampai S3), minimal S2</li>
            <li>Link website atau portofolio dosen</li>
        </ul>
    </li>
</ol>

@endsection