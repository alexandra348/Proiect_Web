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
    console.log(text);
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

export async function getDrinkIngredients(id) {
    return request(`/ingredients/drink?id=${id}`);
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
export async function getRecommendations() {
    return request("/recommendations");
}


// PREFERENCES

export async function getWishlist() {
    return request("/preferences/wishlist");
}

export async function addToWishlist(drink_id) {
    return request("/preferences/wishlist", {
        method: "POST",
        body: JSON.stringify({ drink_id })
    });
}

export async function removeFromWishlist(drink_id) {
    return request(`/preferences/wishlist?id=${drink_id}`, {
        method: "DELETE"
    });
}

export async function getTriedDrinks() {
    return request("/preferences/tried");
}

export async function addTriedDrink(drink_id,rating,notes) {
    return request("/preferences/tried", {
        method: "POST",
        body: JSON.stringify({ drink_id, rating, notes })
    });
}

export async function removeTriedDrink(drink_id) {
    return request(`/preferences/tried?id=${drink_id}`, {
        method: "DELETE"
    });
}

export async function getFavoriteCategories() {
    return request("/preferences/categories");
}

export async function addFavoriteCategory(category_id) {
    return request("/preferences/categories", {
        method: "POST",
        body: JSON.stringify({ category_id })
    });
}

export async function removeFavoriteCategory(category_id) {
    return request(`/preferences/categories?id=${category_id}`, {
        method: "DELETE"
    });
}

export async function getFavoriteIngredients() {
    return request("/preferences/favorite-ingredients");
}

export async function addFavoriteIngredient(ingredient_id) {
    return request("/preferences/favorite-ingredients", {
        method: "POST",
        body: JSON.stringify({ ingredient_id })
    });
}

export async function removeFavoriteIngredient(ingredient_id) {
    return request(`/preferences/favorite-ingredients?id=${ingredient_id}`, {
        method: "DELETE"
    });
}

export async function getAvoidedIngredients() {
    return request("/preferences/avoided-ingredients");
}

export async function addAvoidedIngredient(ingredient_id) {
    return request("/preferences/avoided-ingredients", {
        method: "POST",
        body: JSON.stringify({ ingredient_id })
    });
}

export async function removeAvoidedIngredient(ingredient_id) {
    return request(`/preferences/avoided-ingredients?id=${ingredient_id}`, {
        method: "DELETE"
    });
}

export async function getUserRestrictions() {
    return request("/preferences/restrictions");
}

export async function addUserRestriction(restriction_id) {
    return request("/preferences/restrictions", {
        method: "POST",
        body: JSON.stringify({ restriction_id })
    });
}

export async function getFavoriteProviders() {
    return request("/preferences/providers");
}

export async function addFavoriteProvider(provider_id) {
    return request("/preferences/providers", {
        method: "POST",
        body: JSON.stringify({ provider_id })
    });
}

export async function removeFavoriteProvider(provider_id) {
    return request(`/preferences/providers?id=${provider_id}`, {
        method: "DELETE"
    });
}