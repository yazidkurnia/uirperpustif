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
            <a href="/" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Dashboards">Dashboards</div>
            </a>
        </li>
        <!-- Manajemen -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div class="text-truncate" data-i18n="Layouts"> Manajemen </div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="/fuel" class="menu-link">
                        <div class="text-truncate" data-i18n="Without menu">Fuel Ticket</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="/anomali" class="menu-link">
                        <div class="text-truncate" data-i18n="Without navbar">Anomali</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="/ticket" class="menu-link">
                        <div class="text-truncate" data-i18n="Fluid">Ticket Book</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Karyawan -->
        <li class="menu-item">
            <a href="{{ url('/employee') }}" class="menu-link">
                <i class="fa-solid fa-gear"></i>
                <div class="text-truncate" data-i18n="Front Pages"> Karyawan </div>
            </a>
        </li>
        <!-- Unit -->
        <li class="menu-item">
            <a href="/unit" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div class="text-truncate" data-i18n="Front Pages">Unit </div>
            </a>
        </li>
        <!-- Warehouse -->
        <li class="menu-item">
            <a href="/warehouse" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div class="text-truncate" data-i18n="Front Pages"> Warehouse </div>
            </a>
        </li>
        <!-- Setting -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text"> Setting </span></li>
        @if ($title == 'Setting Account')
            <li class="menu-item active">
            @else
            <li class="menu-item">
        @endif

        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-layout"></i>
            <div class="text-truncate" data-i18n="Layouts"> Setting </div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="/hak" class="menu-link">
                    <div class="text-truncate" data-i18n="Without menu"> Hak Akses </div>
                </a>
            </li>
            <li class="menu-item">
                <a href="/approval" class="menu-link">
                    <div class="text-truncate" data-i18n="Without navbar"> Approval </div>
                </a>
            </li>

            @if ($title == 'Setting Account')
                <li class="menu-item active">
                @else
                <li class="menu-item">
            @endif
            <a href="{{ url('/users') }}" class="menu-link">
                <div class="text-truncate" data-i18n="Without navbar"> Users Account </div>
            </a>
            </li>
        </ul>
        </li>
    </ul>
</aside>
