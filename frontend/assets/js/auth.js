import { login, registerUser } from "./api.js";

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

                window.location.href =
                    "/pages/index.html";

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

// ========================
// REGISTER
// ========================

const registerForm = document.querySelector("#register-form");

if (registerForm) {
    registerForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const data = {
            name: document.querySelector("#name").value,
            email: document.querySelector("#email").value,
            password: document.querySelector("#password").value
        };

        const response = await registerUser(data);

        if (response.status === 201 || response.success) {
            alert("Account created");
            window.location.href = "/pages/login.html";
        } else {
            alert(response.error || response.message || "Register failed");
        }
    });
}