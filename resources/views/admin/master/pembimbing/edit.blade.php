<div class="section__content section__content--p30">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-header">
            <strong>Form Edit</strong> Dosen Pembimbing
          </div>

          <div class="card-body card-block">
            @if ($errors->any())
              <div class="alert alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="alert alert-info">
              Password <b>tidak akan diubah</b> saat menyimpan, kecuali Anda mencentang opsi
              <b>Reset Password</b> di bawah. Jika dicentang, password direset menjadi nilai <b>NIK</b> saat ini.
            </div>

            <form action="{{ route('admin.master.pembimbing.update', $pembimbing->id) }}" method="POST" class="form-horizontal">
              @csrf
              @method('PUT')

              {{-- NIK (dipakai jika reset password) --}}
              <div class="form-group row">
                <label class="col-md-3 form-control-label">NIK</label>
                <div class="col-md-9">
                  <input type="text" name="nik"
                         class="form-control @error('nik') is-invalid @enderror"
                         value="{{ old('nik', $pembimbing->nik) }}" required>
                  @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              {{-- Nama --}}
              <div class="form-group row">
                <label class="col-md-3 form-control-label">Nama</label>
                <div class="col-md-9">
                  <input type="text" name="nama"
                         class="form-control @error('nama') is-invalid @enderror"
                         value="{{ old('nama', $pembimbing->nama) }}" required>
                  @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              {{-- Gelar depan & belakang (opsional) --}}
              <div class="form-group row">
                <label class="col-md-3 form-control-label">Gelar Depan</label>
                <div class="col-md-9">
                  <input type="text" name="gelar_depan"
                         class="form-control @error('gelar_depan') is-invalid @enderror"
                         value="{{ old('gelar_depan', $pembimbing->gelar_depan) }}" placeholder="Misal: Dr.">
                  @error('gelar_depan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label">Gelar Belakang</label>
                <div class="col-md-9">
                  <input type="text" name="gelar_belakang"
                         class="form-control @error('gelar_belakang') is-invalid @enderror"
                         value="{{ old('gelar_belakang', $pembimbing->gelar_belakang) }}" placeholder="Misal: M.Kom.">
                  @error('gelar_belakang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              {{-- Prodi --}}
              <div class="form-group row">
                <label class="col-md-3 form-control-label">Prodi</label>
                <div class="col-md-9">
                  <select name="prodi_id" class="form-control @error('prodi_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Prodi --</option>
                    @foreach ($prodi as $p)
                      <option value="{{ $p->id }}"
                        {{ (string)old('prodi_id', $pembimbing->prodi_id) === (string)$p->id ? 'selected' : '' }}>
                        {{ $p->nama }}
                      </option>
                    @endforeach
                  </select>
                  @error('prodi_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  <div class="dropDownSelect2"></div>
                </div>
              </div>

              {{-- Email akun (opsional) --}}
              <div class="form-group row">
                <label class="col-md-3 form-control-label">Email (Akun)</label>
                <div class="col-md-9">
                  <input type="email" name="email"
                         class="form-control @error('email') is-invalid @enderror"
                         value="{{ old('email', optional($pembimbing->user)->email) }}"
                         placeholder="Kosongkan jika tidak diubah">
                  @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              {{-- Reset Password ke NIK --}}
              <div class="form-group row">
                <label class="col-md-3 form-control-label">Reset Password</label>
                <div class="col-md-9 d-flex align-items-center">
                  <div class="form-check">
                    <input type="checkbox" name="reset_password" id="reset_password" class="form-check-input" value="1">
                    <label for="reset_password" class="form-check-label">
                      Setel ulang password menjadi default saat disimpan
                    </label>
                  </div>
                </div>
              </div>

              {{-- Footer --}}
              <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                  <i class="fa fa-check"></i> Perbarui
                </button>
                <a href="{{ route('admin.master.pembimbing.index') }}" class="btn btn-secondary btn-sm">
                  <i class="fa fa-arrow-left"></i> Batal
                </a>
              </div>

            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
