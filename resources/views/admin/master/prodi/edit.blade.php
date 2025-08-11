<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
      <h3 class="title-5 m-b-35">{{ $title }}</h3>

      <div class="card">
        <div class="card-body card-block">
          <form action="{{ route('admin.master.prodi.update', $prodi->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="form-group">
              <label for="nama" class="form-control-label">Nama Prodi</label>
              <input type="text" name="nama" id="nama"
                     class="form-control @error('nama') is-invalid @enderror"
                     required placeholder="Contoh: Informatika"
                     value="{{ old('nama', $prodi->nama) }}">
              @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
              <label for="deskripsi" class="form-control-label">Deskripsi</label>
              <textarea name="deskripsi" id="deskripsi" rows="4"
                        class="form-control @error('deskripsi') is-invalid @enderror"
                        placeholder="Deskripsi singkat (opsional)...">{{ old('deskripsi', $prodi->deskripsi) }}</textarea>
              @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-primary">
                <i class="zmdi zmdi-check"></i> Perbarui
              </button>
              <a href="{{ route('admin.master.prodi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>
