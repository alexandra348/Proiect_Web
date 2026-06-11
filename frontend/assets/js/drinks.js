import {
    getDrinks,
    getWishlist,
    getTriedDrinks,
    addToWishlist,
    removeFromWishlist,
    addTriedDrink,
    removeTriedDrink
} from "./api.js";

import {
    getFavoriteCategories,
    getFavoriteProviders,
    addFavoriteCategory,
    removeFavoriteCategory,
    addFavoriteProvider,
    removeFavoriteProvider
} from "./api.js";

let drinksData = [];

let wishlistIds = new Set();
let triedIds = new Set();

let favoriteCategoryIds = new Set();
let favoriteProviderIds = new Set();

let selectedDrinkId = null;
let selectedButton = null;

export async function initDrinks() {

    const response = await getDrinks();
    if (!response.success) return;
    drinksData = response.data;
    const user = JSON.parse(localStorage.getItem("user"));

    if (user?.role === "user") {

        const [wishlist, tried] = await Promise.all([getWishlist(), getTriedDrinks()]);
        wishlistIds = new Set(wishlist.data.map(d => d.id));
        triedIds = new Set(tried.data.map(d => d.id));

        const [categories, providers] = await Promise.all([getFavoriteCategories(), getFavoriteProviders()]);

        favoriteCategoryIds = new Set(
            categories.data.map(c => c.id)
        );

        favoriteProviderIds = new Set(
            providers.data.map(p => p.id)
        );
    }

    initDOM();
    initTriedModal();
    renderByCategory();
}


function initDOM() {

    const categoriesBtn = document.querySelector("#btn-categories");
    const providersBtn = document.querySelector("#btn-providers");

    if (categoriesBtn) {
        categoriesBtn.addEventListener("click", renderByCategory);
    }

    if (providersBtn) {
        providersBtn.addEventListener("click", renderByProvider);
    }
}


function groupBy(list, key) {

    const grouped = {};

    for (const item of list) {

        const value = item[key];

        if (!grouped[value]) {
            grouped[value] = [];
        }

        grouped[value].push(item);
    }

    return grouped;
}


export function createCard(drink, template, mode) {

    const token = localStorage.getItem("token");
    const user = JSON.parse(localStorage.getItem("user"));

    const clone = template.content.cloneNode(true);

    const image = clone.querySelector(".drink-image");

    if (drink.image_url)
        image.src = drink.image_url;
    else
        image.src = "/uploads/drinks/Drink.jpg"
    image.alt = drink.name;

    clone.querySelector(".drink-name").textContent = drink.name;
    clone.querySelector(".drink-price").textContent = `${drink.price} RON`;

    if (mode === "provider") {
        clone.querySelector(".drink-category").textContent = drink.category || "Category";
        clone.querySelector(".drink-provider").classList.add("hidden");
        
    }
    else if (mode === "category") {
        clone.querySelector(".drink-provider").textContent = drink.provider || "Provider";
        clone.querySelector(".drink-category").classList.add("hidden");
    }
    else {
        clone.querySelector(".drink-category").textContent = drink.category || "Category";
        clone.querySelector(".drink-provider").textContent = drink.provider || "Provider";
    }

    const card = clone.querySelector(".drink-card");

    card.addEventListener("click", () => {

        window.location.replace(`/pages/drink_details.html?id=${drink.id}`);
    });

    const wishlistBtn = clone.querySelector(".wishlist-btn");
    const triedBtn = clone.querySelector(".tried-btn");

    if (!token || user?.role !== "user") {

        wishlistBtn.style.display = "none";
        triedBtn.style.display = "none";
    }
    else {

        if (wishlistIds.has(drink.id)) {

            wishlistBtn.textContent = "♥";
            wishlistBtn.classList.add("active");
        }

        if (triedIds.has(drink.id)) {

            triedBtn.classList.add("active");
        }

        wishlistBtn.addEventListener(
            "click",
            async (e) => {

                e.stopPropagation();

                if (
                    wishlistBtn.classList.contains("active")
                ) {

                    const response = await removeFromWishlist(drink.id);

                    if (response.success) {

                        wishlistIds.delete(drink.id);
                        wishlistBtn.textContent = "♡";
                        wishlistBtn.classList.remove("active");
                    }

                    return;
                }

                const response = await addToWishlist(drink.id);

                if (response.success) {

                    wishlistIds.add(drink.id);
                    wishlistBtn.textContent = "♥";
                    wishlistBtn.classList.add("active");
                }
            }
        );

        triedBtn.addEventListener(
            "click",
            async (e) => {

                e.stopPropagation();

                if (triedBtn.classList.contains("active")) {

                    const response = await removeTriedDrink(drink.id);

                    if (response.success) {

                        triedIds.delete(
                            drink.id
                        );

                        triedBtn.classList.remove(
                            "active"
                        );
                    }

                    return;
                }

                openTriedModal(
                    drink.id,
                    triedBtn
                );
            }
        );
    }

    return clone;
}

