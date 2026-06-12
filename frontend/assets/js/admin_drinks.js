import { getProviders } from "./api.js";

export async function renderDrinks() {

    const response = await getProviders();

    if (!response.success) return;

    const container = document.querySelector("#admin-content");
    container.innerHTML = `<div class="providers-grid"></div>`;
    const grid = document.querySelector(".providers-grid");

    response.data.forEach(provider => {

        const card = document.createElement("div");
        card.classList.add("provider-card");
        card.innerHTML = `
            <h3>${provider.name}</h3>
            <p>${provider.email}</p>
            <p>${provider.type}</p>
            <p>${provider.city}</p>
            <p>${provider.address}</p>

            <button
                class="btn btn-primary view-drinks"
                data-id="${provider.id}">
                View Drinks
            </button>
        `;

        grid.appendChild(card);
    });

    initDrinkEvents();
}

function initDrinkEvents() {

    document
        .querySelectorAll(".view-drinks")
        .forEach(btn => {

            btn.addEventListener(
                "click",
                () => {

                    window.location.replace(`/pages/provider_dashboard.html?id=${btn.dataset.id}`);
                }
            );
        });
}