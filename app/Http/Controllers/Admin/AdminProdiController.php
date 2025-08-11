<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\Request;

class AdminProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $prodi = Prodi::orderBy('nama')->paginate(10);

        return view('layouts.wrapper', [
            'title'   => 'Daftar Program Studi',
            'content' => 'admin.master.prodi.index',
            'prodi'   => $prodi,
        ]);
    }

    public function create()
    {
        return view('layouts.wrapper', [
            'title'   => 'Tambah Program Studi',
            'content' => 'admin.master.prodi.create',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'      => 'required|string|max:255|unique:prodis,nama',
            'deskripsi' => 'nullable|string',
        ]);

        Prodi::create($data);

        return redirect()->route('admin.master.prodi.index')
            ->with('success', 'Program Studi berhasil ditambahkan.');
    }

    public function edit(Prodi $prodi)
    {
        return view('layouts.wrapper', [
            'title'   => 'Edit Program Studi',
            'content' => 'admin.master.prodi.edit',
            'prodi'   => $prodi,
        ]);
    }

    public function update(Request $request, Prodi $prodi)
    {
        $data = $request->validate([
            'nama'      => 'required|string|max:255|unique:prodis,nama,' . $prodi->id,
            'deskripsi' => 'nullable|string',
        ]);

        $prodi->update($data);

        return redirect()->route('admin.master.prodi.index')
            ->with('success', 'Program Studi berhasil diperbarui.');
    }

    public function destroy(Prodi $prodi)
    {
        $prodi->delete();

        return redirect()->route('admin.master.prodi.index')
            ->with('success', 'Program Studi berhasil dihapus.');
    }
}
