<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function dosen()
    {
        return view('admin.dosen.dashboard');
    }

    public function kaprodi()
    {
        return view('admin.kaprodi.dashboard');
    }

    public function dekan()
    {
        return view('admin.dekan.dashboard');
    }

    public function warek1()
    {
        return view('admin.warek1.dashboard');
    }

    public function hrd()
    {
        return view('admin.hrd.dashboard');
    }

    public function sdm()
    {
        return view('admin.sdm.dashboard');
    }

    public function akademik()
    {
        return view('admin.akademik.dashboard');
    }
}
