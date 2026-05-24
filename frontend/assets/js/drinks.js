import { getDrinks } from "./api.js";

export async function initDrinks() {

    const response = await getDrinks();

    if (!response.success) return;

    const drinks = response.data;

    const template = document.querySelector("#drink-card-template");
    const container = document.querySelector("#drinks-container");

    if (!template || !container) return;

    container.innerHTML = "";

    for (let drink of drinks) {

        const clone = template.content.cloneNode(true);

        clone.querySelector(".drink-name").textContent = drink.name;
        clone.querySelector(".drink-category").textContent = drink.category_name || "Drink";
        clone.querySelector(".drink-price").textContent = `${drink.price} RON`;

        container.appendChild(clone);
    }
}