<div class="sidebar">

    <div class="profile">
        <i class="fas fa-user-circle icon-profile"></i>
        <input type="text" readonly value="HRD" class="profile-name" />
    </div>

    <div class="darkmode-icon" onclick="toggleDarkMode()">
        <i id="darkIcon" class="fas fa-sun"></i>
    </div>

    <nav class="menu">
        <a href="{{ route('hrd.dashboard') }}"
            class="menu-item {{ request()->routeIs('hrd.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('hrd.keahlian.showForHrd') }}"
            class="menu-item {{ request()->routeIs('hrd.keahlian.showForHrd') ? 'active' : '' }}">
            <i class="fas fa-graduation-cap"></i>
            <span>Dosen</span>
        </a>
    </nav>

</div>