// Toggle Dropdown Menu
function toggleMenu() {
    const menu = document.getElementById("menuDropdown");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

// Tutup menu jika klik di luar
document.addEventListener("click", function (e) {
    const menu = document.getElementById("menuDropdown");
    const btn = document.querySelector(".btn-menu");

    if (menu && btn && !btn.contains(e.target) && !menu.contains(e.target)) {
        menu.style.display = "none";
    }
});

function logout() {
    const form = document.getElementById("logoutForm");
    if (form) form.submit();
}
