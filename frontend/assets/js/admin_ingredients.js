import {
    getIngredients,
    createIngredient,
    updateIngredient,
    deleteIngredient
} from "./api.js";

let editingIngredientId = null;

export async function renderIngredients() {

    const response = await getIngredients();
    if (!response.success) return;

    const container = document.querySelector("#admin-content");

    container.innerHTML = `

        <div class="dashboard-toolbar">

            <button
                id="add-category-btn"
                class="btn btn-primary">

                + Add Ingredient

            </button>

        </div>

        <div id="ingredients-list"></div>

    `;

    const list = document.querySelector("#ingredients-list");

    response.data.forEach(ingredient => {

        const row = document.createElement("div");
        row.classList.add("dashboard-item");
        row.innerHTML = `

            <span>${ingredient.name}</span>

            <div>

                <button
                    class="btn btn-secondary edit-category"
                    data-id="${ingredient.id}"
                    data-name="${ingredient.name}">
                    Edit
                </button>

                <button
                    class="btn btn-danger delete-category"
                    data-id="${ingredient.id}">
                    Delete
                </button>

            </div>

        `;

        list.appendChild(row);
    });

    initIngredientEvents();
}

function initIngredientEvents() {

    document
        .querySelector("#add-category-btn")
        ?.addEventListener(
            "click",
            openCreateIngredientModal
        );

    document
        .querySelectorAll(".edit-category")
        .forEach(btn => {

            btn.addEventListener(
                "click",
                () => {

                    editingIngredientId = Number(btn.dataset.id);
                    document.querySelector("#category-name").value = btn.dataset.name;

                    document
                        .querySelector("#category-modal-title")
                        .textContent =
                            "Edit Ingredient";

                    document
                        .querySelector("#category-modal")
                        .classList.remove("hidden");
                }
            );
        });


    document
    .querySelector("#close-category-modal")
    ?.addEventListener(
        "click",
        () => {

            document
                .querySelector("#category-modal")
                .classList.add("hidden");
        }
    );

    document
        .querySelectorAll(".delete-category")
        .forEach(btn => {

            btn.addEventListener(
                "click",
                async () => {

                    if (!confirm("Delete ingredient?")) {
                        return;
                    }

                    const response = await deleteIngredient(Number(btn.dataset.id));

                    if (response.success) {

                        renderIngredients();
                    }
                }
            );
        });
}

function openCreateIngredientModal() {

    editingIngredientId = null;
    document.querySelector("#category-form").reset();
    const form = document.querySelector("#category-form");
    form.onsubmit = saveIngredient;
    document.querySelector("#category-modal-title").textContent = "Add Ingredient";
    document.querySelector("#category-modal").classList.remove("hidden");
}

async function saveIngredient(e) {

    e.preventDefault();
    const name = document.querySelector("#category-name").value.trim();
    let response;

    console.log(name);

    if (editingIngredientId) {
        response = await updateIngredient(editingIngredientId, name);
    } 
    else {
        response = await createIngredient(name);
    }

    if (!response.success) {
        alert("Operation failed");
        return;
    }

    document.querySelector("#category-modal").classList.add("hidden");
    renderIngredients();
}