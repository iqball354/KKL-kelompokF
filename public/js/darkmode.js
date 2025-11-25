// darkmode.js
document.addEventListener("DOMContentLoaded", () => {
    const body = document.body;
    const icon = document.getElementById("darkIcon");
    const userId = body.dataset.userid;

    // Cek localStorage saat halaman load
    const darkModeStatus = localStorage.getItem("darkmode_" + userId);
    if (darkModeStatus === "on") {
        body.classList.add("darkmode");
        icon.classList.remove("fa-sun");
        icon.classList.add("fa-moon");
    }

    // Fungsi toggle dark mode
    window.toggleDarkMode = function () {
        body.classList.toggle("darkmode");

        if (body.classList.contains("darkmode")) {
            icon.classList.remove("fa-sun");
            icon.classList.add("fa-moon");
            localStorage.setItem("darkmode_" + userId, "on");
        } else {
            icon.classList.remove("fa-moon");
            icon.classList.add("fa-sun");
            localStorage.setItem("darkmode_" + userId, "off");
        }
    };
});
