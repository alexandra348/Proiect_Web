
export function getUser() {

    const user = localStorage.getItem("user");

    return user
        ? JSON.parse(user)
        : null;
}

export function isLoggedIn() {

    return !!localStorage.getItem("token");
}

export function logout() {

    localStorage.removeItem("token");
    localStorage.removeItem("user");

    window.location.href =
        "/pages/login.html";
}

export function initLoginButton() {

    const container = document.querySelector("#auth-actions");

    if (!container) return;

    if (isLoggedIn()) {

        const user = getUser();

        container.innerHTML = `
            <span>Hello, ${user.name}</span>
            <button id="logout-btn">Logout</button>
        `;

        document.querySelector("#logout-btn").addEventListener("click", logout);

    } else {

        container.innerHTML = `<a href="/pages/login.html">Login</a>`;
    }
}