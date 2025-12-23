@extends('admin.layout.dosen.main')
@section('title', 'Dashboard')

@section('content')
<h4>Selamat Datang di Halaman Dashboard</h4>
<p>Fitur dosen di sistem ini mencakup:</p>

<div class="row row-cols-1 row-cols-md-3 g-4 mt-3">

    <!-- Jumlah Dosen -->
    <div class="col">
        <div class="card h-100" style="background-color:#007bff; color:white; border-radius:10px;">
            <div class="card-body">
                <h5 class="card-title">Jumlah Dosen</h5>
                <p class="card-text">Total dosen yang terdaftar di sistem akademik</p>
            </div>
        </div>
    </div>

    <!-- Validasi Akademik -->
    <div class="col">
        <div class="card h-100" style="background-color:#ffc107; color:white; border-radius:10px;">
            <div class="card-body">
                <h5 class="card-title">Validasi Akademik</h5>
                <p class="card-text">Status validasi akademik dosen disetujui atau ditolak</p>
            </div>
        </div>
    </div>

    <!-- Bidang Keahlian & Dokumen -->
    <div class="col">
        <div class="card h-100" style="background-color:#28a745; color:white; border-radius:10px;">
            <div class="card-body">
                <h5 class="card-title">Bidang Keahlian</h5>
                <p class="card-text">Menampilkan bidang keahlian yang dimiliki dosen beserta dokumen pendukung</p>
            </div>
        </div>
    </div>

</div>

<h5 class="mt-4">Keterangan Fitur</h5>

<!-- Modul Bidang Keahlian-->
<h6>Bidang Keahlian Dosen</h6>
<ol>
    <li>Menambahkan bidang keahlian</li>
    <li>Mengedit bidang keahlian</li>
    <li>Menghapus bidang keahlian</li>
    <li>Melihat status validasi akademik bidang keahlian</li>
    <li>Melihat dokumen pendukung bidang keahlian, seperti:
        <ul style="list-style-type: disc; margin-left: 20px;">
            <li>Dokumen sertifikat, pelatihan, dan penelitian</li>
            <li>Dokumen lainnya, seperti penempatan atau keputusan</li>
            <li>Dokumen pendidikan (D3 sampai S3), minimal S2</li>
            <li>Link website atau portofolio</li>
        </ul>
    </li>
</ol>

@endsection