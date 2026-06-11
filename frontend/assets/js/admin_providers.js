import {
    createProvider,
    updateProvider,
    getProviders,
    deleteProvider
} from "./api.js";

let editingProviderId = null;

export async function renderProviders() {

    const response = await getProviders();

    if (!response.success) return;

    const container =
        document.querySelector("#admin-content");

    container.innerHTML = `
        <div class="dashboard-toolbar">

            <button
                id="add-provider-btn"
                class="btn btn-primary">

                + Add Provider

            </button>

        </div>

        <div id="providers-list"></div>
    `;

    const list =
        document.querySelector("#providers-list");

    response.data.forEach(provider => {

        const row = document.createElement("div");

        row.classList.add("dashboard-item");

        row.innerHTML = `
            <span>${provider.name}</span>
            <span>${provider.email}</span>
            <span>${provider.type}</span>
            <span>${provider.address}</span>
            <span>${provider.city}</span>
            

            <div>

                <button
                    class="btn btn-secondary edit-provider"
                    data-id="${provider.id}">
                    Edit
                </button>

                <button
                    class="btn btn-danger delete-provider"
                    data-id="${provider.id}">
                    Delete
                </button>

            </div>
        `;

        list.appendChild(row);
    });

    initProviderEvents(response.data);
}

async function saveProvider(e) {

    e.preventDefault();
    const data = {
        name: document.querySelector("#provider-name").value,
        email: document.querySelector("#provider-email").value,
        password: document.querySelector("#provider-password").value,
        address: document.querySelector("#provider-address").value,
        city: document.querySelector("#provider-city").value,
        type: document.querySelector("#provider-type").value
    };

    let response;

    if (editingProviderId) {

        response = await updateProvider(editingProviderId, data);

    } else {

        response = await createProvider(data);
    }

    if (!response.success) {

        alert("Operation failed");
        return;
    }

    document.querySelector("#provider-modal").classList.add("hidden");

    await renderProviders();
}

function initProviderEvents(providers) {

    const addBtn = document.querySelector("#add-provider-btn");
    const form = document.querySelector("#provider-form");
    const modal = document.querySelector("#provider-modal");
    const closeBtn = document.querySelector("#close-provider-modal");

    addBtn?.addEventListener(
        "click",
        () => {

            editingProviderId = null;
            form.reset();
            document.querySelector("#provider-modal-title")
                .textContent = "Add Provider";

            modal.classList.remove("hidden");
        }
    );


    form?.addEventListener("submit", saveProvider);

    closeBtn?.addEventListener(
        "click",
        () => {
            modal.classList.add("hidden");
        }
    );

    
    document.querySelectorAll(".edit-provider")
        .forEach(btn => {

            btn.addEventListener(
                "click",
                () => {

                    const provider =
                        providers.find(
                            p => p.id == btn.dataset.id
                        );

                    editingProviderId = provider.id;

                    document.querySelector("#provider-name").value = provider.name;
                    document.querySelector("#provider-email").value = provider.email;
                    document.querySelector("#provider-address").value = provider.address;
                    document.querySelector("#provider-city").value = provider.city;
                    document.querySelector("#provider-type").value = provider.type;

                    document.querySelector("#provider-modal-title")
                        .textContent = "Edit Provider";

                    modal.classList.remove("hidden");
                }
            );
        });

    
    document.querySelectorAll(".delete-provider")
        .forEach(btn => {

            btn.addEventListener(
                "click",
                async () => {

                    if (!confirm("Delete provider?")) return;

                    const response = await deleteProvider(Number(btn.dataset.id));

                    if (response.success) {
                        renderProviders();
                    }
                }
            );
        });
}