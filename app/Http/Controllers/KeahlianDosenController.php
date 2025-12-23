<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KeahlianDosen;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class KeahlianDosenController extends Controller
{
    // ========================
    // CRUD Umum Dosen/Admin
    // ========================
    public function index()
    {
        $keahlian = KeahlianDosen::all();
        return view('admin.dosen.keahlian_dosen', compact('keahlian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dosen' => 'required|string|max:255',
            'bidang_keahlian' => 'required|array|min:1',
            'bidang_keahlian.*' => 'required|string|max:255',
            'dokumen_sertifikat.*' => 'nullable|file|mimes:pdf,jpg,png,docx|max:10240',
            'dokumen_lainnya.*' => 'nullable|file|mimes:pdf,jpg,png,docx|max:10240',
            'dokumen_pendidikan.*' => 'nullable|file|mimes:pdf,jpg,png,docx|max:10240',
            'deskripsi_sertifikat.*' => 'nullable|string|max:255',
            'deskripsi_lainnya.*' => 'nullable|string|max:255',
            'deskripsi_pendidikan.*' => 'nullable|string|max:255',
            'tahun_sertifikat.*' => 'nullable|integer|min:1900|max:' . date('Y'),
            'tahun_lainnya.*' => 'nullable|integer|min:1900|max:' . date('Y'),
            'tahun_pendidikan.*' => 'nullable|integer|min:1900|max:' . date('Y'),
            'link.*' => 'nullable|url',
            'deskripsi_link.*' => 'nullable|string|max:255', // validasi deskripsi link
        ]);

        $data = [
            'nama_dosen' => $request->nama_dosen,
            'bidang_keahlian' => $request->bidang_keahlian,
            'dokumen_sertifikat' => $this->uploadFiles($request->file('dokumen_sertifikat')),
            'dokumen_lainnya' => $this->uploadFiles($request->file('dokumen_lainnya')),
            'dokumen_pendidikan' => $this->uploadFiles($request->file('dokumen_pendidikan')),
            'deskripsi_sertifikat' => $request->deskripsi_sertifikat ?? [],
            'deskripsi_lainnya' => $request->deskripsi_lainnya ?? [],
            'deskripsi_pendidikan' => $request->deskripsi_pendidikan ?? [],
            'tahun_sertifikat' => $request->tahun_sertifikat ?? [],
            'tahun_lainnya' => $request->tahun_lainnya ?? [],
            'tahun_pendidikan' => $request->tahun_pendidikan ?? [],
            'link' => $request->link ?? [],
            'deskripsi_link' => $request->deskripsi_link ?? [],
            'status_akademik' => 'menunggu',
        ];

        KeahlianDosen::create($data);

        return back()->with('success', 'Data berhasil disimpan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_dosen' => 'required|string|max:255',
            'bidang_keahlian' => 'required|array|min:1',
            'bidang_keahlian.*' => 'required|string|max:255',
        ]);

        $keahlian = KeahlianDosen::findOrFail($id);

        $keahlian->nama_dosen = $request->nama_dosen;

        // Normalisasi array bidang_keahlian
        $bidang = $request->bidang_keahlian;
        if (is_string($bidang)) {
            $decoded = json_decode($bidang, true);
            $bidang = is_array($decoded) ? $decoded : explode(',', $bidang);
        }
        $keahlian->bidang_keahlian = $bidang;

        foreach (['sertifikat', 'lainnya', 'pendidikan'] as $type) {
            $existing = $keahlian->{'dokumen_' . $type} ?? [];
            $new = $this->uploadFiles($request->file('dokumen_' . $type)) ?? [];
            $keahlian->{'dokumen_' . $type} = array_merge($existing, $new);
            $keahlian->{'deskripsi_' . $type} = $request->{'deskripsi_' . $type} ?? [];
            $keahlian->{'tahun_' . $type} = $request->{'tahun_' . $type} ?? [];
        }

        $keahlian->link = $request->link ?? [];
        $keahlian->deskripsi_link = $request->deskripsi_link ?? [];
        $keahlian->save();

        return back()->with('success', 'Data berhasil diperbarui');
    }

    private function uploadFiles($files)
    {
        if (!$files) return [];
        $result = [];
        foreach ($files as $file) {
            $result[] = $file->store('dokumen', 'public');
        }
        return $result;
    }

    public function destroy($id)
    {
        $keahlian = KeahlianDosen::findOrFail($id);
        foreach (['dokumen_sertifikat', 'dokumen_lainnya', 'dokumen_pendidikan'] as $field) {
            $docs = $keahlian->$field ?? [];
            foreach ($docs as $doc) {
                if (!str_starts_with($doc, 'http')) Storage::disk('public')->delete($doc);
            }
        }
        $keahlian->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }

    public function deleteDoc($id, $type, $index)
    {
        $keahlian = KeahlianDosen::findOrFail($id);
        $field = 'dokumen_' . $type;
        $desc = 'deskripsi_' . $type;
        $year = 'tahun_' . $type;

        $docs = $keahlian->$field ?? [];
        $descs = $keahlian->$desc ?? [];
        $years = $keahlian->$year ?? [];

        if (isset($docs[$index]) && !str_starts_with($docs[$index], 'http')) {
            Storage::disk('public')->delete($docs[$index]);
        }

        if (isset($docs[$index])) {
            array_splice($docs, $index, 1);
            array_splice($descs, $index, 1);
            array_splice($years, $index, 1);
        }

        $keahlian->$field = $docs;
        $keahlian->$desc = $descs;
        $keahlian->$year = $years;
        $keahlian->save();

        return back()->with('success', 'Dokumen berhasil dihapus');
    }

    // ========================
    // Akademik
    // ========================
    public function showAkademik()
    {
        $keahlian = KeahlianDosen::all();

        // Normalisasi array bidang_keahlian
        $keahlian->transform(function ($item) {
            if (is_string($item->bidang_keahlian)) {
                $decoded = json_decode($item->bidang_keahlian, true);
                $item->bidang_keahlian = is_array($decoded)
                    ? $decoded
                    : array_filter(array_map('trim', explode(',', $item->bidang_keahlian)));
            }
            if ($item->bidang_keahlian === null) $item->bidang_keahlian = [];
            return $item;
        });

        return view('admin.akademik.keahlian_dosen', compact('keahlian'));
    }

    public function validasiAkademik(Request $request, $id)
    {
        $request->validate([
            'status_akademik' => 'required|in:disetujui,ditolak',
            'alasan_validasi' => 'nullable|string|max:255',
        ]);

        $keahlian = KeahlianDosen::findOrFail($id);

        $keahlian->status_akademik = $request->status_akademik;
        if ($request->filled('alasan_validasi')) {
        $keahlian->alasan_validasi = $request->alasan_validasi;
    }
        $keahlian->validasi_by = Auth::id();
        $keahlian->validasi_at = now();
        $keahlian->save();

        return back()->with('success', 'Validasi berhasil diproses');
    }

    // ========================
    // Role-based sesuai route
    // ========================
    public function showForKaprodi()
    {
        $keahlian = KeahlianDosen::all();
        return view('admin.kaprodi.keahlian_dosen', compact('keahlian'));
    }

    public function showForDekan()
    {
        $keahlian = KeahlianDosen::all();
        return view('admin.dekan.keahlian_dosen', compact('keahlian'));
    }

    public function showForSdm()
    {
        $keahlian = KeahlianDosen::all();
        return view('admin.sdm.keahlian_dosen', compact('keahlian'));
    }

    public function showForWarek1()
    {
        $keahlian = KeahlianDosen::all();
        return view('admin.warek1.keahlian_dosen', compact('keahlian'));
    }

    public function showForHrd()
    {
        $keahlian = KeahlianDosen::all();
        return view('admin.hrd.keahlian_dosen', compact('keahlian'));
    }
}
