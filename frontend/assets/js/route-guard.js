import { getUserRole } from "./api.js";

const publicPages = [
    "/pages/login.html",
    "/pages/register.html",
    "/pages/index.html"
];

const rolePages = {
    "/pages/admin_dashboard.html": ["admin"],
    "/pages/provider_dashboard.html": ["provider", "admin"],
    "/pages/user_dashboard.html": ["user"]
};

const currentPath = window.location.pathname;
const token = localStorage.getItem("token");

(async () => {

    if (!token && !publicPages.includes(currentPath)) {

        window.location.replace("/pages/login.html");
        return;
    }

    if (!token) {

        document.body.style.visibility = "visible";
        return;
    }

    const response = await getUserRole();

    if (!response.success) {

        localStorage.clear();
        window.location.replace("/pages/login.html");
        return;
    }

    const user = {
        role: response.data.role
    };

    if (rolePages[currentPath]) {

        if (!rolePages[currentPath].includes(user.role)) {

            switch (user.role) {

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

            return;
        }
    }

    document.body.style.visibility = "visible";

})();