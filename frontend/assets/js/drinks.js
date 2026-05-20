import { getDrinks, deleteDrink } from "./api.js";

document.addEventListener("DOMContentLoaded", async () => {

    const res = await getDrinks();

    if (res.status !== 200) {
        console.error(res.error);
        return;
    }

    const drinks = res.data;

    const container = document.querySelector("#drinks-container");
    const template = document.querySelector("#drink-card-template");

    drinks.forEach(drink => {
        const clone = template.content.cloneNode(true);

        clone.querySelector(".drink-name").textContent = drink.name;
        clone.querySelector(".drink-price").textContent = `${drink.price} RON`;
        clone.querySelector(".drink-category").textContent = drink.category_id;

        container.appendChild(clone);
    });

    // delete
    document.addEventListener("click", async (e) => {
        if (e.target.classList.contains("btn-delete")) {
            const id = e.target.closest(".drink-card").dataset.id;

            const res = await deleteDrink(id);

            if (res.status === 200) {
                location.reload();
            }
        }
    });

});