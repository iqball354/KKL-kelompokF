<!-- ================= HEADER ================= -->
<header class="header">

    <!-- KIRI (Logo + Text) -->
    <div class="header-left">
        <img src="{{ asset('asset/logo-itsm.png') }}" class="logo-header">
        <span class="logo-text">SIAKAD ITSM</span>
    </div>

    <!-- KANAN (Warek + Menu) -->
    <div class="header-right">
        <div class="btn-warek">WAREK</div>

        <div class="menu-wrapper">
            <button class="btn-menu" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </button>

            <div class="menu-dropdown" id="menuDropdown">
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

</header>

<style>
    /* ================= HEADER ================= */
    .header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 56px;
        background: white;
        border-bottom: 1px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
        z-index: 1000;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .logo-header {
        width: 32px;
        height: 32px;
    }

    .logo-text {
        font-family: 'Inknut Antiqua', serif;
        font-size: 1.2rem;
        font-weight: 600;
        color: #4c69f0;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    /* ==== WARNA WAREK UNGU ==== */
    .btn-warek {
        background: #f0e6ff;
        /* ungu muda */
        color: #7d3cff;
        /* ungu tua */
        font-weight: 600;
        font-size: 0.85rem;
        border-radius: 8px;
        padding: 6px 15px;
    }

    /* MENU */
    .btn-menu {
        background: white;
        border: none;
        font-size: 1.3rem;
        color: #555;
        cursor: pointer;
        padding: 6px 12px;
        border-radius: 8px;
        transition: background-color 0.2s ease;
    }

    .btn-menu:hover {
        background-color: #f0f0f0;
    }

    .menu-wrapper {
        position: relative;
    }

    .menu-dropdown {
        position: absolute;
        top: 48px;
        right: 0;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 8px 0;
        width: 140px;
        display: none;
        z-index: 200;
    }

    .menu-dropdown .dropdown-item {
        background: none;
        border: none;
        width: 100%;
        padding: 10px 15px;
        text-align: left;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .menu-dropdown .dropdown-item:hover {
        background: #f0f0f0;
    }
</style>

<script>
    function toggleMenu() {
        const menu = document.getElementById("menuDropdown");
        menu.style.display = (menu.style.display === "block") ? "none" : "block";
    }

    document.addEventListener("click", function(e) {
        const menu = document.getElementById("menuDropdown");
        const btn = document.querySelector(".btn-menu");

        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.style.display = "none";
        }
    });
</script>