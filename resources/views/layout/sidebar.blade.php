<div class="sidebar">
    <div class="logo">
        <img src="{{ asset('asset/logo-itsm.png') }}" alt="Logo SIAKAD ITSM" />
        <span>SIAKAD ITSM</span>
    </div>

    <div class="profile">
        <i class="fas fa-user-circle icon-profile"></i>
        <input type="text" readonly value="Dosen" class="profile-name" />
    </div>

    <nav class="menu">
        <a href="#" class="menu-item active">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-graduation-cap"></i>
            <span>Dosen</span>
        </a>
        <a href="#" class="menu-item">
            <i class="far fa-file-alt"></i>
            <span>kurikulum</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-tools"></i>
            <span>konsentrasi Jurusan</span>
        </a>
    </nav>
</div>

<style>
    .sidebar {
        width: 220px;
        background-color: #7182D6;
        height: 100vh;
        padding: 15px 10px;
        color: white;
        position: fixed;
        top: 0; left: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
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
    .profile {
        width: 100%;
        text-align: center;
        margin-bottom: 30px;
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
        cursor: default;
    }
    .profile-name:focus {
        outline: none;
    }
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
        background-color: rgba(255,255,255,0.3);
    }
</style>