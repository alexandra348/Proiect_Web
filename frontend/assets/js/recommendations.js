import { getRecommendations } from "./api.js";
import { initLayout } from "./components.js";
import { createCard } from "./drinks.js";

export function renderRecommendations(drinks) {

    const template = document.querySelector("#drink-card-template");
    const container = document.querySelector("#drinks-container");

    if (!template || !container) return;

    container.innerHTML = "";

    drinks.forEach(drink => {

        container.appendChild(createCard(drink, template, "category"));
    });
}

export async function initRecommendations() {

    const response = await getRecommendations();

    if (!response.success) return;

    renderRecommendations(response.data);
}

await initLayout();
await initRecommendations();