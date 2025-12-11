<div class="sidebar">

    <div class="profile">
        <i class="fas fa-user-circle icon-profile"></i>
        <input type="text" readonly value="Dekan" class="profile-name" />
    </div>

    <div class="darkmode-icon" onclick="toggleDarkMode()">
        <i id="darkIcon" class="fas fa-sun"></i>
    </div>

    <nav class="menu">
        <a href="{{ route('dekan.dashboard') }}"
            class="menu-item {{ request()->routeIs('dekan.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('dekan.keahlian.showForDekan') }}"
            class="menu-item {{ request()->routeIs('dekan.keahlian.showForDekan') ? 'active' : '' }}">
            <i class="fas fa-graduation-cap"></i>
            <span>Dosen</span>
        </a>

        <a href="{{ route('dekan.kurikulum.showForDekan') }}"
            class="menu-item {{ request()->routeIs('dekan.kurikulum.*') ? 'active' : '' }}">
            <i class="far fa-file-alt"></i>
            <span>Kurikulum</span>
        </a>

        <a href="{{ route('dekan.konsentrasi.show') }}"
            class="menu-item {{ request()->routeIs('dekan.konsentrasi.*') ? 'active' : '' }}">
            <i class="fas fa-tools"></i>
            <span>Konsentrasi Jurusan</span>
        </a>
    </nav>

</div>
