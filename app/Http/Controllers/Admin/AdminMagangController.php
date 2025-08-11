<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Magang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminMagangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $magang = Magang::query()
            ->when($search, function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('kab', 'like', "%{$search}%")
                  ->orWhere('provinsi', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%");
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('layouts.wrapper', [
            'title'   => 'Daftar Tempat Magang',
            'content' => 'admin.master.magang.index',
            'magang'  => $magang,
            'search'  => $search,
        ]);
    }

    /**
     * Form create.
     */
    public function create()
    {
        return view('layouts.wrapper', [
            'title'   => 'Tambah Tempat Magang',
            'content' => 'admin.master.magang.create',
        ]);
    }

    /**
     * Simpan data.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'            => ['required','string','max:255','unique:magangs,nama'],
            'alamat'          => ['nullable','string','max:255'],
            'kab'             => ['nullable','string','max:100'],
            'provinsi'        => ['nullable','string','max:100'],
            'telepon'         => ['nullable','string','max:50'],
            'tanggal_mulai'   => ['nullable','date'],
            'tanggal_selesai' => ['nullable','date','after_or_equal:tanggal_mulai'],
        ]);

        Magang::create($data);

        return redirect()
            ->route('admin.master.magang.index')
            ->with('success', 'Tempat Magang berhasil ditambahkan.');
    }

    /**
     * Form edit.
     */
    public function edit(Magang $magang)
    {
        return view('layouts.wrapper', [
            'title'   => 'Edit Tempat Magang',
            'content' => 'admin.master.magang.edit',
            'magang'  => $magang,
        ]);
    }

    /**
     * Update data.
     */
    public function update(Request $request, Magang $magang)
    {
        $data = $request->validate([
            'nama'            => ['required','string','max:255', Rule::unique('magangs','nama')->ignore($magang->id)],
            'alamat'          => ['nullable','string','max:255'],
            'kab'             => ['nullable','string','max:100'],
            'provinsi'        => ['nullable','string','max:100'],
            'telepon'         => ['nullable','string','max:50'],
            'tanggal_mulai'   => ['nullable','date'],
            'tanggal_selesai' => ['nullable','date','after_or_equal:tanggal_mulai'],
        ]);

        Magang::whereKey($magang->id)->update($data);

        return redirect()
            ->route('admin.master.magang.index')
            ->with('success', 'Tempat Magang berhasil diperbarui.');
    }

    /**
     * Hapus data.
     */
    public function destroy(Magang $magang)
    {
        $magang->delete();

        return redirect()
            ->route('admin.master.magang.index')
            ->with('success', 'Tempat Magang berhasil dihapus.');
    }
}
