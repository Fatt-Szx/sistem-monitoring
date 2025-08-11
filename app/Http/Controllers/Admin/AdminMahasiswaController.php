<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\Magang;
use App\Models\Pembimbing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AdminMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        // Reset filter -> kembali ke index tanpa query string
        if ($request->boolean('reset')) {
            return redirect()->route('admin.master.mahasiswa.index');
        }

        $search        = $request->get('search');
        $prodiId       = $request->get('prodi_id');
        $magangId      = $request->get('magang_id');
        $semester      = $request->get('semester');
        $pembimbingId  = $request->get('pembimbing_id');

        $mahasiswa = Mahasiswa::with(['prodi','magang','user','pembimbing'])
            ->when($search, fn($q) => $q->where(fn($qq) =>
                $qq->where('nama','like',"%{$search}%")
                   ->orWhere('nim','like',"%{$search}%")
            ))
            ->when($prodiId,      fn($q) => $q->where('prodi_id',      $prodiId))
            ->when($magangId,     fn($q) => $q->where('magang_id',     $magangId))
            ->when($semester,     fn($q) => $q->where('semester',      $semester))
            ->when($pembimbingId, fn($q) => $q->where('pembimbing_id', $pembimbingId))
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('layouts.wrapper', [
            'title'        => 'Daftar Mahasiswa',
            'content'      => 'admin.master.mahasiswa.index',
            'mahasiswa'    => $mahasiswa,
            'prodi'        => Prodi::orderBy('nama')->get(),
            'magang'       => Magang::orderBy('nama')->get(),
            'pembimbing'   => Pembimbing::orderBy('nama')->get(),
            'search'       => $search,
            'prodi_id'     => $prodiId,
            'magang_id'    => $magangId,
            'semester'     => $semester,
            'pembimbing_id'=> $pembimbingId,
        ]);
    }

    /**
     * Username berbasis NIM (unik); kalau tabrakan, tambah angka.
     */
    private function makeUniqueUsernameFromNim(string $nim, ?int $ignoreUserId = null): string
    {
        $base = strtolower(preg_replace('/[^a-z0-9]/i', '', $nim)) ?: 'user';
        $username = $base;
        $i = 1;

        $exists = User::where('username', $username)
            ->when($ignoreUserId, fn($q) => $q->where('id', '!=', $ignoreUserId))
            ->exists();

        while ($exists) {
            $username = $base.$i;
            $i++;
            $exists = User::where('username', $username)
                ->when($ignoreUserId, fn($q) => $q->where('id', '!=', $ignoreUserId))
                ->exists();
        }

        return $username;
    }

    public function create()
    {
        return view('layouts.wrapper', [
            'title'      => 'Tambah Mahasiswa',
            'content'    => 'admin.master.mahasiswa.create',
            'prodi'      => Prodi::orderBy('nama')->get(),
            'magang'     => Magang::orderBy('nama')->get(),
            'pembimbing' => Pembimbing::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        // Password DIHILANGKAN dari form; default password = "mahasiswa", username = NIM
        $validated = $request->validate([
            'prodi_id'      => ['required','exists:prodis,id'],
            'magang_id'     => ['nullable','exists:magangs,id'],
            'pembimbing_id' => ['nullable','exists:pembimbings,id'],
            'nim'           => ['required','string','max:50', Rule::unique('mahasiswas','nim')],
            'nama'          => ['required','string','max:255'],
            'semester'      => ['required','integer','between:1,14'],
            'email'         => ['nullable','email','max:255','unique:users,email'],
        ]);

        DB::transaction(function () use ($validated) {
            // 1) Email (opsional; auto dari NIM jika kosong)
            $email = $validated['email'] ?? null;
            if (!$email) {
                $base   = Str::lower(preg_replace('/\s+/', '', $validated['nim']));
                $domain = config('app.student_domain', 'student.unimus.ac.id');
                $candidate = "{$base}@{$domain}";
                $i = 1;
                while (User::where('email', $candidate)->exists()) {
                    $candidate = "{$base}+{$i}@{$domain}";
                    $i++;
                }
                $email = $candidate;
            }

            // 2) Default password = "mahasiswa"
            $plainPassword = 'mahasiswa';

            // 3) Buat user (username = NIM unik)
            $user = User::create([
                'name'     => $validated['nama'],
                'username' => $this->makeUniqueUsernameFromNim($validated['nim']),
                'email'    => $email,
                'password' => Hash::make($plainPassword),
            ]);

            // 4) Role 'mahasiswa'
            Role::findOrCreate('mahasiswa');
            $user->syncRoles(['mahasiswa']);

            // 5) Buat Mahasiswa
            Mahasiswa::create([
                'user_id'       => $user->id,
                'prodi_id'      => $validated['prodi_id'],
                'magang_id'     => $validated['magang_id'] ?? null,
                'pembimbing_id' => $validated['pembimbing_id'] ?? null,
                'nim'           => $validated['nim'],
                'nama'          => $validated['nama'],
                'semester'      => $validated['semester'],
            ]);
        });

        return redirect()->route('admin.master.mahasiswa.index')
            ->with('success', 'Mahasiswa + akun user dibuat (username = NIM, password awal = "mahasiswa").');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        return view('layouts.wrapper', [
            'title'      => 'Edit Mahasiswa',
            'content'    => 'admin.master.mahasiswa.edit',
            'mahasiswa'  => $mahasiswa->load(['prodi','magang','user','pembimbing']),
            'prodi'      => Prodi::orderBy('nama')->get(),
            'magang'     => Magang::orderBy('nama')->get(),
            'pembimbing' => Pembimbing::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        // Validasi (tanpa password di form)
        $validated = $request->validate([
            'prodi_id'      => ['required','exists:prodis,id'],
            'magang_id'     => ['nullable','exists:magangs,id'],
            'pembimbing_id' => ['nullable','exists:pembimbings,id'],
            'nim'           => ['required','string','max:50', Rule::unique('mahasiswas','nim')->ignore($mahasiswa->id)],
            'nama'          => ['required','string','max:255'],
            'semester'      => ['required','integer','between:1,14'],
            'email'         => ['nullable','email','max:255', Rule::unique('users','email')->ignore($mahasiswa->user_id)],
            // checkbox 'reset_password' opsional
        ]);

        $resetPassword = $request->boolean('reset_password');

        DB::transaction(function () use ($validated, $mahasiswa, $resetPassword) {
            // Update user (tanpa password, kecuali reset)
            if ($mahasiswa->user) {
                $userData = [
                    'name'     => $validated['nama'],
                    // sinkron username = NIM (unik). Hapus baris ini jika tidak ingin berubah saat NIM diubah.
                    'username' => $this->makeUniqueUsernameFromNim($validated['nim'], $mahasiswa->user_id),
                ];
                if (!empty($validated['email'])) {
                    $userData['email'] = $validated['email'];
                }
                $mahasiswa->user->update($userData);

                // Reset password ke "mahasiswa" jika diminta
                if ($resetPassword) {
                    $mahasiswa->user->forceFill([
                        'password' => Hash::make('mahasiswa'),
                    ])->save();
                }

                Role::findOrCreate('mahasiswa');
                $mahasiswa->user->syncRoles(['mahasiswa']);
            }

            // Update data mahasiswa
            $mahasiswa->update([
                'prodi_id'      => $validated['prodi_id'],
                'magang_id'     => $validated['magang_id'] ?? null,
                'pembimbing_id' => $validated['pembimbing_id'] ?? null,
                'nim'           => $validated['nim'],
                'nama'          => $validated['nama'],
                'semester'      => $validated['semester'],
            ]);
        });

        return redirect()
            ->route('admin.master.mahasiswa.index')
            ->with('success', 'Mahasiswa diperbarui' . ($resetPassword ? ' (password direset ke "mahasiswa").' : '.'));
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();

        return redirect()->route('admin.master.mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
