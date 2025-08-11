<div class="section__content section__content--p30">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-header">
            <strong>Form Tambah</strong> Mahasiswa
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
              Akun user akan dibuat otomatis dengan role <b>mahasiswa</b>.
              <br>• <b>Password awal</b> = NIM
              <br>• <b>Email</b> opsional; jika dikosongkan akan dibuat dari NIM (mis. <code>nim@student.local</code>)
            </div>

            <form action="{{ route('admin.master.mahasiswa.store') }}" method="POST" class="form-horizontal">
              @csrf

              {{-- NIM (juga jadi password awal) --}}
              <div class="form-group row">
                <label class="col-md-3 form-control-label">NIM</label>
                <div class="col-md-9">
                  <input type="text" name="nim"
                         class="form-control @error('nim') is-invalid @enderror"
                         value="{{ old('nim') }}" required>
                  @error('nim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  <small class="form-text text-muted">Nilai ini akan menjadi username akun.</small>
                </div>
              </div>

              {{-- Nama --}}
              <div class="form-group row">
                <label class="col-md-3 form-control-label">Nama</label>
                <div class="col-md-9">
                  <input type="text" name="nama"
                         class="form-control @error('nama') is-invalid @enderror"
                         value="{{ old('nama') }}" required>
                  @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              {{-- Prodi --}}
              <div class="form-group row">
                <label class="col-md-3 form-control-label">Prodi</label>
                <div class="col-md-9">
                  <select name="prodi_id" class="form-control @error('prodi_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Prodi --</option>
                    @foreach ($prodi as $p)
                      <option value="{{ $p->id }}" {{ (string)old('prodi_id')===(string)$p->id ? 'selected' : '' }}>
                        {{ $p->nama }}
                      </option>
                    @endforeach
                  </select>
                  @error('prodi_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  <div class="dropDownSelect2"></div>
                </div>
              </div>

              {{-- Dosen Pembimbing (opsional) --}}
            <div class="form-group row">
                <label class="col-md-3 form-control-label">Pembimbing</label>
                <div class="col-md-9">
            <select name="pembimbing_id" class="form-control @error('pembimbing_id') is-invalid @enderror">
            <option value="">-- (Opsional) Pilih Pembimbing --</option>
            @foreach ($pembimbing as $d)
                @php
                $namaLengkap = trim(
                    ($d->gelar_depan ? $d->gelar_depan.' ' : '')
                    . $d->nama
                    . ($d->gelar_belakang ? ', '.$d->gelar_belakang : '')
                );
                @endphp
                <option value="{{ $d->id }}" {{ (string)old('pembimbing_id') === (string)$d->id ? 'selected' : '' }}>
                {{ $namaLengkap }}{{ $d->nik ? ' — '.$d->nik : '' }}
                </option>
            @endforeach
            </select>
            @error('pembimbing_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>


              {{-- Tempat Magang (opsional) --}}
              <div class="form-group row">
                <label class="col-md-3 form-control-label">Tempat Magang</label>
                <div class="col-md-9">
                  <select name="magang_id" class="form-control @error('magang_id') is-invalid @enderror">
                    <option value="">-- (Opsional) Pilih Tempat --</option>
                    @foreach ($magang as $m)
                      <option value="{{ $m->id }}" {{ (string)old('magang_id')===(string)$m->id ? 'selected' : '' }}>
                        {{ $m->nama }}
                      </option>
                    @endforeach
                  </select>
                  @error('magang_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  <div class="dropDownSelect2"></div>
                </div>
              </div>

              {{-- Semester --}}
              <div class="form-group row">
                <label class="col-md-3 form-control-label">Semester</label>
                <div class="col-md-9">
                  <select name="semester" class="form-control @error('semester') is-invalid @enderror" required>
                    <option value="">-- Pilih Semester --</option>
                    @for ($s=1; $s<=14; $s++)
                      <option value="{{ $s }}" {{ (string)old('semester')===(string)$s ? 'selected' : '' }}>{{ $s }}</option>
                    @endfor
                  </select>
                  @error('semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              {{-- Email akun (opsional) --}}
              <div class="form-group row">
                <label class="col-md-3 form-control-label">Email (Akun)</label>
                <div class="col-md-9">
                  <input type="email" name="email"
                         class="form-control @error('email') is-invalid @enderror"
                         value="{{ old('email') }}" placeholder="Kosongkan untuk auto-generate dari NIM">
                  @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              {{-- Footer --}}
              <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                  <i class="fa fa-dot-circle-o"></i> Simpan
                </button>
                <a href="{{ route('admin.master.mahasiswa.index') }}" class="btn btn-secondary btn-sm">
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
