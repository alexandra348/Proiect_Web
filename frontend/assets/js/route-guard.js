const publicPages = [
    "/pages/login.html",
    "/pages/register.html",
    "/pages/index.html"
];

const currentPath = window.location.pathname;
const token = localStorage.getItem("token");

if (!token && !publicPages.includes(currentPath)) {

    window.location.replace("/pages/login.html");

} else {

    document.body.style.visibility = "visible";
}