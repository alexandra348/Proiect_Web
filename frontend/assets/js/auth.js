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


            window.location.replace("/pages/index.html");

        } else {

            alert(response.error || response.message || "Login failed");
        }

    });

}
