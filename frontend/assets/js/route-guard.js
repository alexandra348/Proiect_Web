// ========================
// ROUTE GUARD (AUTH CHECK)
// ========================

const publicPages = [
    "/pages/login.html",
    "/pages/register.html"
];

// ia path-ul curent
const currentPath = window.location.pathname;

// verificăm token
const token = localStorage.getItem("token");

// dacă NU e logat și nu e pe pagină publică → redirect login
if (!token && !publicPages.includes(currentPath)) {
    window.location.href = "/pages/login.html";
}

// opțional: dacă e logat și intră pe login → îl trimitem la home
if (token && currentPath === "/pages/login.html") {
    window.location.href = "/pages/index.html";
}