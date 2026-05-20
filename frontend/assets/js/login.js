import { login } from "./api.js";

document.addEventListener("DOMContentLoaded", () => {

    const form = document.querySelector("#login-form");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const email = document.querySelector("#email").value;
        const password = document.querySelector("#password").value;

        const res = await login(email, password);

        console.log("LOGIN RESPONSE:", res);

        // verificare succes
        if (!res.token) {
            alert(res.message || "Login failed");
            return;
        }

        // salvare token
        localStorage.setItem("token", res.token);

        // redirect la dashboard
        window.location.href = "/pages/index.html";
    });
});