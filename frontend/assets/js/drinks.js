import { getDrinks } from "./api.js";


let drinksData = [];

export async function initDrinks() {

    const response = await getDrinks();

    if (!response.success) return;

    drinksData = response.data;

    initDOM();
    renderByCategory(); // default view
}


function initDOM() {

    const categoriesBtn = document.querySelector("#btn-categories");
    const providersBtn = document.querySelector("#btn-providers");

    if (categoriesBtn) {
        categoriesBtn.addEventListener("click", renderByCategory);
    }

    if (providersBtn) {
        providersBtn.addEventListener("click", renderByProvider);
    }
}


function groupBy(list, key) {

    const grouped = {};

    for (const item of list) {

        const value = item[key];

        if (!grouped[value]) {
            grouped[value] = [];
        }

        grouped[value].push(item);
    }

    return grouped;
}


function createCard(drink, template, mode) {

    const clone = template.content.cloneNode(true);

    const image = clone.querySelector(".drink-image");

    if(drink.image_url)
        image.src = drink.image_url;
    else
        image.src = "/uploads/drinks/Drink.jpg"
    image.alt = drink.name;

    clone.querySelector(".drink-name").textContent = drink.name;
    clone.querySelector(".drink-price").textContent = `${drink.price} RON`;

    const extraInfo = clone.querySelector(".drink-provider");

    if (mode === "category") {
        extraInfo.textContent = drink.provider || "Provider";
    }
    else {
        extraInfo.textContent = drink.category || "Category";
    }

    return clone;
}


function renderSections(groupedData,mode) {

    const template = document.querySelector("#drink-card-template");
    const container = document.querySelector("#drinks-container");

    if (!template || !container) return;

    container.innerHTML = "";

    for (const groupName in groupedData) {

        // SECTION
        const section = document.createElement("div");
        section.classList.add("category-section");

        // TITLE
        const title = document.createElement("h2");
        title.classList.add("category-title");
        title.textContent = groupName;

        // ROW
        const row = document.createElement("div");
        row.classList.add("category-row");

        // CARDS
        for (const drink of groupedData[groupName]) {
            row.appendChild(createCard(drink, template, mode));
        }

        section.appendChild(title);
        section.appendChild(row);

        container.appendChild(section);
    }
}


function renderByCategory() {

    const grouped = groupBy(drinksData, "category");
    renderSections(grouped, "category");
    setActiveButton("category");
}


function renderByProvider() {

    const grouped = groupBy(drinksData, "provider");
    renderSections(grouped, "provider");
    setActiveButton("provider");
}


function setActiveButton(mode) {

    const categoriesBtn = document.querySelector("#btn-categories");

    const providersBtn = document.querySelector("#btn-providers");

    if (mode === "category") {
        categoriesBtn.classList.add("active");
        providersBtn.classList.remove("active");
    }
    else {
        providersBtn.classList.add("active");
        categoriesBtn.classList.remove("active");
    }
}