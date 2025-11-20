function toggleDarkMode() {
    const body = document.body;
    const icon = document.getElementById("darkIcon");
    const userId = body.dataset.userid;

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
}