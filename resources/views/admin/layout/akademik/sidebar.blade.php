<div class="sidebar">

    <div class="profile">
        <i class="fas fa-user-circle icon-profile"></i>
        <input type="text" readonly value="Akademik" class="profile-name" />
    </div>

    <div class="darkmode-icon" onclick="toggleDarkMode()">
        <i id="darkIcon" class="fas fa-sun"></i>
    </div>

    <nav class="menu">
        <a href="{{ route('akademik.dashboard') }}" class="menu-item {{ request()->routeIs('akademik.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('akademik.keahlian.showForAkademik') }}" class="menu-item {{ request()->routeIs('akademik.keahlian.showForAkademik') ? 'active' : '' }}">
            <i class="fas fa-graduation-cap"></i>
            <span>Dosen</span>
        </a>

        <a href="{{ route('akademik.kurikulum.index') }}" class="menu-item {{ request()->routeIs('akademik.kurikulum.*') ? 'active' : '' }}">
            <i class="far fa-file-alt"></i>
            <span>Kurikulum</span>
        </a>

        <a href="{{ route('akademik.konsentrasi.index') }}" class="menu-item {{ request()->routeIs('akademik.konsentrasi.*') ? 'active' : '' }}">
            <i class="fas fa-tools"></i>
            <span>Konsentrasi Jurusan</span>
        </a>
    </nav>
</div>