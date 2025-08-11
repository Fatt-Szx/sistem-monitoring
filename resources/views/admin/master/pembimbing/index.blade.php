<div class="container-fluid">
  <div class="row">
    <div class="col-md-12" style="top: 15%">
      <h3 class="title-5 m-b-35">{{ $title }}</h3>

      {{-- Toolbar: Search + Filter Prodi + Tambah --}}
      <div class="table-data__tool d-flex flex-wrap align-items-center justify-content-between mb-3">
        <form action="{{ route('admin.master.pembimbing.index') }}" method="GET" class="d-flex flex-wrap align-items-end gap-2 mb-2 mb-md-0">

          {{-- Search --}}
          <div class="mr-2 mb-2">
            <label class="small mb-1 d-block">Cari</label>
            <input type="text" name="search" class="form-control"
                   placeholder="Nama / NIK"
                   value="{{ request('search', $search ?? '') }}"
                   style="min-width:220px">
          </div>

          {{-- Filter Prodi --}}
          <div class="mr-2 mb-2">
            <label class="small mb-1 d-block">Prodi</label>
            <select name="prodi_id" class="form-control" style="min-width:220px">
              <option value="">-- Semua Prodi --</option>
              @foreach ($prodi as $p)
                <option value="{{ $p->id }}" {{ (string)request('prodi_id', $prodi_id ?? '') === (string)$p->id ? 'selected' : '' }}>
                  {{ $p->nama }}
                </option>
              @endforeach
            </select>
          </div>

          @php
            $filters = collect(request()->only(['search','prodi_id']))->filter();
          @endphp
          <div class="mb-2 d-flex">
            <button type="submit" class="btn btn-outline-primary mr-2">Terapkan</button>
            @if($filters->isNotEmpty())
              <a href="{{ route('admin.master.pembimbing.index') }}" class="btn btn-light">Reset</a>
            @endif
          </div>
        </form>

        <div class="table-data__tool-right">
          <a href="{{ route('admin.master.pembimbing.create') }}" class="au-btn au-btn-icon au-btn--green au-btn--small">
            <i class="zmdi zmdi-plus"></i>Tambah Pembimbing
          </a>
        </div>
      </div>

      {{-- Flash message --}}
      @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
      @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif

      <div class="table-responsive table-responsive-data2">
        <table class="table table-data2">
          <thead>
            <tr>
              <th style="width:70px;">No</th>
              <th>NIK</th>
              <th>Nama</th>
              <th>Prodi</th>
              <th>Email Akun</th>
              <th style="width:140px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
          @forelse ($pembimbing as $dosen)
            @php
              $namaLengkap = trim(
                ($dosen->gelar_depan ? $dosen->gelar_depan.' ' : '')
                . $dosen->nama
                . ($dosen->gelar_belakang ? ', '.$dosen->gelar_belakang : '')
              );
            @endphp
            <tr class="tr-shadow">
              <td>{{ $pembimbing->firstItem() + $loop->index }}</td>
              <td>{{ $dosen->nik }}</td>
              <td>{{ $namaLengkap }}</td>
              <td>{{ $dosen->prodi->nama ?? '-' }}</td>
              <td>{{ $dosen->user->email ?? '-' }}</td>
              <td>
                <div class="table-data-feature" style="justify-content:flex-start !important">
                  <form action="{{ route('admin.master.pembimbing.destroy', $dosen->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="item" type="submit" data-toggle="tooltip" title="Hapus"
                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                      <i class="zmdi zmdi-delete"></i>
                    </button>
                  </form>
                  <a href="{{ route('admin.master.pembimbing.edit', $dosen->id) }}"
                     class="item ml-2" data-toggle="tooltip" title="Edit">
                    <i class="zmdi zmdi-border-color"></i>
                  </a>
                </div>
              </td>
            </tr>
            <tr class="spacer"></tr>
          @empty
            <tr>
              <td colspan="6" class="text-center">Tidak ada data</td>
            </tr>
          @endforelse
          </tbody>
        </table>

        <div class="mt-4 mb-2 d-flex justify-content-center align-items-center">
          <nav>
            {{ $pembimbing->appends(request()->query())->links('pagination::bootstrap-4') }}
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
