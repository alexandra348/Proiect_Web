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

        console.log("LOGIN RESPONSE:", response);

        // verificare corectă
        if (response.status === 200 && response.token) {

            localStorage.setItem("token", response.token);

            alert("Login successful");

            window.location.href = "/pages/index.html"; // sau dashboard.html, DAR alege unul
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