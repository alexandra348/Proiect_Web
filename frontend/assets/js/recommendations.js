import { getRecommendations } from "./api.js";
import { initLayout } from "./components.js";

export function renderRecommendations(drinks) {

    const template = document.querySelector("#drink-card-template");
    const container = document.querySelector("#drinks-container");

    if (!template || !container) return;

    container.innerHTML = "";

    for (const drink of drinks) {

        const clone = template.content.cloneNode(true);
        const image = clone.querySelector(".drink-image");
        image.src = "http://localhost:8085/uploads/drinks/drink_6a24099b61fa48.39801071.jpg";
        image.alt = drink.name;
        clone.querySelector(".drink-name").textContent = drink.name;
        clone.querySelector(".drink-price").textContent = `${drink.price} RON`;
        clone.querySelector(".drink-category").textContent = drink.category;
        clone.querySelector(".drink-provider").textContent = drink.provider;

        container.appendChild(clone);
    }
}

export async function initRecommendations() {

    const response = await getRecommendations();

    if (!response.success) return;

    renderRecommendations(response.data);
}

await initLayout();
await initRecommendations();