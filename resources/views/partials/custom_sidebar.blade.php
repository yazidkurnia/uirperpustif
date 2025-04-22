<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold ms-2"> Menu </span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item">
            <a href="{{ route('transaction.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Dashboards">Dashboards</div>
            </a>
        </li>
        {{-- Kegiatan --}}
        <li class="menu-header small text-uppercase"><span class="menu-header-text"> Kegiatan </span></li>
        @if ($title == 'Setting Account')
            <li class="menu-item active">
            @else
            <li class="menu-item">
        @endif
        <!-- Unit -->
        <li class="menu-item">
            <a href="{{ route('data.loaning') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-book-alt"></i>
                <div class="text-truncate" data-i18n="Front Pages">Peminjaman </div>
            </a>
        </li>
        {{-- <li class="menu-item">
            <a href="{{ route('loaning.by.userid') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div class="text-truncate" data-i18n="Front Pages">Peminjaman Anda </div>
            </a>
        </li> --}}
        <li class="menu-item">
            <a href="{{ route('data.return') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-archive-in"></i>
                <div class="text-truncate" data-i18n="Front Pages">Pengembalian </div>
            </a>
        </li>
        @if (Auth::user()->roleid == 1)
            <li class="menu-header small text-uppercase"><span class="menu-header-text"> Laporan </span></li>
            @if ($title == 'Setting Account')
                <li class="menu-item active">
                @else
                <li class="menu-item">
            @endif
            <li class="menu-item">
                <a href="{{ route('report.transaction') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-analyse"></i>
                    <div class="text-truncate" data-i18n="Front Pages">Laporan Peminjaman dan Pengembalian </div>
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span class="menu-header-text"> Confirm Request </span></li>
            @if ($title == 'Setting Account')
                <li class="menu-item active">
                @else
                <li class="menu-item">
            @endif
            <li class="menu-item">
                <a href="{{ route('transaction.approval') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-check-square"></i>
                    <div class="text-truncate" data-i18n="Front Pages">Approval Peminjaman </div>
                </a>
            </li>

            {{-- khusus hak akses admin --}}
            <!-- Setting -->
            <li class="menu-header small text-uppercase"><span class="menu-header-text"> Setting </span></li>
            @if ($title == 'Setting Account')
                <li class="menu-item active">
                @else
                <li class="menu-item">
            @endif

            <a href="#" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div class="text-truncate" data-i18n="Layouts"> Setting </div>
            </a>
            <ul class="menu-sub">

                <li class="menu-item">
                    <a href="{{ route('list.lectures') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Without navbar"> Akun dosen </div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('list.collager') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Without navbar"> Akun mahasiswa </div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.setup.adm.account') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Without navbar"> Setting Account Admin </div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('category.list') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Without navbar"> Kategori </div>
                    </a>
                </li>
            </ul>
            </li>
        @endif
        <li class="menu-header small text-uppercase"><span class="menu-header-text"> Stok Buku </span></li>
        @if ($title == 'Data Stok Buku')
            <li class="menu-item active">
            @else
            <li class="menu-item">
        @endif
        <!-- Unit -->
        <li class="menu-item">
            <a href="{{ route('data.book') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div class="text-truncate" data-i18n="Front Pages">Data Buku </div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('stock.data') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div class="text-truncate" data-i18n="Front Pages">Data Stok </div>
            </a>
        </li>
    </ul>
</aside>
