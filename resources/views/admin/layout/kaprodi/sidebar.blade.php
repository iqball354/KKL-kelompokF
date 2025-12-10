<div class="sidebar sidebar-kaprodi">

    <div class="profile">
        <i class="fas fa-user-circle icon-profile"></i>
        <input type="text" readonly value="Kaprodi" class="profile-name" />
    </div>

    <div class="darkmode-icon" onclick="toggleDarkMode()">
        <i id="darkIcon" class="fas fa-sun"></i>
    </div>

    <nav class="menu">
        <a href="{{ route('kaprodi.dashboard') }}"
            class="menu-item {{ request()->routeIs('kaprodi.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('kaprodi.keahlian.show') }}"
            class="menu-item {{ request()->routeIs('kaprodi.keahlian.show') ? 'active' : '' }}">
            <i class="fas fa-graduation-cap"></i>
            <span>Dosen</span>
        </a>

        <a href="{{ route('kaprodi.kurikulum.showForKaprodi') }}"
            class="menu-item {{ request()->routeIs('kaprodi.kurikulum.*') ? 'active' : '' }}">
            <i class="far fa-file-alt"></i>
            <span>Kurikulum</span>
        </a>

        <a href="{{ route('kaprodi.konsentrasi.index') }}"
            class="menu-item {{ request()->routeIs('kaprodi.konsentrasi.*') ? 'active' : '' }}">
            <i class="fas fa-tools"></i>
            <span>Konsentrasi Jurusan</span>
        </a>
    </nav>

</div>