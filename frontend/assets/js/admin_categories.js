import {
    getCategories,
    createCategory,
    updateCategory,
    deleteCategory
} from "./api.js";

let editingCategoryId = null;

export async function renderCategories() {

    const response = await getCategories();
    if (!response.success) return;

    const container = document.querySelector("#admin-content");

    container.innerHTML = `

        <div class="dashboard-toolbar">

            <button
                id="add-category-btn"
                class="btn btn-primary">

                + Add Category

            </button>

        </div>

        <div id="categories-list"></div>

    `;

    const list = document.querySelector("#categories-list");

    response.data.forEach(category => {

        const row =
            document.createElement("div");

        row.classList.add("dashboard-item");

        row.innerHTML = `

            <span>${category.name}</span>

            <div>

                <button
                    class="btn btn-secondary edit-category"
                    data-id="${category.id}"
                    data-name="${category.name}">
                    Edit
                </button>

                <button
                    class="btn btn-danger delete-category"
                    data-id="${category.id}">
                    Delete
                </button>

            </div>

        `;

        list.appendChild(row);
    });

    initCategoryEvents();
}

function initCategoryEvents() {

    document
        .querySelector("#add-category-btn")
        ?.addEventListener(
            "click",
            openCreateCategoryModal
        );

    document
        .querySelectorAll(".edit-category")
        .forEach(btn => {

            btn.addEventListener(
                "click",
                () => {

                    editingCategoryId =
                        Number(btn.dataset.id);

                    document
                        .querySelector("#category-name")
                        .value =
                            btn.dataset.name;

                    document
                        .querySelector("#category-modal-title")
                        .textContent =
                            "Edit Category";

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

                    if (!confirm("Delete category?")) {
                        return;
                    }

                    const response = await deleteCategory(Number(btn.dataset.id));

                    if (response.success) {

                        renderCategories();
                    }
                }
            );
        });
}

function openCreateCategoryModal() {

    editingCategoryId = null;
    document.querySelector("#category-form").reset();
    const form = document.querySelector("#category-form");
    form.onsubmit = saveCategory;
    document.querySelector("#category-modal-title").textContent = "Add Category";
    document.querySelector("#category-modal").classList.remove("hidden");
}

async function saveCategory(e) {

    e.preventDefault();
    const name = document.querySelector("#category-name").value.trim();
    let response;

    console.log(name);

    if (editingCategoryId) {
        response = await updateCategory(editingCategoryId, name);
    } 
    else {
        response = await createCategory(name);
    }

    if (!response.success) {
        alert("Operation failed");
        return;
    }

    document.querySelector("#category-modal").classList.add("hidden");
    renderCategories();
}