export function initTriedModal() {

    const modal = document.querySelector("#tried-modal");

    if (!modal) return;

    document.querySelector("#close-modal")?.addEventListener(
        "click",
        () => {

            modal.classList.add("hidden");
        }
    );

    document.querySelector("#tried-form")?.addEventListener(
        "submit",
        async e => {

            e.preventDefault();

            const rating = Number(document.querySelector("#drink-rating").value);
            const notes = document.querySelector("#drink-comment").value;
            const response = await addTriedDrink(selectedDrinkId, rating, notes);

            if (response.success) {

                triedIds.add(selectedDrinkId);
                selectedButton.classList.add("active");
                modal.classList.add("hidden");
                document.querySelector("#drink-rating").value = "";
                document.querySelector("#drink-comment").value = "";
            }
        }
    );
}

export function openTriedModal(drinkId, button) {

    selectedDrinkId = drinkId;
    selectedButton = button;

    document
        .querySelector("#tried-modal")
        ?.classList.remove("hidden");
}


function renderSections(groupedData, mode) {

    const template = document.querySelector("#drink-card-template");
    const container = document.querySelector("#drinks-container");

    if (!template || !container) return;

    container.innerHTML = "";

    for (const groupName in groupedData) {

        // SECTION
        const section = document.createElement("div");
        section.classList.add("category-section");

        // TITLE
        const titleWrapper = document.createElement("div");
        titleWrapper.classList.add("section-header");

        const title = document.createElement("h2");
        title.classList.add("category-title");
        title.textContent = groupName;

        titleWrapper.appendChild(title);

        const user = JSON.parse(localStorage.getItem("user"));

        if (user?.role === "user") {

            const favoriteBtn = document.createElement("button");

            favoriteBtn.classList.add(
                "favorite-section-btn"
            );

            favoriteBtn.textContent = "♡";

            const firstDrink = groupedData[groupName][0];

            let entityId;

            if (mode === "category") {

                entityId = firstDrink.category_id;

                if (
                    favoriteCategoryIds.has(entityId)
                ) {

                    favoriteBtn.textContent = "♥";
                    favoriteBtn.classList.add("active");
                }

            } else {

                entityId = firstDrink.provider_id;

                if (
                    favoriteProviderIds.has(entityId)
                ) {

                    favoriteBtn.textContent = "♥";
                    favoriteBtn.classList.add("active");
                }
            }

            favoriteBtn.addEventListener(
                "click",
                async () => {

                    if (favoriteBtn.classList.contains("active")) {

                        let response;

                        if (mode === "category") {
                            response = await removeFavoriteCategory(entityId);
                            if (response.success) {

                                favoriteCategoryIds.delete(entityId);
                            }

                        } else {

                            response = await removeFavoriteProvider(entityId);

                            if (response.success) {

                                favoriteProviderIds.delete(entityId);
                            }
                        }

                        if (response.success) {

                            favoriteBtn.textContent = "♡";
                            favoriteBtn.classList.remove("active");
                        }

                        return;
                    }

                    let response;

                    if (mode === "category") {

                        response = await addFavoriteCategory(entityId);

                        if (response.success) {

                            favoriteCategoryIds.add(entityId);
                        }

                    } else {

                        response = await addFavoriteProvider(entityId);

                        if (response.success) {

                            favoriteProviderIds.add(entityId);
                        }
                    }

                    if (response.success) {

                        favoriteBtn.textContent = "♥";

                        favoriteBtn.classList.add("active");
                    }
                }
            );

            titleWrapper.appendChild(favoriteBtn);
        }

        // ROW
        const row = document.createElement("div");
        row.classList.add("category-row");

        // CARDS
        for (const drink of groupedData[groupName]) {
            row.appendChild(createCard(drink, template, mode));
        }

        section.appendChild(titleWrapper);
        section.appendChild(row);

        container.appendChild(section);
    }
}


function renderByCategory() {

    const grouped = groupBy(drinksData, "category");
    renderSections(grouped, "category");
    setActiveButton("category");
}


function renderByProvider() {

    const grouped = groupBy(drinksData, "provider");
    renderSections(grouped, "provider");
    setActiveButton("provider");
}


function setActiveButton(mode) {

    const categoriesBtn = document.querySelector("#btn-categories");

    const providersBtn = document.querySelector("#btn-providers");

    if (mode === "category") {
        categoriesBtn.classList.add("active");
        providersBtn.classList.remove("active");
    }
    else {
        providersBtn.classList.add("active");
        categoriesBtn.classList.remove("active");
    }
}