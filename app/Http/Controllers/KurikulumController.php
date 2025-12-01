<?php

namespace App\Http\Controllers;

use App\Models\Kurikulum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KurikulumController extends Controller
{
    public function index()
    {
        $data = Kurikulum::all();
        return view('admin.akademik.kurikulum', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_identitas' => 'required',
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
            'kode_identitas' => $request->kode_identitas,
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
            'kode_identitas' => 'required',
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
            'kode_identitas' => $request->kode_identitas,
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
}
