import { initLayout } from "./components.js";
import { getDrinks } from "./api.js";

await initLayout();

const params = new URLSearchParams(window.location.search);
const drinkId = params.get("id");

const drinkData = await getDrinks(drinkId);

const drink = drinkData.data;

document.querySelector("#drink-name").textContent = drink.name;
document.querySelector("#drink-category").textContent = drink.category;
document.querySelector("#drink-price").textContent = `${drink.price} RON`;
document.querySelector("#drink-image").src = drink.image_url ? drink.image_url : "/uploads/drinks/Drink.jpg";

document.querySelector("#provider-info").innerHTML = `
    <div class="provider-row">
        <strong>${drink.provider}</strong>
    </div>

    <div class="provider-row">
        ${drink.type}
    </div>

    <div class="provider-row">
        ${drink.city}
    </div>

    <div class="provider-row">
        ${drink.address}
    </div>

    <div class="provider-row">
        ${drink.email}
    </div>
`;

const ingredients = document.querySelector("#ingredients");
drink.ingredients.forEach(ingredient => {

    const tag = document.createElement("span");
    tag.classList.add("ingredient-tag");
    tag.textContent = ingredient.name;
    ingredients.appendChild(tag);
});


const reviews =
    document.querySelector("#reviews");

drink.reviews.forEach(review => {

    const card = document.createElement("div");
    card.classList.add("review-card");

    card.innerHTML = `
        <div class="review-header">
            <span class="review-user">
                ${review.user_name}
            </span>

            <span class="review-rating">
                ${"★".repeat(review.rating)}
            </span>
        </div>

        <div>
            ${review.notes || ""}
        </div>
    `;
    reviews.appendChild(card);
});

import {
    addToWishlist,
    removeFromWishlist,
    addTriedDrink,
    removeTriedDrink,
    getWishlist,
    getTriedDrinks
} from "./api.js";

import {
    openTriedModal,
    initTriedModal
} from "./drinks.js";

const token = localStorage.getItem("token");
const user = JSON.parse(localStorage.getItem("user"));

const wishlistBtn =
    document.querySelector("#wishlist-btn");

const triedBtn =
    document.querySelector("#tried-btn");

if (!token || user?.role !== "user") {

    wishlistBtn.style.display = "none";
    triedBtn.style.display = "none";

} else {

    const wishlistResponse =
        await getWishlist();

    const triedResponse =
        await getTriedDrinks();

    const wishlistIds = new Set(
        wishlistResponse.data.map(
            d => d.id
        )
    );

    const triedIds = new Set(
        triedResponse.data.map(
            d => d.id
        )
    );

    if (wishlistIds.has(drink.id)) {

        wishlistBtn.textContent =
            "♥ Wishlist";

        wishlistBtn.classList.add(
            "active"
        );
    }
    else {

        wishlistBtn.textContent =
            "♡ Wishlist";
    }

    if (triedIds.has(drink.id)) {

        triedBtn.classList.add(
            "active"
        );
    }

    wishlistBtn.addEventListener(
        "click",
        async () => {

            if (
                wishlistBtn.classList.contains(
                    "active"
                )
            ) {

                const response =
                    await removeFromWishlist(
                        drink.id
                    );

                if (response.success) {

                    wishlistIds.delete(
                        drink.id
                    );

                    wishlistBtn.textContent =
                        "♡ Wishlist";

                    wishlistBtn.classList.remove(
                        "active"
                    );
                }

                return;
            }

            const response =
                await addToWishlist(
                    drink.id
                );

            if (response.success) {

                wishlistIds.add(
                    drink.id
                );

                wishlistBtn.textContent =
                    "♥ Wishlist";

                wishlistBtn.classList.add(
                    "active"
                );
            }
        }
    );

    await initTriedModal();

    triedBtn.addEventListener(
        "click",
        async () => {

            if (
                triedBtn.classList.contains(
                    "active"
                )
            ) {

                const response =
                    await removeTriedDrink(
                        drink.id
                    );

                if (response.success) {

                    triedIds.delete(
                        drink.id
                    );

                    triedBtn.classList.remove(
                        "active"
                    );
                }

                return;
            }

            openTriedModal(
                drink.id,
                triedBtn
            );
        }
    );
}