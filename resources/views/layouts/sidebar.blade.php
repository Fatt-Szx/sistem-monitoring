<!-- MENU SIDEBAR-->
<aside class="menu-sidebar d-none d-lg-block">
    <div class="logo">
        <a href="#"><img src="{{ asset('images/icon/Logo.png') }}" alt="Logo" /></a>
    </div>

    <div class="menu-sidebar__content js-scrollbar1">
        <nav class="navbar-sidebar">
            <ul class="list-unstyled navbar__list">

                {{-- ADMIN ONLY --}}
                @role('admin')
                    <li class="{{ Request::is('admin/dashboard*') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                    </li>
                    {{-- Master Data --}}
                    <li class="has-sub {{ Request::is('admin/master/prodi*','admin/master/mahasiswa*','admin/master/pembimbing*','admin/pembimbing/magang*') ? 'active' : '' }}">
                    <a class="js-arrow" href="#"><i class="fas fa-database"></i>Master Data</a>
                    <ul class="list-unstyled navbar__sub-list js-sub-list" style="display: {{ Request::is('admin/master*') ? ' block' : '' }}">
                        <li class="{{ Request::is('admin/master/prodi*') ? 'active' : '' }}">
                            <a href="{{ route('admin.master.prodi.index') }}">Program Studi</a>
                        </li>
                        <li class="{{ Request::is('admin/master/mahasiswa*') ? 'active' : '' }}">
                            <a href="{{ route('admin.master.mahasiswa.index') }}">Mahasiswa</a>
                        </li>
                        <li class="{{ Request::is('admin/master/pembimbing*') ? 'active' : '' }}">
                            <a href="{{ route('admin.master.pembimbing.index') }}">Dosen Pembimbing</a>
                        </li>
                        <li class="{{ Request::is('admin/master/magang*') ? 'active' : '' }}">
                            <a href="{{ route('admin.master.magang.index') }}">Tempat Magang</a>
                        </li>
                    </ul>
                    </li>
                    {{-- Penjadwalan --}}
                    <li class="has-sub {{ Request::is('admin/penjadwalan/jadwal*','admin/penjadwalan/penempatan*') ? 'active' : '' }}">
                    <a class="js-arrow" href="#"><i class="fas fa-database"></i>Master Magang</a>
                    <ul class="list-unstyled navbar__sub-list js-sub-list" style="display: {{ Request::is('admin/penjadwalan*') ? ' block' : '' }}">
                        <li class="{{ Request::is('admin/penjadwalan/jadwal*') ? 'active' : '' }}">
                            <a href="{{ route('admin.penjadwalan.jadwal.index') }}">Atur Jadwal</a>
                        </li>
                        <li class="{{ Request::is('admin/penjadwalan/penempatan*') ? 'active' : '' }}">
                            <a href="{{ route('admin.penjadwalan.penempatan.index') }}">Penempatan</a>
                        </li>
                    </ul>
                    </li>
                    {{-- Laporan --}}
                    <li class="{{ Request::is('admin/laporan*') ? 'active' : '' }}">
                        <a href="{{ route('admin.laporan.index') }}">
                            <i class="fas fa-tachometer-alt"></i>Laporan</a>
                    </li>
                    {{-- Pengaturan --}}
                    <li class="{{ Request::is('admin/pengaturan*') ? 'active' : '' }}">
                        <a href="{{ route('admin.pengaturan.index') }}">
                            <i class="fas fa-tachometer-alt"></i>Pengaturan</a>
                    </li>
                @endrole

                {{-- DOSEN ONLY --}}
                @role('dosen')
                    <li class="{{ Request::is('dosen/dashboard*') ? 'active' : '' }}">
                        <a href="{{ route('dosen.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                    </li>
                    {{-- Mahasiswa Bimbingan --}}
                    <li class="{{ Request::is('dosen/bimgingan*') ? 'active' : '' }}">
                        <a href="{{ route('dosen.bimgingan') }}">
                            <i class="fas fa-tachometer-alt"></i>Mahasiswa Bimbingan</a>
                    </li>
                    {{-- Jadwal --}}
                    <li class="{{ Request::is('dosen/jadwal*') ? 'active' : '' }}">
                        <a href="{{ route('dosen.jadwal') }}">
                            <i class="fas fa-tachometer-alt"></i>Jadwal Magang</a>
                    </li>
                    {{-- Laporan --}}
                    <li class="{{ Request::is('dosen/laporan*') ? 'active' : '' }}">
                        <a href="{{ route('dosen.laporan') }}">
                            <i class="fas fa-tachometer-alt"></i>Laporan</a>
                    </li>
                @endrole

                {{-- MAHASISWA ONLY --}}
                @role('mahasiswa')
                    <li class="{{ Request::is('mahasiswa/dashboard*') ? 'active' : '' }}">
                        <a href="{{ route('mahasiswa.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                    </li>
                    <li class="{{ Request::is('mahasiswa/jadwal*') ? 'active' : '' }}">
                        <a href="{{ route('mahasiswa.jadwal') }}">
                            <i class="fas fa-tachometer-alt"></i>Jadwal</a>
                    </li>
                @endrole

            </ul>
        </nav>
    </div>
</aside>
<!-- END MENU SIDEBAR-->
