<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembimbing; // pastikan model ada
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AdminPembimbingController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->get('search');
        $prodiId = $request->get('prodi_id');

        $pembimbing = Pembimbing::with(['prodi','user'])
            ->when($search, fn($q) => $q->where(fn($qq) =>
                $qq->where('nama','like',"%{$search}%")
                   ->orWhere('nik','like',"%{$search}%")
            ))
            ->when($prodiId, fn($q) => $q->where('prodi_id', $prodiId))
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('layouts.wrapper', [
            'title'       => 'Daftar Dosen Pembimbing',
            'content'     => 'admin.master.pembimbing.index',
            'pembimbing'  => $pembimbing,
            'prodi'       => Prodi::orderBy('nama')->get(),
            'search'      => $search,
            'prodi_id'    => $prodiId,
        ]);
    }

    private function makeUniqueUsernameFromNik(string $nik, ?int $ignoreUserId = null): string
    {
        $base = strtolower(preg_replace('/[^a-z0-9]/i', '', $nik)) ?: 'user';
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
            'title'   => 'Tambah Dosen Pembimbing',
            'content' => 'admin.master.pembimbing.create',
            'prodi'   => Prodi::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'prodi_id'       => ['required','exists:prodis,id'],
            'nik'            => ['required','string','max:100', Rule::unique('pembimbings','nik')],
            'nama'           => ['required','string','max:255'],
            'gelar_depan'    => ['nullable','string','max:50'],
            'gelar_belakang' => ['nullable','string','max:50'],
            'email'          => ['nullable','email','max:255','unique:users,email'],
        ]);

        DB::transaction(function () use ($validated) {
            // email (opsional; auto dari NIK jika kosong)
            $email = $validated['email'] ?? null;
            if (!$email) {
                $base   = Str::lower(preg_replace('/\s+/', '', $validated['nik']));
                $domain = config('app.staff_domain', 'unimus.ac.id');
                $candidate = "{$base}@{$domain}";
                $i = 1;
                while (User::where('email', $candidate)->exists()) {
                    $candidate = "{$base}+{$i}@{$domain}";
                    $i++;
                }
                $email = $candidate;
            }

            // >>> Default password = "dosen"
            $plainPassword = 'dosen';

            $user = User::create([
                'name'     => $validated['nama'],
                // >>> Default username = NIK (unik)
                'username' => $this->makeUniqueUsernameFromNik($validated['nik']),
                'email'    => $email,
                'password' => Hash::make($plainPassword),
            ]);

            Role::findOrCreate('pembimbing');
            $user->syncRoles(['pembimbing']);

            Pembimbing::create([
                'user_id'        => $user->id,
                'prodi_id'       => $validated['prodi_id'],
                'nik'            => $validated['nik'],
                'nama'           => $validated['nama'],
                'gelar_depan'    => $validated['gelar_depan'] ?? null,
                'gelar_belakang' => $validated['gelar_belakang'] ?? null,
            ]);
        });

        return redirect()->route('admin.master.pembimbing.index')
            ->with('success', 'Dosen pembimbing + akun user dibuat (username = NIK, password awal = "dosen").');
    }
    public function edit(Pembimbing $pembimbing)
    {
        return view('layouts.wrapper', [
            'title'      => 'Edit Dosen Pembimbing',
            'content'    => 'admin.master.pembimbing.edit',
            'pembimbing' => $pembimbing->load(['prodi','user']),
            'prodi'      => Prodi::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Pembimbing $pembimbing)
    {
        $validated = $request->validate([
            'prodi_id'       => ['required','exists:prodis,id'],
            'nik'            => ['required','string','max:100', Rule::unique('pembimbings','nik')->ignore($pembimbing->id)],
            'nama'           => ['required','string','max:255'],
            'gelar_depan'    => ['nullable','string','max:50'],
            'gelar_belakang' => ['nullable','string','max:50'],
            'email'          => ['nullable','email','max:255', Rule::unique('users','email')->ignore($pembimbing->user_id)],
        ]);

        $resetPassword = $request->boolean('reset_password');

        DB::transaction(function () use ($validated, $pembimbing, $resetPassword) {
            if ($pembimbing->user) {
                $userData = [
                    'name'     => $validated['nama'],
                    // >>> sinkron username ke NIK (unik). Hapus baris ini jika tidak ingin berubah saat edit.
                    'username' => $this->makeUniqueUsernameFromNik($validated['nik'], $pembimbing->user_id),
                ];
                if (!empty($validated['email'])) {
                    $userData['email'] = $validated['email'];
                }
                $pembimbing->user->update($userData);

                // reset password (saat ini: ke NIK; lihat catatan di bawah jika mau ke "dosen")
                if ($resetPassword) {
                    $pembimbing->user->forceFill([
                        'password' => Hash::make('dosen'),
                    ])->save();
                }

                Role::findOrCreate('pembimbing');
                $pembimbing->user->syncRoles(['pembimbing']);
            }

            $pembimbing->update([
                'prodi_id'       => $validated['prodi_id'],
                'nik'            => $validated['nik'],
                'nama'           => $validated['nama'],
                'gelar_depan'    => $validated['gelar_depan'] ?? null,
                'gelar_belakang' => $validated['gelar_belakang'] ?? null,
            ]);
        });

        return redirect()->route('admin.master.pembimbing.index')
            ->with('success', 'Dosen pembimbing diperbarui' . ($resetPassword ? ' (password direset ke default).' : '.'));
    }

    public function destroy(Pembimbing $pembimbing)
    {
        $pembimbing->delete();

        return redirect()->route('admin.master.pembimbing.index')
            ->with('success', 'Dosen pembimbing berhasil dihapus.');
    }
}
