@extends('admin.layout.kaprodi.main')
@section('title', 'Dashboard')

@section('content')
<h5>Statistik Sistem Akademik</h5>
<div class="d-flex flex-wrap mt-3">
    <div class="card m-2" style="width: 13rem; border-radius: 10px;">
        <div class="card-body d-flex justify-content-between align-items-center">
            <span>Total Dosen</span>
            <i class="fas fa-user"></i>
        </div>
    </div>
    <div class="card m-2" style="width: 13rem; border-radius: 10px;">
        <div class="card-body d-flex justify-content-between align-items-center">
            <span>Kurikulum</span>
            <i class="fas fa-users"></i>
        </div>
    </div>
    <div class="card m-2" style="width: 13rem; border-radius: 10px;">
        <div class="card-body d-flex justify-content-between align-items-center">
            <span>Mata Kuliah</span>
            <i class="fas fa-book"></i>
        </div>
    </div>
    <div class="card m-2" style="width: 13rem; border-radius: 10px;">
        <div class="card-body d-flex justify-content-between align-items-center">
            <span>Program Studi</span>
            <i class="fas fa-graduation-cap"></i>
        </div>
    </div>
</div>
@endsection