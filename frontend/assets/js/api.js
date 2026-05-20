// ========================
// CONFIG
// ========================
const API_BASE = "/api";
console.log("API.JS LOADED");

// ========================
// CORE REQUEST FUNCTION
// ========================

/*async function request(endpoint, options = {}) {
    const response = await fetch(API_BASE + endpoint, options);

    const text = await response.text();
    console.log("RAW TEXT:", text);

    const data = JSON.parse(text);

    return {
        status: response.status,
        success: response.ok,
        data: data.data ?? data,
        error: data.error
    };
}*/

async function request(endpoint, options = {}) {
    const response = await fetch(API_BASE + endpoint, options);

    const text = await response.text();
    console.log("RAW TEXT:", text);

    let data;

    try {
        data = JSON.parse(text);
    } catch (e) {
        console.error("Invalid JSON from backend:", text);

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

/*async function request(endpoint, options = {}) {
    const token = localStorage.getItem("token");

    const config = {
        headers: {
            "Content-Type": "application/json",
            ...(token && { Authorization: `Bearer ${token}` })
        },
        ...options
    };

    const response = await fetch(API_BASE + endpoint, config);

    let data;
    try {
        data = await response.json();
    } catch (e) {
        return {
            status: response.status,
            success: false,
            data: null,
            error: "Invalid JSON"
        };
    }

    console.log("FETCH:", API_BASE + endpoint);
    console.log("RAW RESPONSE:", response);

    return {
        status: response.status,
        success: response.ok,
        data: data.data ?? data,   // 🔥 FIX IMPORTANT
        message: data.message,
        error: data.error_code || data.error
    };
}*/
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

export async function deleteUser(id) {
    return request("/users", {
        method: "DELETE",
        body: JSON.stringify({ id })
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
        localStorage.setItem("token", data.token);
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