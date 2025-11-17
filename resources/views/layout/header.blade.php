<header class="header">
    <div class="btn-dosen">DOSEN</div>
    <button class="btn-menu" aria-label="Toggle Menu">
        <i class="fas fa-bars"></i>
    </button>
</header>

<style>
    .header {
        position: fixed;
        top: 0; left: 220px; right: 0;
        height: 56px;
        background: white;
        border-bottom: 1px solid #ddd;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        padding: 0 20px;
        z-index: 100;
    }
    .btn-dosen {
        background: #dee8ff;
        color: #4c69f0;
        font-weight: 600;
        font-size: 0.85rem;
        border-radius: 8px;
        padding: 5px 15px;
        margin-right: 12px;
        user-select: none;
        cursor: default;
    }
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
    .btn-menu:focus {
        outline: none;
        box-shadow: 0 0 0 2px #7182D6;
    }
</style>