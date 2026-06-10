

import {
    getWishlist,
    getTriedDrinks,
    getFavoriteCategories,
    getFavoriteIngredients,
    getAvoidedIngredients,
    getUserRestrictions,
    getFavoriteProviders,

    removeFromWishlist,
    removeTriedDrink,
    removeFavoriteCategory,
    removeFavoriteIngredient,
    removeAvoidedIngredient,
    removeFavoriteProvider
} from "./api.js";

async function reloadDashboard() {

    await initUserDashboard();
}

export async function initUserDashboard() {

    const [
        wishlist,
        tried,
        categories,
        favoriteIngredients,
        avoidedIngredients,
        restrictions,
        providers
    ] = await Promise.all([
        getWishlist(),
        getTriedDrinks(),
        getFavoriteCategories(),
        getFavoriteIngredients(),
        getAvoidedIngredients(),
        getUserRestrictions(),
        getFavoriteProviders()
    ]);

    renderStats(
        wishlist.data,
        tried.data,
        categories.data,
        providers.data
    );

    renderList(
    "#favorite-categories",
    categories.data,
    "name",
    removeFavoriteCategory
    );

    renderList(
        "#favorite-ingredients",
        favoriteIngredients.data,
        "name",
        removeFavoriteIngredient
    );

    renderList(
        "#avoided-ingredients",
        avoidedIngredients.data,
        "name",
        removeAvoidedIngredient
    );


    renderList("#restrictions",restrictions.data,"name");
    renderList("#favorite-providers",providers.data,"name", removeFavoriteProvider);
    renderDrinksWishlist(wishlist.data);
    renderTriedDrinks(tried.data);
}

function renderStats(
    wishlist,
    tried,
    categories,
    providers
) {
    document.querySelector("#wishlist-count").textContent = wishlist.length;
    document.querySelector("#tried-count").textContent = tried.length;
    document.querySelector("#categories-count").textContent = categories.length;
    document.querySelector("#providers-count").textContent = providers.length;
}

function renderList(
    selector,
    items,
    field,
    removeFn,
    idField = "id"
) {

    const container = document.querySelector(selector);
    container.innerHTML = "";

    items.forEach(item => {

        const row =
            document.createElement("div");

        row.classList.add("dashboard-item");

        row.innerHTML = `
            <span class="preference-tag">
                ${item[field]}
            </span>

            ${
                removeFn
                ?
                `<button
                    class="btn btn-danger remove-btn"
                    data-id="${item[idField]}">
                    Remove
                </button>`
                :
                ""
            }
        `;

        container.appendChild(row);
    });

    if(removeFn){

        container
            .querySelectorAll(".remove-btn")
            .forEach(btn => {

                btn.addEventListener(
                    "click",
                    async () => {

                        if(!confirm("Remove this item?")){
                            return;
                        }
                        await removeFn(btn.dataset.id);
                        reloadDashboard();
                    }
                );
            });
    }
}

function renderDrinksWishlist(drinks) {

    const container = document.querySelector("#wishlist");

    container.innerHTML = "";

    const header = document.createElement("div");

    header.classList.add(
        "dashboard-drink",
        "dashboard-drink-wishlist",
        "dashboard-header"
    );

    header.innerHTML = `
        <strong>Name</strong>
        <strong>Price</strong>
        <strong>Provider</strong>
        <strong>Action</strong>
    `;

    container.appendChild(header);

    drinks.forEach(drink => {

        const item =
            document.createElement("div");

        item.classList.add("dashboard-drink", "dashboard-drink-wishlist");

        item.innerHTML = `
            <strong>${drink.name}</strong>
            <span>${drink.price} RON</span>
            <span>${drink.provider}</span>

            <button
                class="btn btn-danger remove-wishlist"
                data-id="${drink.id}">
                Remove
            </button>
        `;

        container.appendChild(item);
    });

    document
        .querySelectorAll(".remove-wishlist")
        .forEach(btn => {

            btn.addEventListener(
                "click",
                async () => {

                    if(!confirm("Remove from wishlist?")) {
                        return;
                    }

                    await removeFromWishlist(btn.dataset.id);

                    reloadDashboard();
                }
            );
        });
}

function renderTriedDrinks(drinks) {

    const container = document.querySelector("#tried-drinks");

    container.innerHTML = "";

    const header = document.createElement("div");

    header.classList.add(
        "dashboard-drink",
        "dashboard-drink-tried",
        "dashboard-header"
    );

    header.innerHTML = `
        <strong>Name</strong>
        <strong>Rating</strong>
        <strong>Comment</strong>
        <strong>Price</strong>
        <strong>Provider</strong>
        <strong>Action</strong>
    `;

    container.appendChild(header);

    drinks.forEach(drink => {

        const item = document.createElement("div");

        item.classList.add("dashboard-drink", "dashboard-drink-tried");

        item.innerHTML = `
            <span>${drink.name}</span>
            <span>${drink.rating ?? "-"}</span>
            <span>${drink.notes ?? "-"}</span>
            <span>${drink.price} RON</span>
            <span>${drink.provider}</span>

            <button
                class="btn btn-danger remove-tried"
                data-id="${drink.id}">
                Remove
            </button>
        `;

        container.appendChild(item);
    });

    document
        .querySelectorAll(".remove-tried")
        .forEach(btn => {

            btn.addEventListener(
                "click",
                async () => {

                    if(
                        !confirm(
                            "Remove tried drink?"
                        )
                    ){
                        return;
                    }

                    await removeTriedDrink(
                        btn.dataset.id
                    );

                    reloadDashboard();
                }
            );
        });
}

import { initLayout } from "./components.js";
await initLayout();

await initUserDashboard();