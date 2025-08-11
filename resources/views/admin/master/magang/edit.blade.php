<div class="section__content section__content--p30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header">
                        <strong>Form Edit</strong> Tempat Magang
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

                        <form action="{{ route('admin.master.magang.update', $magang->id) }}" method="POST" class="form-horizontal">
                            @csrf
                            @method('PUT')

                            {{-- Nama Tempat (wajib) --}}
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Nama Tempat</label>
                                <div class="col-md-9">
                                    <input type="text" name="nama"
                                           class="form-control @error('nama') is-invalid @enderror"
                                           value="{{ old('nama', $magang->nama) }}" required
                                           placeholder="Contoh: PT Maju Jaya">
                                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Alamat --}}
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Alamat</label>
                                <div class="col-md-9">
                                    <textarea name="alamat" rows="3"
                                              class="form-control @error('alamat') is-invalid @enderror"
                                              placeholder="Jl. Contoh No. 123, Kec. Contoh">{{ old('alamat', $magang->alamat) }}</textarea>
                                    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Kab/Kota --}}
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Kab/Kota</label>
                                <div class="col-md-9">
                                    <input type="text" name="kab"
                                           class="form-control @error('kab') is-invalid @enderror"
                                           value="{{ old('kab', $magang->kab) }}" placeholder="Contoh: Sragen">
                                    @error('kab') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Provinsi --}}
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Provinsi</label>
                                <div class="col-md-9">
                                    <input type="text" name="provinsi"
                                           class="form-control @error('provinsi') is-invalid @enderror"
                                           value="{{ old('provinsi', $magang->provinsi) }}" placeholder="Contoh: Jawa Tengah">
                                    @error('provinsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Telepon --}}
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Telepon</label>
                                <div class="col-md-9">
                                    <input type="text" name="telepon"
                                           class="form-control @error('telepon') is-invalid @enderror"
                                           value="{{ old('telepon', $magang->telepon) }}" placeholder="Contoh: 021-123456 / 0812xxxxxxx">
                                    @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Tanggal Mulai --}}
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Tanggal Mulai</label>
                                <div class="col-md-9">
                                    <input type="date" name="tanggal_mulai"
                                           class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                           value="{{ old('tanggal_mulai', optional($magang->tanggal_mulai)->format('Y-m-d')) }}">
                                    @error('tanggal_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Tanggal Selesai --}}
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Tanggal Selesai</label>
                                <div class="col-md-9">
                                    <input type="date" name="tanggal_selesai"
                                           class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                           value="{{ old('tanggal_selesai', optional($magang->tanggal_selesai)->format('Y-m-d')) }}">
                                    @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <small class="form-text text-muted">
                                        Opsional. Harus &ge; Tanggal Mulai jika diisi.
                                    </small>
                                </div>
                            </div>

                            {{-- Footer --}}
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-check"></i> Perbarui
                                </button>
                                <a href="{{ route('admin.master.magang.index') }}" class="btn btn-secondary btn-sm">
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
