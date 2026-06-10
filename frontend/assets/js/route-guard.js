const publicPages = [
    "/pages/login.html",
    "/pages/register.html",
    "/pages/index.html"
];

const rolePages = {
    "/pages/admin_dashboard.html": ["admin"],
    "/pages/provider_dashboard.html": ["provider"],
    "/pages/user_dashboard.html": ["user"]
};

const currentPath = window.location.pathname;
const token = localStorage.getItem("token");

if (!token && !publicPages.includes(currentPath)) {
    window.location.replace("/pages/login.html");
} else {
    const user = JSON.parse(localStorage.getItem("user"));

    if (rolePages[currentPath]) {

        if (!user || !rolePages[currentPath].includes(user.role)) {

            switch (user?.role) {
                case "admin":
                    window.location.replace("/pages/admin_dashboard.html");
                    break;

                case "provider":
                    window.location.replace("/pages/provider_dashboard.html");
                    break;

                case "user":
                    window.location.replace("/pages/user_dashboard.html");
                    break;

                default:
                    window.location.replace("/pages/login.html");
            }
        }
    }

    document.body.style.visibility = "visible";
}