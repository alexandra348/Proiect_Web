import { getUsers, deleteUser } from "./api.js";

document.addEventListener("DOMContentLoaded", async () => {

    const res = await getUsers();

    if (!res.success) {
        console.error(res.error);
        return;
    }

    const container = document.querySelector("#users-container");

    res.data.forEach(user => {

        const div = document.createElement("div");

        div.innerHTML = `
            <h3>${user.name}</h3>
            <p>${user.email}</p>
            <button data-id="${user.id}" class="delete-user">
                Delete
            </button>
        `;

        container.appendChild(div);
    });

    document.addEventListener("click", async (e) => {
        if (e.target.matches(".delete-user")) {
            await deleteUser(e.target.dataset.id);
            location.reload();
        }
    });
});