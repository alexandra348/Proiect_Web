import {
    getProviderDrinks,
    deleteDrink,
    getCategories,
    getIngredients,
    createDrink,
    addIngredientToDrink,
    updateDrink,
    getDrinkIngredients,
    deleteIngredientFromDrink,
    getProviders,
    getUserRole
}
    from "./api.js";

import { initLayout } from "./components.js";
await initLayout();

const response = await getUserRole();

if (!response.success) {

        localStorage.clear();
        window.location.replace("/pages/login.html");
    }

const user = { role: response.data.role};

const params = new URLSearchParams(window.location.search);
const providerId = params.get("id");

if (user?.role === "admin" && !providerId) {

    window.location.replace(
        "/pages/admin_dashboard.html?tab=drinks"
    );
}

export async function initProviderDashboard() {

    const response = await getProviderDrinks(providerId);

    if (!response.success) return;

    document
    .querySelector("#close-ingredients-modal")
    ?.addEventListener(
        "click",
        () => {

            document
                .querySelector("#ingredients-modal")
                .classList.add("hidden");
        }
    );

    if (user.role === "admin" && response.data.length) {

        const provider = await getProviders(providerId);
        document.querySelector("#dashboard-title").textContent =`${provider.data.name} Drinks`;
    }

    renderStats(response.data);
    renderDrinks(response.data);
    initAddDrinkModal();
}

function renderStats(drinks) {

    document.querySelector("#drinks-count").textContent = drinks.length;
}

function initAddDrinkModal() {

    document
        .querySelector("#add-drink-btn")
        ?.addEventListener(
            "click",
            () => {

                document.querySelector("#drink-modal")?.classList.remove("hidden");
            }
        );

    initCategories();
    initIngredients();
    initIngredientsDropdown();
    initIngredientsSelection();
    document
        .querySelector("#close-drink-modal")
        ?.addEventListener(
            "click",
            () => {

                document.querySelector("#drink-modal")?.classList.add("hidden");
            }
        );

    document.querySelector("#drink-form")?.addEventListener(
        "submit",
        saveDrink
    );

}

let editingDrinkId = null;
let currentDrinkId = null;

function openDrinkModal(drink = null) {

    document.querySelector("#drink-modal").classList.remove("hidden");

    if (drink) {

        editingDrinkId = drink.id;

        document.querySelector("#drink-name").value = drink.name;
        document.querySelector("#drink-price").value = drink.price;
        document.querySelector("#drink-category").value = drink.category_id;
    } else {
        editingDrinkId = null;
        document.querySelector("#drink-form").reset();
    }
}

async function saveDrink(e) {

    e.preventDefault();
    const name = document.querySelector("#drink-name").value.trim();
    const price = document.querySelector("#drink-price").value;
    const categoryId = Number(document.querySelector("#drink-category").value);
    const image = document.querySelector("#drink-image").files[0];

    const ingredientIds =
        [...document.querySelectorAll(
            "#ingredients-dropdown input:checked"
        )].map(input => Number(input.value));

    const formData = new FormData();

    formData.append("name", name);
    formData.append("price", price);
    formData.append("category_id", categoryId);
    if (user.role === "admin" && providerId) {
        formData.append("provider_id", providerId);
    }

    if (image) {
        formData.append("image", image);
    }


    let response;

    if (!editingDrinkId) {
        response = await createDrink(formData);
        if (!response.success) {
            alert("Failed to create drink");
            return;
        }

    }
    else {
        response = await updateDrink(editingDrinkId, formData);
        if (!response.success) {
            alert("Failed to update drink");
            return;
        }
    }


    const drinkId = editingDrinkId == null ? response.data.drink_id : editingDrinkId;


    for (const ingredientId of ingredientIds) {
        await addIngredientToDrink(drinkId, ingredientId);
    }

    if (!editingDrinkId)
        alert("Drink created");
    else
        alert("Drink update");

    document.querySelector("#drink-modal").classList.add("hidden");
    location.reload();
}

async function initCategories() {

    const categories = await getCategories();
    const categorySelect = document.querySelector("#drink-category");
    categorySelect.innerHTML = "";
    categories.data.forEach(category => {

        categorySelect.innerHTML += `
            <option value="${category.id}">
                ${category.name}
            </option>
        `;
    });
}

