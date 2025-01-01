window.addEventListener("load", function() {
    if (localStorage.getItem("color-theme")) {
        if (localStorage.getItem("color-theme") === "dark") {
            document.documentElement.classList.add("dark");
            localStorage.setItem("color-theme", "dark");
        } else {
            document.documentElement.classList.remove("light");
            localStorage.setItem("color-theme", "light");
        }
    } else {
        document.documentElement.classList.remove("light");
        localStorage.setItem("color-theme", "light");
    }
});