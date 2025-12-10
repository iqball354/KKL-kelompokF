<div class="sidebar">

    <div class="profile">
        <i class="fas fa-user-circle icon-profile"></i>
        <input type="text" readonly value="Warek 1" class="profile-name" />
    </div>

    <div class="darkmode-icon" onclick="toggleDarkMode()">
        <i id="darkIcon" class="fas fa-sun"></i>
    </div>

    <nav class="menu">
        <a href="{{ route('warek1.dashboard') }}"class="menu-item {{ request()->routeIs('warek1.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('warek1.keahlian.showForWarek1') }}"class="menu-item {{ request()->routeIs('warek1.keahlian.*') ? 'active' : '' }}">
            <i class="fas fa-graduation-cap"></i>
            <span>Dosen</span>
        </a>

        <a href="{{ route('warek1.kurikulum.showForWarek1') }}" class="menu-item {{ request()->routeIs('warek1.kurikulum.*') ? 'active' : '' }}">
            <i class="far fa-file-alt"></i>
            <span>Kurikulum</span>
        </a>

        <a href="#"class="menu-item {{ request()->routeIs('warek1.konsentrasi.*') ? 'active' : '' }}">
            <i class="fas fa-tools"></i>
            <span>Konsentrasi Jurusan</span>
        </a>
    </nav>

</div>