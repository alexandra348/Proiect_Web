/*import { getDrinks, deleteDrink } from "./api.js";

async function loadDrinks() {

    const response = await getDrinks();

    if (!response.success) {
        console.error(response.error);
        return;
    }

    const drinks = response.data;

    const container = document.querySelector("#drinks-container");

    container.innerHTML = "";

    drinks.forEach(drink => {

        const card = document.createElement("div");

        card.classList.add("drink-card");

        card.innerHTML = `
            <h3>${drink.name}</h3>

            <p>${drink.price} RON</p>

            <p>Category: ${drink.category_id}</p>

            <button class="delete-btn" data-id="${drink.id}">
                Delete
            </button>

            <button 
                class="add-to-shopping"
                data-name="${drink.name}"
            >
                Add to shopping list
            </button>
        `;

        container.appendChild(card);
    });
}

document.addEventListener("click", async (e) => {

    if (e.target.matches(".delete-btn")) {

        const id = e.target.dataset.id;

        const response = await deleteDrink(id);

        if (response.success) {
            loadDrinks();
        } else {
            alert(response.error);
        }
    }
});

loadDrinks();*/