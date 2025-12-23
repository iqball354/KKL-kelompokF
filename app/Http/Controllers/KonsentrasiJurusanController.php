<?php

namespace App\Http\Controllers;

use App\Models\KonsentrasiJurusan;
use App\Models\Kurikulum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KonsentrasiJurusanController extends Controller
{
    // =====================================================
    // KAPRODI - CRUD DENGAN KUNCI PROGRAM STUDI
    // =====================================================
    public function index(Request $request)
    {
        $queryProdi = $request->input('prodi');
        $password   = $request->input('password');

        $validPasswords = [
            'S2 Magister Manajemen'           => 'mm123',
            'S1 Manajemen'                   => 'm123',
            'S1 Akuntansi'                   => 'ak123',
            'S1 Ekonomi Pembangunan'         => 'ep123',
            'D3 Keuangan dan Perbankan'      => 'kb123',
            'S1 Sistem dan Teknologi Informasi' => 'sti123',
            'S1 Rekayasa Perangkat Lunak'    => 'rpl123',
        ];

        $data       = collect([]);
        $kurikulums = collect([]);
        $error      = null;

        if ($queryProdi && $password) {
            if (
                isset($validPasswords[$queryProdi]) &&
                $validPasswords[$queryProdi] === $password
            ) {
                $kurikulums = Kurikulum::where('program_studi', $queryProdi)->get();

                $data = KonsentrasiJurusan::with('kurikulum')
                    ->whereIn('kurikulum_id', $kurikulums->pluck('id'))
                    ->get();
            } else {
                $error = 'Kunci salah.';
            }
        }

        return view('admin.kaprodi.konsentrasi', compact(
            'data',
            'kurikulums',
            'queryProdi',
            'error'
        ));
    }

    // =====================================================
    // STORE
    // =====================================================
    public function store(Request $request)
    {
        $request->validate([
            'kurikulum_id'      => 'required|exists:kurikulums,id',
            'kode_konsentrasi'  => 'required|string|unique:konsentrasi_jurusan,kode_konsentrasi',
            'nama_konsentrasi'  => 'required|string',
            'deskripsi'         => 'nullable|string',
            'sub_konsentrasi'   => 'nullable|array',
        ]);

        KonsentrasiJurusan::create([
            'kurikulum_id'      => $request->kurikulum_id,
            'kode_konsentrasi'  => $request->kode_konsentrasi,
            'nama_konsentrasi'  => $request->nama_konsentrasi,
            'deskripsi'         => $request->deskripsi,
            'sub_konsentrasi'   => $request->sub_konsentrasi,
            'status_verifikasi' => 'menunggu',
        ]);

        return redirect()->back()->with('success', 'Data konsentrasi berhasil ditambahkan');
    }

    // =====================================================
    // UPDATE
    // =====================================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'kurikulum_id'      => 'required|exists:kurikulums,id',
            'kode_konsentrasi'  => 'required|string|unique:konsentrasi_jurusan,kode_konsentrasi,' . $id,
            'nama_konsentrasi'  => 'required|string',
            'deskripsi'         => 'nullable|string',
            'sub_konsentrasi'   => 'nullable|array',
        ]);

        $konsentrasi = KonsentrasiJurusan::findOrFail($id);

        $konsentrasi->update([
            'kurikulum_id'      => $request->kurikulum_id,
            'kode_konsentrasi'  => $request->kode_konsentrasi,
            'nama_konsentrasi'  => $request->nama_konsentrasi,
            'deskripsi'         => $request->deskripsi,
            'sub_konsentrasi'   => $request->sub_konsentrasi,
            'status_verifikasi' => 'menunggu',
            'verifikasi_by'     => null,
            'verifikasi_at'     => null,
            'alasan_verifikasi' => null,
        ]);

        return redirect()->back()->with('success', 'Data konsentrasi berhasil diperbarui');
    }

    // =====================================================
    // DELETE
    // =====================================================
    public function destroy($id)
    {
        KonsentrasiJurusan::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Data konsentrasi berhasil dihapus');
    }

    // =====================================================
    // DEKAN - READ ONLY DENGAN KUNCI FAKULTAS
    // =====================================================
    public function showForDekan(Request $request)
    {
        $fakultas = $request->input('fakultas');
        $password = $request->input('password');

        $error = null;
        $data  = collect([]);

        $dekanPasswords = [
            'FEB'  => 'feb123',
            'FSTI' => 'fsti123',
        ];

        $fakultasProdi = [
            'FEB' => [
                'S2 Magister Manajemen',
                'S1 Manajemen',
                'S1 Akuntansi',
                'S1 Ekonomi Pembangunan',
                'D3 Keuangan dan Perbankan',
            ],
            'FSTI' => [
                'S1 Sistem dan Teknologi Informasi',
                'S1 Rekayasa Perangkat Lunak',
            ],
        ];

        if ($fakultas && $password) {
            if (
                isset($dekanPasswords[$fakultas]) &&
                $dekanPasswords[$fakultas] === $password
            ) {
                $data = KonsentrasiJurusan::with('kurikulum')
                    ->whereIn(
                        'kurikulum_id',
                        Kurikulum::whereIn(
                            'program_studi',
                            $fakultasProdi[$fakultas]
                        )->pluck('id')
                    )
                    ->get();
            } else {
                $error = 'Password Dekan salah.';
            }
        }

        return view('admin.dekan.konsentrasi_jurusan', compact(
            'data',
            'fakultas',
            'error'
        ));
    }

    // =====================================================
    // WAREK I - READ ONLY
    // =====================================================
    public function showForWarek1()
    {
        $data = KonsentrasiJurusan::with('kurikulum', 'verifier')->get();

        $data->transform(function ($item) {
            if (is_string($item->sub_konsentrasi)) {
                $decoded = json_decode($item->sub_konsentrasi, true);
                $item->sub_konsentrasi = is_array($decoded)
                    ? $decoded
                    : array_filter(array_map('trim', explode(',', $item->sub_konsentrasi)));
            }

            if ($item->sub_konsentrasi === null) {
                $item->sub_konsentrasi = [];
            }

            return $item;
        });

        return view('admin.warek1.konsentrasi_jurusan', compact('data'));
    }

    // =====================================================
    // AKADEMIK - VERIFIKASI
    // =====================================================
    public function verifikasiIndex()
    {
        $data = KonsentrasiJurusan::with('kurikulum', 'verifier')->get();

        $data->transform(function ($item) {
            if (is_string($item->sub_konsentrasi)) {
                $decoded = json_decode($item->sub_konsentrasi, true);
                $item->sub_konsentrasi = is_array($decoded)
                    ? $decoded
                    : array_filter(array_map('trim', explode(',', $item->sub_konsentrasi)));
            }

            if ($item->sub_konsentrasi === null) {
                $item->sub_konsentrasi = [];
            }

            return $item;
        });

        return view('admin.akademik.konsentrasi_jurusan', compact('data'));
    }

    // =====================================================
    // UPDATE VERIFIKASI (VERSI DIPERBAIKI)
    // =====================================================
    public function verifikasiUpdate(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:disetujui,ditolak',
            'alasan_verifikasi' => $request->status_verifikasi === 'ditolak'
                ? 'required|string'
                : 'nullable|string',
        ]);

        $konsentrasi = KonsentrasiJurusan::findOrFail($id);

        $konsentrasi->update([
            'status_verifikasi' => $request->status_verifikasi,
            'alasan_verifikasi' => $request->alasan_verifikasi,
            'verifikasi_by'     => Auth::id(),
            'verifikasi_at'     => now(),
        ]);

        return redirect()->back()->with('success', 'Status verifikasi berhasil diperbarui');
    }
}
