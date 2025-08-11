<div class="container-fluid">
  <div class="row">
    <div class="col-md-12" style="top: 15%">
      <h3 class="title-5 m-b-35">{{ $title }}</h3>

      <div class="table-data__tool" style="justify-content: flex-end">
        <div class="table-data__tool-right">
          <a href="{{ route('admin.master.prodi.create') }}" class="au-btn au-btn-icon au-btn--green au-btn--small">
            <i class="zmdi zmdi-plus"></i>Tambah Prodi
          </a>
        </div>
      </div>

      <div class="table-responsive table-responsive-data2">
        <table class="table table-data2">
          <thead>
            <tr>
              <th style="width:70px;">No</th>
              <th>Nama Prodi</th>
              <th>Deskripsi</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
          @forelse ($prodi as $item)
            <tr class="tr-shadow">
              <td>{{ $prodi->firstItem() + $loop->index }}</td>
              <td>{{ $item->nama }}</td>
              <td>{{ $item->deskripsi }}</td>
              <td>
                <div class="table-data-feature" style="justify-content:flex-start !important">
                  <form action="{{ route('admin.master.prodi.destroy', $item->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="item" type="submit" data-toggle="tooltip" title="Hapus"
                            onclick="return confirm('Yakin ingin menghapus?')">
                      <i class="zmdi zmdi-delete"></i>
                    </button>
                  </form>
                  <a href="{{ route('admin.master.prodi.edit', $item->id) }}" class="item ml-2" data-toggle="tooltip" title="Edit">
                    <i class="zmdi zmdi-border-color"></i>
                  </a>
                </div>
              </td>
            </tr>
            <tr class="spacer"></tr>
          @empty
            <tr>
              <td colspan="4" class="text-center">Tidak ada data</td>
            </tr>
          @endforelse
          </tbody>
        </table>

        <div class="mt-4 mb-2 d-flex justify-content-center align-items-center">
          <nav>
            {{ $prodi->appends(request()->query())->links('pagination::bootstrap-4') }}
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
