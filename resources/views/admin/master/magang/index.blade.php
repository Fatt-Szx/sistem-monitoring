<div class="container-fluid">
  <div class="row">
    <div class="col-md-12" style="top: 15%">
      <h3 class="title-5 m-b-35">{{ $title }}</h3>

      {{-- Toolbar: Search + Tambah --}}
      <div class="table-data__tool d-flex flex-wrap align-items-center justify-content-between mb-3">
        <form action="{{ route('admin.master.magang.index') }}" method="GET" class="d-flex align-items-center mb-2 mb-md-0">
          <input
            type="text"
            name="search"
            class="form-control mr-2"
            placeholder="Cari nama/alamat/kab/provinsi/telepon..."
            value="{{ request('search', $search ?? '') }}"
            style="min-width:280px"
          >
          <button type="submit" class="btn btn-outline-primary">Cari</button>
          @if(request()->filled('search'))
            <a href="{{ route('admin.master.magang.index') }}" class="btn btn-light ml-2">Reset</a>
          @endif
        </form>

        <div class="table-data__tool-right">
          <a href="{{ route('admin.master.magang.create') }}" class="au-btn au-btn-icon au-btn--green au-btn--small">
            <i class="zmdi zmdi-plus"></i>Tambah Tempat Magang
          </a>
        </div>
      </div>

      {{-- Flash message --}}
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <div class="table-responsive table-responsive-data2">
        <table class="table table-data2">
          <thead>
            <tr>
              <th style="width:70px;">No</th>
              <th>Nama Tempat</th>
              <th>Alamat</th>
              <th>Kab/Kota</th>
              <th>Provinsi</th>
              <th>Telepon</th>
              <th>Mulai</th>
              <th>Selesai</th>
              <th style="width:140px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
          @forelse ($magang as $item)
            <tr class="tr-shadow">
              <td>{{ $magang->firstItem() + $loop->index }}</td>
              <td>{{ $item->nama }}</td>
              <td>{{ $item->alamat ?? '-' }}</td>
              <td>{{ $item->kab ?? '-' }}</td>
              <td>{{ $item->provinsi ?? '-' }}</td>
              <td>{{ $item->telepon ?? '-' }}</td>
              <td>{{ $item->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
              <td>{{ $item->tanggal_selesai?->format('d/m/Y') ?? '-' }}</td>
              <td>
                <div class="table-data-feature" style="justify-content:flex-start !important">
                  <form action="{{ route('admin.master.magang.destroy', $item->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="item" type="submit" data-toggle="tooltip" title="Hapus"
                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                      <i class="zmdi zmdi-delete"></i>
                    </button>
                  </form>
                  <a href="{{ route('admin.master.magang.edit', $item->id) }}"
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
            {{ $magang->appends(request()->query())->links('pagination::bootstrap-4') }}
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
