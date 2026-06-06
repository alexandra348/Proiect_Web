import { login } from "./api.js";

// ========================
// LOGIN
// ========================

const loginForm = document.querySelector("#login-form");

if (loginForm) {

    loginForm.addEventListener("submit", async (e) => {

        e.preventDefault();

        const email = document.querySelector("#email").value;
        const password = document.querySelector("#password").value;

        const response = await login(email, password);

        if (response.status === 200 && response.data.token) {

            localStorage.setItem(
                "token",
                response.data.token
            );

            localStorage.setItem(
                "user",
                JSON.stringify(response.data.user)
            );

            const role = response.data.user.role;

            if (role === "admin") {

                window.location.replace("/pages/index.html");

            } else if (role === "provider") {

                window.location.href =
                    "/pages/provider_dashboard.html";

            } else {

                window.location.href =
                    "/pages/index.html";
            }

        } else {

            alert(response.error || response.message || "Login failed");
        }

    });

}
