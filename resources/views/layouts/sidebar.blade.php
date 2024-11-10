<div class="sidebar">
    <!-- SidebarSearch Form -->
    <div class="form-inline mt-2">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link  {{ $activeMenu == 'dashboard' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-header">Data Pengguna</li>
            <li class="nav-item">
                <a href="{{ url('/dosen') }}" class="nav-link {{ $activeMenu == 'dosen' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-user"></i>
                    <p>Dosen</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/manajemen') }}" class="nav-link {{ $activeMenu == 'manajemen' ? 'active' : '' }}">
                    <i class="nav-icon far fa-user"></i>
                    <p>Manajemen</p>
                </a>
            </li>
            <li class="nav-header">Data Penugasan</li>
            <li class="nav-item">
                <a href="{{ url('/penugasan') }}" class="nav-link {{ $activeMenu == 'penugasan' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-file"></i>
                    <p>Penugasan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/logout') }}" class="nav-link {{ $activeMenu == 'logout' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-door-open"></i>
                    <p>Logout</p>
                </a>
            </li>
        </ul>
    </nav>
</div>
