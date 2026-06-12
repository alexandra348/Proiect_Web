import { searchDrinks } from "./api.js";
import { initLayout } from "./components.js";

await initLayout();

const params = new URLSearchParams(window.location.search);
const query = params.get("term");

const response = await searchDrinks(query);

const container = document.querySelector("#search-results");

if (!response.success || !response.data.length) {

    container.innerHTML = `
        <div class="empty-state">
            No drinks found
        </div>
    `;

} else {

    container.innerHTML = "";

    const title = document.querySelector("#search-title");

    title.textContent = query
        ? `Search Results for: "${query}"`
        : "Search Results";

    response.data.forEach(drink => {

        const card = document.createElement("div");

        card.classList.add("drink-card");

        card.innerHTML = `
            <img
                class="drink-image"
                src="${drink.image_url || "/uploads/drinks/Drink.jpg"}"
                alt="${drink.name}">

            <h3 class="drink-name">
                ${drink.name}
            </h3>

            <p class="drink-price">
                ${drink.price} RON
            </p>

            <p class="drink-category">
                ${drink.category || "Category"}
            </p>

            <p class="drink-provider">
                ${drink.provider || "Provider"}
            </p>
        `;

        card.addEventListener(
            "click",
            () => {

                location.replace(`/pages/drink_details.html?id=${drink.id}`);
            }
        );

        container.appendChild(card);
    });
}