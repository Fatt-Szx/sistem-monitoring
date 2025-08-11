<div class="container-fluid">
  <div class="row">
    <div class="col-md-12" style="top: 15%">
      <h3 class="title-5 m-b-35">{{ $title }}</h3>

      {{-- Toolbar: Search + Filters + Tambah --}}
      <div class="table-data__tool d-flex flex-wrap align-items-center justify-content-between mb-3">
        <form action="{{ route('admin.master.mahasiswa.index') }}" method="GET" class="d-flex flex-wrap align-items-end gap-2 mb-2 mb-md-0">

          {{-- Search --}}
          <div class="mr-2 mb-2">
            <label class="small mb-1 d-block">Cari</label>
            <input type="text" name="search" class="form-control"
                   placeholder="Nama / NIM"
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

          {{-- Filter Pembimbing (baru) --}}
          <div class="mr-2 mb-2">
            <label class="small mb-1 d-block">Dosen Pembimbing</label>
            <select name="pembimbing_id" class="form-control" style="min-width:220px">
              <option value="">-- Semua Pembimbing --</option>
              @foreach ($pembimbing as $d)
                <option value="{{ $d->id }}" {{ (string)request('pembimbing_id', $pembimbing_id ?? '') === (string)$d->id ? 'selected' : '' }}>
                  {{ $d->nama }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Filter Magang --}}
          <div class="mr-2 mb-2">
            <label class="small mb-1 d-block">Tempat Magang</label>
            <select name="magang_id" class="form-control" style="min-width:220px">
              <option value="">-- Semua Tempat --</option>
              @foreach ($magang as $m)
                <option value="{{ $m->id }}" {{ (string)request('magang_id', $magang_id ?? '') === (string)$m->id ? 'selected' : '' }}>
                  {{ $m->nama }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Filter Semester --}}
          <div class="mr-2 mb-2">
            <label class="small mb-1 d-block">Semester</label>
            <select name="semester" class="form-control" style="min-width:120px">
              <option value="">-- Semua --</option>
              @for ($s = 1; $s <= 14; $s++)
                <option value="{{ $s }}" {{ (string)request('semester', $semester ?? '') === (string)$s ? 'selected' : '' }}>{{ $s }}</option>
              @endfor
            </select>
          </div>

          <div class="mb-2 d-flex">
            <button type="submit" class="btn btn-outline-primary mr-2">Terapkan</button>
            @php
              $filters = collect(request()->only(['search','prodi_id','pembimbing_id','magang_id','semester']))->filter();
            @endphp
              <a href="{{ route('admin.master.mahasiswa.index') }}" class="btn btn-light">Reset</a>
          </div>
        </form>

        <div class="table-data__tool-right d-flex">
          <a href="{{ route('admin.master.mahasiswa.create') }}" class="au-btn au-btn-icon au-btn--green au-btn--small" style="align-content: flex-end">
            <i class="zmdi zmdi-plus"></i>Tambah Mahasiswa
          </a>
        </div>
      </div>

      {{-- Flash message --}}
      @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
      @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif

      <div class="table-responsive table-responsive-data2 table-scroll">
        <table class="table table-data2" >
          <thead>
            <tr>
              <th style="width:70px;">No</th>
              <th>NIM</th>
              <th>Nama</th>
              <th>Prodi</th>
              <th>Pembimbing</th> {{-- kolom baru --}}
              <th>Tempat Magang</th>
              <th>Semester</th>
              <th>Email Akun</th>
              <th style="width:140px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
          @forelse ($mahasiswa as $mhs)
            @php
                // rakit nama pembimbing lengkap: "Dr. Nama, M.Kom."
                $pb = $mhs->pembimbing;
                $namaPembimbing = $pb
                ? trim( ($pb->gelar_depan ? $pb->gelar_depan.' ' : '')
                        . $pb->nama
                        . ($pb->gelar_belakang ? ', '.$pb->gelar_belakang : '') )
                : '-';
            @endphp
            <tr class="tr-shadow table-sticky">
              <td>{{ $mahasiswa->firstItem() + $loop->index }}</td>
              <td>{{ $mhs->nim }}</td>
              <td>{{ $mhs->nama }}</td>
              <td>{{ $mhs->prodi->nama ?? '-' }}</td>
              <td>{{ $namaPembimbing }}</td> {{-- tampilkan pembimbing --}}
              <td>{{ $mhs->magang->nama ?? '-' }}</td>
              <td>{{ $mhs->semester }}</td>
              <td>{{ $mhs->user->email ?? '-' }}</td>
              <td>
                <div class="table-data-feature" style="justify-content:flex-start !important">
                  <form action="{{ route('admin.master.mahasiswa.destroy', $mhs->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="item" type="submit" data-toggle="tooltip" title="Hapus"
                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                      <i class="zmdi zmdi-delete"></i>
                    </button>
                  </form>
                  <a href="{{ route('admin.master.mahasiswa.edit', $mhs->id) }}"
                     class="item ml-2" data-toggle="tooltip" title="Edit">
                    <i class="zmdi zmdi-border-color"></i>
                  </a>
                </div>
              </td>
            </tr>
            <tr class="spacer"></tr>
          @empty
            <tr>
              <td colspan="9" class="text-center">Tidak ada data</td>
            </tr>
          @endforelse
          </tbody>
        </table>

        <div class="mt-4 mb-2 d-flex justify-content-center align-items-center">
          <nav>
            {{ $mahasiswa->appends(request()->query())->links('pagination::bootstrap-4') }}
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .table-scroll {
    max-height: 60vh;      
    overflow: auto;        
  }
  .table-sticky thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    background: #fff;      
  } 
  .table-sticky th, .table-sticky td {
    white-space: nowrap;
    vertical-align: middle;
  }
</style>