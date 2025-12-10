<?php

namespace App\Http\Controllers;

use App\Models\Kurikulum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KurikulumController extends Controller
{
    // ===========================
    // Halaman Akademik
    // ===========================
    public function index()
    {
        $data = Kurikulum::all();
        return view('admin.akademik.kurikulum', compact('data'));
    }

    // ===========================
    // CRUD
    // ===========================
    public function store(Request $request)
    {
        $request->validate([
            'kurikulum' => 'required',
            'tahun' => 'required|integer',
            'program_studi' => 'required',
            'dokumen_kurikulum' => 'nullable|mimes:pdf|max:20480',
            'status' => 'required'
        ]);

        $filePath = null;
        if ($request->hasFile('dokumen_kurikulum')) {
            $filePath = $request->file('dokumen_kurikulum')->store('kurikulum', 'public');
        }

        Kurikulum::create([
            'kurikulum' => $request->kurikulum,
            'tahun' => $request->tahun,
            'program_studi' => $request->program_studi,
            'dokumen_kurikulum' => $filePath,
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kurikulum = Kurikulum::findOrFail($id);
        return response()->json($kurikulum);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kurikulum' => 'required',
            'tahun' => 'required|integer',
            'program_studi' => 'required',
            'dokumen_kurikulum' => 'nullable|mimes:pdf|max:20480',
            'status' => 'required'
        ]);

        $kurikulum = Kurikulum::findOrFail($id);

        if ($request->hasFile('dokumen_kurikulum')) {
            if ($kurikulum->dokumen_kurikulum) {
                Storage::delete('public/' . $kurikulum->dokumen_kurikulum);
            }
            $filePath = $request->file('dokumen_kurikulum')->store('kurikulum', 'public');
        } else {
            $filePath = $kurikulum->dokumen_kurikulum;
        }

        $kurikulum->update([
            'kurikulum' => $request->kurikulum,
            'tahun' => $request->tahun,
            'program_studi' => $request->program_studi,
            'dokumen_kurikulum' => $filePath,
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $kurikulum = Kurikulum::findOrFail($id);
        if ($kurikulum->dokumen_kurikulum) {
            Storage::delete('public/' . $kurikulum->dokumen_kurikulum);
        }
        $kurikulum->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    // ===========================
    // Halaman Dekan
    // ===========================
    public function showForDekan(Request $request)
    {
        // Password per dekan
        $dekanPasswords = [
            'FEB' => 'feb123',
            'FSTI' => 'fsti123',
        ];

        $fakultas = $request->input('fakultas');
        $password = $request->input('password');
        $error = null;
        $data = collect([]);

        $fakultasProdi = [
            'FEB' => [
                'S2 Magister Manajemen',
                'S1 Manajemen',
                'S1 Akuntansi',
                'S1 Ekonomi Pembangunan',
                'D3 Keuangan dan Perbankan'
            ],
            'FSTI' => [
                'S1 Sistem dan Teknologi Informasi (STI)',
                'S1 Rekayasa Perangkat Lunak (RPL)'
            ]
        ];

        if ($fakultas && $password) {
            // cek password dekan
            if (isset($dekanPasswords[$fakultas]) && $dekanPasswords[$fakultas] === $password) {
                // ambil semua kurikulum prodi di fakultas itu
                if (isset($fakultasProdi[$fakultas])) {
                    $data = Kurikulum::whereIn('program_studi', $fakultasProdi[$fakultas])->get();
                }
            } else {
                $error = "Password Dekan salah.";
            }
        }

        return view('admin.dekan.kurikulum', compact('data', 'fakultas', 'error'));
    }

    // ===========================
    // Halaman Warek
    // ===========================
    public function showForWarek1()
    {
        $data = Kurikulum::all();
        return view('admin.warek1.kurikulum', compact('data'));
    }

    // ===========================
    // Halaman Kaprodi
    // ===========================
    public function showForKaprodi(Request $request)
    {
        $queryProdi = $request->input('prodi');
        $password = $request->input('password');

        $validPasswords = [
            // Fakultas FEB
            'S2 Magister Manajemen' => 'mm123',
            'S1 Manajemen' => 'm123',
            'S1 Akuntansi' => 'ak123',
            'S1 Ekonomi Pembangunan' => 'ep123',
            'D3 Keuangan dan Perbankan' => 'kb123',
            // Fakultas FSTI
            'S1 Sistem dan Teknologi Informasi (STI)' => 'sti123',
            'S1 Rekayasa Perangkat Lunak (RPL)' => 'rpl123'
        ];

        $data = collect([]);
        $error = null;

        if ($queryProdi && $password) {
            if (isset($validPasswords[$queryProdi]) && $validPasswords[$queryProdi] === $password) {
                $data = Kurikulum::where('program_studi', $queryProdi)->get();
            } else {
                $error = "Kunci salah.";
            }
        }

        return view('admin.kaprodi.kurikulum', compact('data', 'queryProdi', 'error'));
    }
}
