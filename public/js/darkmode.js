function toggleDarkMode() {
    const body = document.body;
    const icon = document.getElementById("darkIcon");

    body.classList.toggle("darkmode");

    if (body.classList.contains("darkmode")) {
        icon.classList.remove("fa-sun");
        icon.classList.add("fa-moon");
        localStorage.setItem("darkmode", "on");
    } else {
        icon.classList.remove("fa-moon");
        icon.classList.add("fa-sun");
        localStorage.setItem("darkmode", "off");
    }
}

// Load mode dari localStorage
window.onload = function () {
    const saved = localStorage.getItem("darkmode");

    if (saved === "on") {
        document.body.classList.add("darkmode");
        document
            .getElementById("darkIcon")
            .classList.replace("fa-sun", "fa-moon");
    }
};

