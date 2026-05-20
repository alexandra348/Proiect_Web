const STORAGE_KEY = "shopping-list";

const listContainer = document.querySelector("#shopping-list");

function getShoppingList() {
    return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
}

function saveShoppingList(items) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
}

function renderShoppingList() {

    if (!listContainer) return;

    const items = getShoppingList();

    listContainer.innerHTML = "";

    items.forEach((item, index) => {

        const li = document.createElement("li");

        li.innerHTML = `
            ${item}
            <button data-index="${index}">
                Remove
            </button>
        `;

        listContainer.appendChild(li);
    });
}

document.addEventListener("click", (e) => {

    if (e.target.matches(".add-to-shopping")) {

        const name = e.target.dataset.name;

        const items = getShoppingList();

        items.push(name);

        saveShoppingList(items);

        renderShoppingList();
    }

    if (e.target.matches("#shopping-list button")) {

        const index = e.target.dataset.index;

        const items = getShoppingList();

        items.splice(index, 1);

        saveShoppingList(items);

        renderShoppingList();
    }
});

renderShoppingList();