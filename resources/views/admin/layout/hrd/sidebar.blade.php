<div class="sidebar">

    <!-- LOGO -->
    <div class="logo">
        <img src="{{ asset('asset/logo-itsm.png') }}" alt="Logo SIAKAD ITSM" />
        <span>SIAKAD ITSM</span>
    </div>

    <!-- PROFILE -->
    <div class="profile">
        <i class="fas fa-user-circle icon-profile"></i>
        <input type="text" readonly value="Akademik" class="profile-name" />
    </div>

    <!-- DARKMODE BUTTON -->
    <div class="darkmode-icon" onclick="toggleDarkMode()">
        <i id="darkIcon" class="fas fa-sun"></i>
    </div>

    <!-- MENU -->
    <nav class="menu">
        <a href="{{ route('akademik.dashboard') }}" class="menu-item active">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-graduation-cap"></i>
            <span>Dosen</span>
        </a>
    </nav>
</div>

<style>
    /* =============== SIDEBAR =============== */
    .sidebar {
        width: 220px;
        background-color: #7182D6;
        height: 100vh;
        padding: 15px 10px;
        color: white;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* =============== LOGO =============== */
    .logo {
        width: 100%;
        display: flex;
        align-items: center;
        margin-bottom: 25px;
    }

    .logo img {
        width: 36px;
        height: 36px;
        margin-right: 10px;
    }

    .logo span {
        font-weight: 700;
        font-size: 1.1rem;
    }

    /* =============== PROFILE =============== */
    .profile {
        width: 100%;
        text-align: center;
        margin-bottom: 25px;
    }

    .icon-profile {
        font-size: 3.2rem;
        margin-bottom: 8px;
    }

    .profile-name {
        width: 80%;
        border-radius: 4px;
        border: none;
        padding: 6px 8px;
        font-weight: 600;
        text-align: center;
        background: white;
        color: black;
    }

    .profile-name:focus {
        outline: none;
    }

    /* =============== DARK MODE BUTTON =============== */
    .darkmode-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.35);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        margin-bottom: 25px;
        transition: 0.2s;
    }

    .darkmode-icon:hover {
        background: rgba(255, 255, 255, 0.55);
    }

    .darkmode-icon i {
        font-size: 1.2rem;
        color: white;
    }

    /* =============== MENU =============== */
    .menu {
        width: 100%;
        display: flex;
        flex-direction: column;
    }

    .menu-item {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        border-radius: 6px;
        margin-bottom: 8px;
        transition: background-color 0.2s ease;
    }

    .menu-item i {
        margin-right: 12px;
        min-width: 20px;
        text-align: center;
        font-size: 1.2rem;
    }

    .menu-item.active,
    .menu-item:hover {
        background-color: rgba(255, 255, 255, 0.3);
    }

    /* =============== DARK MODE STYLE GLOBAL =============== */
    .darkmode .sidebar {
        background: #1e1e2d;
    }

    .darkmode .logo span {
        color: #f2f2f2;
    }

    .darkmode .profile-name {
        background: #333;
        color: white;
    }

    .darkmode .darkmode-icon {
        background: rgba(255, 255, 255, 0.2);
    }

    .darkmode .menu-item.active,
    .darkmode .menu-item:hover {
        background: rgba(255, 255, 255, 0.15);
    }
</style>

<script>
    /* =============== DARK MODE SCRIPT =============== */
    function toggleDarkMode() {
        document.body.classList.toggle("darkmode");

        const icon = document.getElementById("darkIcon");

        if (document.body.classList.contains("darkmode")) {
            icon.classList.remove("fa-sun");
            icon.classList.add("fa-moon");
        } else {
            icon.classList.remove("fa-moon");
            icon.classList.add("fa-sun");
        }
    }
</script>