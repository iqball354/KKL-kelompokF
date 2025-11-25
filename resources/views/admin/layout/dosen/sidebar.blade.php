<div class="sidebar sidebar-dosen">

    <div class="profile">
        <i class="fas fa-user-circle icon-profile"></i>
        <input type="text" readonly value="Dosen" class="profile-name">
    </div>

    <div class="darkmode-icon" onclick="toggleDarkMode()">
        <i id="darkIcon" class="fas fa-sun"></i>
    </div>

    <nav class="menu">
        <a href="{{ route('dosen.dashboard') }}" class="menu-item active">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('keahlian.index') }}" class="menu-item">
            <i class="fas fa-graduation-cap"></i>
            <span>Bidang Keahlian</span>
        </a>
    </nav>

</div>