async function initIngredients() {
    const response = await getIngredients();
    const container = document.querySelector("#ingredients-dropdown");
    container.innerHTML = "";

    response.data.forEach(
        ingredient => {

            container.innerHTML += `
                <label class="ingredient-option">

                    <input
                        type="checkbox"
                        value="${ingredient.id}"
                        data-name="${ingredient.name}">

                    <span>${ingredient.name}</span>

                </label>
            `;
        }
    );
}

function initIngredientsDropdown() {

    const toggle = document.querySelector("#ingredients-toggle");
    const dropdown = document.querySelector("#ingredients-dropdown");

    toggle.addEventListener(
        "click",
        () => {
            dropdown.classList.toggle("hidden");
        }
    );
}

function initIngredientsSelection() {

    const toggle = document.querySelector("#ingredients-toggle");
    const dropdown = document.querySelector("#ingredients-dropdown");
    dropdown.addEventListener(
        "change",
        () => {

            const selected =
                [...dropdown.querySelectorAll(
                    "input:checked"
                )];

            if (!selected.length) {
                toggle.textContent = "Select ingredients";
                return;
            }

            toggle.textContent = selected.map(i => i.dataset.name).join(", ");
        }
    );
}

function renderDrinks(drinks) {

    const container = document.querySelector("#provider-drinks");

    container.innerHTML = "";

    drinks.forEach(drink => {

        const card = document.createElement("div");
        card.classList.add("provider-drink-card");

        card.innerHTML = `
            <div>
                <h3>${drink.name}</h3>
                <div>
                    ${drink.category}
                </div>
                <div>
                    ${drink.price} RON
                </div>
            </div>

            <div class="actions">
                <button
                    class="btn btn-secondary view-btn">
                    View
                </button>
                <button
                    class="btn btn-primary edit-btn">
                    Edit
                </button>
                <button
                    class="btn ingredients-btn">
                    Ingredients
                </button>
                <button
                    class="btn btn-danger delete-btn">
                    Delete
                </button>
            </div>
        `;

        card.querySelector(".view-btn").addEventListener(
            "click",
            () => {

                location.replace(`/pages/drink_details.html?id=${drink.id}`);
            }
        );

        card.querySelector(".delete-btn").addEventListener(
            "click",
            async () => {

                if (!confirm(`Delete ${drink.name}?`)) return;
                const response = await deleteDrink(drink.id);

                if (response.success) {
                    card.remove();
                }
            }
        );

        card.querySelector(".edit-btn").addEventListener(
            "click",
            () => {
                openDrinkModal(drink);
            }
        );

        card.querySelector(".ingredients-btn").addEventListener(
            "click",
            () => {
                openIngredientsModal(drink);
            }
        );

        container.appendChild(card);
    });
}

async function openIngredientsModal(drink) {

    currentDrinkId = drink.id;

    document
        .querySelector("#ingredients-title")
        .textContent =
        `Ingredients - ${drink.name}`;

    const allIngredients = await getIngredients();
    const selectedIngredients = await getDrinkIngredients(drink.id);

    const selectedIds = new Set(
        selectedIngredients.data.map(i => i.id)
    );

    const container = document.querySelector("#ingredients-list");
    container.innerHTML = "";

    allIngredients.data.forEach(ingredient => {

        const row = document.createElement("label");
        row.classList.add("ingredient-option");

        row.innerHTML = `
            <input
                type="checkbox"
                value="${ingredient.id}"
                ${selectedIds.has(ingredient.id)
                ? "checked"
                : ""}
            >
            <span>${ingredient.name}</span>
        `;

        const checkbox = row.querySelector("input");

        checkbox.addEventListener(
            "change",
            async () => {

                if (checkbox.checked) {
                    await addIngredientToDrink(drink.id, ingredient.id);
                } 
                else {
                    await deleteIngredientFromDrink(drink.id,ingredient.id);
                }
            }
        );

        container.appendChild(row);
    });

    document.querySelector("#ingredients-modal").classList.remove("hidden");
}


await initProviderDashboard();