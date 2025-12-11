<?php

namespace App\Http\Controllers;

use App\Models\KonsentrasiJurusan;
use App\Models\Kurikulum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KonsentrasiJurusanController extends Controller
{
    // =========================
    // Kaprodi - CRUD dengan kunci
    // =========================
    public function index(Request $request)
    {
        $queryProdi = $request->input('prodi');
        $password = $request->input('password');

        // Password per program studi
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
        $kurikulums = collect([]);
        $error = null;

        if ($queryProdi && $password) {
            if (isset($validPasswords[$queryProdi]) && $validPasswords[$queryProdi] === $password) {
                // Ambil semua kurikulum untuk prodi itu
                $kurikulums = Kurikulum::where('program_studi', $queryProdi)->get();
                $data = KonsentrasiJurusan::with('kurikulum')
                    ->whereIn('kurikulum_id', $kurikulums->pluck('id'))
                    ->get();
            } else {
                $error = "Kunci salah.";
            }
        }

        return view('admin.kaprodi.konsentrasi', compact('data', 'kurikulums', 'queryProdi', 'error'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kurikulum_id' => 'required|exists:kurikulums,id',
            'kode_konsentrasi' => 'required|string|unique:konsentrasi_jurusan,kode_konsentrasi',
            'nama_konsentrasi' => 'required|string',
            'sub_konsentrasi' => 'nullable|array',
        ]);

        KonsentrasiJurusan::create([
            'kurikulum_id' => $request->kurikulum_id,
            'kode_konsentrasi' => $request->kode_konsentrasi,
            'nama_konsentrasi' => $request->nama_konsentrasi,
            'sub_konsentrasi' => $request->sub_konsentrasi,
            'status_verifikasi' => 'menunggu',
        ]);

        return redirect()->back()->with('success', 'Data konsentrasi berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kurikulum_id' => 'required|exists:kurikulums,id',
            'kode_konsentrasi' => 'required|string|unique:konsentrasi_jurusan,kode_konsentrasi,' . $id,
            'nama_konsentrasi' => 'required|string',
            'sub_konsentrasi' => 'nullable|array',
        ]);

        $konsentrasi = KonsentrasiJurusan::findOrFail($id);
        $konsentrasi->update([
            'kurikulum_id' => $request->kurikulum_id,
            'kode_konsentrasi' => $request->kode_konsentrasi,
            'nama_konsentrasi' => $request->nama_konsentrasi,
            'sub_konsentrasi' => $request->sub_konsentrasi,
            'status_verifikasi' => 'pending',
            'verifikasi_by' => null,
            'verifikasi_at' => null,
            'alasan_verifikasi' => null,
        ]);

        return redirect()->back()->with('success', 'Data konsentrasi berhasil diupdate');
    }

    public function destroy($id)
    {
        $konsentrasi = KonsentrasiJurusan::findOrFail($id);
        $konsentrasi->delete();
        return redirect()->back()->with('success', 'Data konsentrasi berhasil dihapus');
    }

    // =========================
    // Dekan & Warek1 - Read Only
    // =========================
    public function showForDekan()
    {
        // Tidak perlu password dan tidak perlu memilih fakultas
        $error = null;

        // Ambil semua data konsentrasi
        $data = KonsentrasiJurusan::with('kurikulum', 'verifier')->get();

        // Variabel fakultas tetap dikirim untuk mencegah error di Blade
        $fakultas = null;

        return view('admin.dekan.konsentrasi_jurusan', compact('data', 'fakultas', 'error'));
    }

    public function showForWarek1()
    {
        $data = KonsentrasiJurusan::with('kurikulum', 'verifier')->get();
        return view('admin.warek1.konsentrasi', compact('data'));
    }

    // =========================
    // Akademik - Verifikasi
    // =========================
    public function verifikasiIndex()
    {
        $data = KonsentrasiJurusan::with('kurikulum', 'verifier')->get();
        return view('admin.akademik.konsentrasi_jurusan', compact('data'));
    }

    public function verifikasiUpdate(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:disetujui,ditolak',
            'alasan_verifikasi' => 'nullable|string',
        ]);

        $konsentrasi = KonsentrasiJurusan::findOrFail($id);

        $konsentrasi->update([
            'status_verifikasi' => $request->status_verifikasi,
            'alasan_verifikasi' => $request->status_verifikasi == 'ditolak'
                ? $request->alasan_verifikasi
                : null,
            'verifikasi_by' => Auth::id(),
            'verifikasi_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Status verifikasi berhasil diperbarui');
    }
}
