<div class="sidebar">

    <div class="profile">
        <i class="fas fa-user-circle icon-profile"></i>
        <input type="text" readonly value="Akademik" class="profile-name" />
    </div>

    <div class="darkmode-icon" onclick="toggleDarkMode()">
        <i id="darkIcon" class="fas fa-sun"></i>
    </div>

    <nav class="menu">
        <a href="{{ route('akademik.dashboard') }}" class="menu-item active">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-graduation-cap"></i>
            <span>Dosen</span>
        </a>
        <a href="#" class="menu-item">
            <i class="far fa-file-alt"></i>
            <span>Kurikulum</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-tools"></i>
            <span>Konsentrasi Jurusan</span>
        </a>
    </nav>
</div>

