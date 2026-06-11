import { initLayout } from "./components.js";

import { renderUsers } from "./admin_users.js";
import { renderProviders } from "./admin_providers.js";
//import { renderDrinks } from "./admin_drinks.js";
import { renderCategories } from "./admin_categories.js";
import { renderIngredients } from "./admin_ingredients.js";

await initLayout();

let activeTab = "users";

function initTabs() {

    document
        .querySelectorAll(".tab-btn")
        .forEach(btn => {

            btn.addEventListener(
                "click",
                () => {

                    document
                        .querySelectorAll(".tab-btn")
                        .forEach(b =>
                            b.classList.remove("active")
                        );

                    btn.classList.add("active");

                    activeTab = btn.dataset.tab;
                    loadTab(activeTab);
                }
            );
        });
}

async function loadTab(tab) {

    switch(tab){

        case "users":
            await renderUsers();
            break;

        case "providers":
            await renderProviders();
            break;

        /*case "drinks":
            await renderDrinks();
            break;*/

        case "categories":
            await renderCategories();
            break;

        case "ingredients":
            await renderIngredients();
            break;
    }
}

initTabs();
loadTab(activeTab);
