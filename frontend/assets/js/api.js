// ========================
// CONFIG
// ========================
const API_BASE = "/api";
console.log("API.JS LOADED");

// ========================
// CORE REQUEST FUNCTION
// ========================

async function request(endpoint, options = {}) {

    const token = localStorage.getItem("token");

    options.headers = {
        ...(options.headers || {}),
        "Content-Type": "application/json"
    };

    if (token) {
        options.headers.Authorization =
            `Bearer ${token}`;
    }

    const response = await fetch(
        API_BASE + endpoint,
        options
    );

    const text = await response.text();

    let data;

    try {
        data = JSON.parse(text);
    } catch (e) {
        return {
            status: response.status,
            success: false,
            data: null,
            error: "Invalid JSON response"
        };
    }

    return {
        status: response.status,
        success: response.ok,
        data: data.data ?? data,
        error: data.error || data.error_code
    };
}


// ========================
// DRINKS
// ========================
export async function getDrinks(id = null) {
    return request(id ? `/drinks?id=${id}` : "/drinks");
}

export async function createDrink(data) {
    return request("/drinks", {
        method: "POST",
        body: JSON.stringify(data)
    });
}

export async function deleteDrink(id) {
    return request("/drinks", {
        method: "DELETE",
        body: JSON.stringify({ id })
    });
}

// ========================
// CATEGORIES
// ========================
export async function getCategories() {
    return request("/categories");
}

export async function createCategory(data) {
    return request("/categories", {
        method: "POST",
        body: JSON.stringify(data)
    });
}

export async function deleteCategory(id) {
    return request("/categories", {
        method: "DELETE",
        body: JSON.stringify({ id })
    });
}

// ========================
// INGREDIENTS
// ========================
export async function getIngredients() {
    return request("/ingredients");
}

export async function createIngredient(data) {
    return request("/ingredients", {
        method: "POST",
        body: JSON.stringify(data)
    });
}

// ========================
// PROVIDERS
// ========================
export async function getProviders(id = null) {
    return request(id ? `/providers?id=${id}` : "/providers");
}

export async function createProvider(data) {
    return request("/providers", {
        method: "POST",
        body: JSON.stringify(data)
    });
}

export async function updateProvider(data) {
    return request("/providers", {
        method: "PUT",
        body: JSON.stringify(data)
    });
}

export async function deleteProvider() {
    return request("/providers", {
        method: "DELETE"
    });
}

// ========================
// USERS
// ========================
export async function getUsers(id = null) {
    return request(id ? `/users?id=${id}` : "/users");
}

export async function registerUser(data) {
    return request("/users", {
        method: "POST",
        body: JSON.stringify(data)
    });
}

export async function updateUser(data) {
    return request("/users", {
        method: "PUT",
        body: JSON.stringify(data)
    });
}

export async function deleteUser() {
    return request("/users", {
        method: "DELETE"
    });
}

// ========================
// AUTH
// ========================
export async function login(email, password) {
    const data = await request("/login", {
        method: "POST",
        body: JSON.stringify({ email, password })
    });

    if (data.token) {
        localStorage.setItem("token", data.data.token);
    }

    return data;
}

export function logout() {
    localStorage.removeItem("token");
}

// ========================
// RESTRICTIONS
// ========================
export async function getRestrictions() {
    return request("/restrictions");
}

export async function createRestriction(data) {
    return request("/restrictions", {
        method: "POST",
        body: JSON.stringify(data)
    });
}

// ========================
// STATISTICS
// ========================
export async function getStatistics() {
    return request("/statistics");
}

// ========================
// RECOMMENDATIONS
// ========================
export async function getRecommendations(userId) {
    return request(`/recommendations?user_id=${userId}`);
}