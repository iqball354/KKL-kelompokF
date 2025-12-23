@extends('admin.layout.kaprodi.main')
@section('title', 'Dashboard')

@section('content')
<h4>Selamat Datang di Halaman Dashboard Kaprodi</h4>
<p>Fitur Kaprodi di sistem ini mencakup:</p>

<div class="row row-cols-1 row-cols-md-3 g-4 mt-3">

    <div class="col">
        <div class="card h-100" style="background-color:#007bff; color:white; border-radius:10px;">
            <div class="card-body">
                <h5 class="card-title">Bidang Keahlian Dosen</h5>
                <p class="card-text">
                    Total dosen yang terdaftar di sistem akademik </p>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card h-100" style="background-color:#ffc107; color:white; border-radius:10px;">
            <div class="card-body">
                <h5 class="card-title">Kurikulum</h5>
                <p class="card-text">
                    Melihat kurikulum dan memasukkan kunci kurikulum sesuai program studi
                </p>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card h-100" style="background-color:#28a745; color:white; border-radius:10px;">
            <div class="card-body">
                <h5 class="card-title">Konsentrasi Jurusan</h5>
                <p class="card-text">
                    Memasukkan kunci konsentrasi jurusan, bisa tambah/edit/hapus, dan diverifikasi oleh akademik
                </p>
            </div>
        </div>
    </div>

</div>

<h5 class="mt-4">Keterangan Fitur</h5>

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

<h6>Kurikulum</h6>
<ol>
    <li>Memasukkan kunci kurikulum sesuai program studi</li>
    <li>Melihat daftar kurikulum berdasarkan program studi</li>
    <li>Melihat daftar kurikulum aktif/nonaktif</li>
</ol>

<h6>Konsentrasi Jurusan</h6>
<ol>
    <li>Memasukkan kunci kosentrasi jurusan sesuai program studi</li>
    <li>Bisa tambah, edit, hapus data konsentrasi jurusan</li>
    <li>Melihat status diverifikasi oleh akademik</li>
</ol>

@endsection