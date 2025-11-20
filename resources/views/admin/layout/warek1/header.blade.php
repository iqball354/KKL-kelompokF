<header class="header">

    <div class="header-left">
        <img src="{{ asset('asset/logo-itsm.png') }}" class="logo-header">
        <span class="logo-text">SIAKAD ITSM</span>
    </div>

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