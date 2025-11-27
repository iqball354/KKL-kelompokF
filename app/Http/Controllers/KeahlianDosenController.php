<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KeahlianDosen;
use Illuminate\Support\Facades\Storage;

class KeahlianDosenController extends Controller
{
    // ========================
    // Method untuk Dosen/Admin
    // ========================

    // Tampilkan semua keahlian untuk dosen/admin
    public function index()
    {
        $keahlian = KeahlianDosen::all();
        return view('admin.dosen.keahlian_dosen', compact('keahlian'));
    }

    // Simpan data keahlian baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_dosen' => 'required|string|max:255',
            'bidang_keahlian.*' => 'required|string',
            'dokumen_sertifikat.*' => 'nullable|file|mimes:pdf,jpg,png,docx|max:10240',
            'dokumen_lainnya.*' => 'nullable|file|mimes:pdf,jpg,png,docx|max:10240',
            'dokumen_pendidikan.*' => 'nullable|file|mimes:pdf,jpg,png,docx|max:10240',
            'deskripsi_sertifikat.*' => 'nullable|string',
            'deskripsi_lainnya.*' => 'nullable|string',
            'deskripsi_pendidikan.*' => 'nullable|string',
            'tahun_sertifikat.*' => 'nullable|integer|min:1900|max:2100',
            'tahun_lainnya.*' => 'nullable|integer|min:1900|max:2100',
            'tahun_pendidikan.*' => 'nullable|integer|min:1900|max:2100',
            'link.*' => 'nullable|url',
        ]);

        $data = [
            'nama_dosen' => $request->nama_dosen,
            'bidang_keahlian' => $request->bidang_keahlian,
            'dokumen_sertifikat' => $this->uploadFiles($request->file('dokumen_sertifikat')),
            'dokumen_lainnya' => $this->uploadFiles($request->file('dokumen_lainnya')),
            'dokumen_pendidikan' => $this->uploadFiles($request->file('dokumen_pendidikan')),
            'deskripsi_sertifikat' => $request->deskripsi_sertifikat,
            'deskripsi_lainnya' => $request->deskripsi_lainnya,
            'deskripsi_pendidikan' => $request->deskripsi_pendidikan,
            'tahun_sertifikat' => $request->tahun_sertifikat,
            'tahun_lainnya' => $request->tahun_lainnya,
            'tahun_pendidikan' => $request->tahun_pendidikan,
            'link' => $request->link,
        ];

        KeahlianDosen::create($data);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    // Update data keahlian
    public function update(Request $request, $id)
    {
        $keahlian = KeahlianDosen::findOrFail($id);

        $keahlian->nama_dosen = $request->nama_dosen;
        $keahlian->bidang_keahlian = $request->bidang_keahlian;

        foreach (['sertifikat', 'lainnya', 'pendidikan'] as $type) {
            $existingDocs = $keahlian->{'dokumen_' . $type} ?? [];
            $newDocs = $this->uploadFiles($request->file('dokumen_' . $type)) ?? [];
            $keahlian->{'dokumen_' . $type} = array_merge($existingDocs, $newDocs);

            $keahlian->{'deskripsi_' . $type} = $request->{'deskripsi_' . $type} ?? [];
            $keahlian->{'tahun_' . $type} = $request->{'tahun_' . $type} ?? [];
        }

        $keahlian->link = $request->link ?? [];

        $keahlian->save();

        return redirect()->route('keahlian.index')->with('success', 'Data berhasil diperbarui');
    }

    // Helper untuk upload file
    private function uploadFiles($files)
    {
        if (!$files) return [];
        $result = [];
        foreach ($files as $file) {
            $result[] = $file->store('dokumen', 'public');
        }
        return $result;
    }

    // Hapus keahlian beserta dokumen
    public function destroy($id)
    {
        $keahlian = KeahlianDosen::findOrFail($id);

        foreach (['dokumen_sertifikat', 'dokumen_lainnya', 'dokumen_pendidikan'] as $field) {
            $docs = $keahlian->$field ?? [];
            foreach ($docs as $doc) {
                if (str_starts_with($doc, 'http')) continue;
                Storage::disk('public')->delete($doc);
            }
        }

        $keahlian->delete();
        return back()->with('success', 'Data berhasil dihapus');
    }

    // Hapus dokumen tertentu
    public function deleteDoc($id, $type, $index)
    {
        $keahlian = KeahlianDosen::findOrFail($id);

        $field = 'dokumen_' . $type;
        $desc = 'deskripsi_' . $type;
        $year = 'tahun_' . $type;

        $docs = $keahlian->$field ?? [];
        $descs = $keahlian->$desc ?? [];
        $years = $keahlian->$year ?? [];

        $doc = $docs[$index] ?? null;
        if ($doc && !str_starts_with($doc, 'http')) {
            Storage::disk('public')->delete($doc);
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
    // Method khusus Kaprodi
    // ========================

    // Tampilkan data keahlian untuk Kaprodi
    public function showForKaprodi()
    {
        $keahlian = KeahlianDosen::all();
        return view('admin.kaprodi.keahlian_dosen', compact('keahlian'));
    }

    // Aksi setuju / tolak Kaprodi
    public function aksiKaprodi(Request $request, $id)
    {
        $keahlian = KeahlianDosen::findOrFail($id);

        $request->validate([
            'aksi' => 'required|in:setuju,tolak',
            'nama_dosen' => 'required|string|max:255'
        ]);

        $keahlian->status_kaprodi = $request->aksi === 'setuju' ? 'disetujui' : 'ditolak';
        $keahlian->nama_dosen = $request->nama_dosen;
        $keahlian->save();

        return back()->with('success', 'Aksi Kaprodi berhasil diproses');
    }

    // Hapus data keahlian oleh Kaprodi
    public function hapusKeahlian($id)
    {
        $keahlian = KeahlianDosen::findOrFail($id);

        foreach (['dokumen_sertifikat', 'dokumen_lainnya', 'dokumen_pendidikan'] as $field) {
            $docs = $keahlian->$field ?? [];
            foreach ($docs as $doc) {
                if (!str_starts_with($doc, 'http')) {
                    Storage::disk('public')->delete($doc);
                }
            }
        }

        $keahlian->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }

    // Lihat dokumen Kaprodi
    public function lihatDokumen($id)
    {
        $keahlian = KeahlianDosen::findOrFail($id);
        return view('admin.kaprodi.lihat_dokumen', compact('keahlian'));
    }

    // Kirim ke Akademik
    public function kirimKeAkademik($id)
    {
        $keahlian = KeahlianDosen::findOrFail($id);
        $keahlian->status_akademik = 'dikirim';
        $keahlian->save();

        return back()->with('success', 'Data berhasil dikirim ke Akademik.');
    }

    // ========================
    // Method khusus Akademik
    // ========================
    public function aksiAkademik($id)
    {
        $keahlian = KeahlianDosen::findOrFail($id);
        $keahlian->status_akademik = 'diterima';
        $keahlian->save();

        return back()->with('success', 'Akademik menerima data');
    }

    // Tampilkan data keahlian untuk Dekan
    public function showForDekan()
    {
        $keahlian = KeahlianDosen::all();
        return view('admin.dekan.keahlian_dosen', compact('keahlian'));
    }

    // Tampilkan data keahlian untuk Sdm
    public function showForSdm()
    {
        $keahlian = KeahlianDosen::all();
        return view('admin.sdm.keahlian_dosen', compact('keahlian'));
    }

    // Tampilkan data keahlian untuk Warek 1
    public function showForWarek1()
    {
        $keahlian = KeahlianDosen::all();
        return view('admin.warek1.keahlian_dosen', compact('keahlian'));
    }

    // Tampilkan data keahlian untuk Akademik
    public function showForAkademik()
    {
        $keahlian = KeahlianDosen::all();
        return view('admin.akademik.keahlian_dosen', compact('keahlian'));
    }

    // Tampilkan data keahlian untuk Hrd
    public function showForHrd()
    {
        $keahlian = KeahlianDosen::all();
        return view('admin.hrd.keahlian_dosen', compact('keahlian'));
    }
}
