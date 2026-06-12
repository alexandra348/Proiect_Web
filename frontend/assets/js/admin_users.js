import {
    createUser,
    updateUser,
    getUsers,
    deleteUser
} from "./api.js";

let editingUserId = null;

export async function renderUsers() {

    const response = await getUsers();

    if (!response.success) return;

    const container = document.querySelector("#admin-content");

    container.innerHTML = `

        <div class="dashboard-toolbar">

            <button
                id="add-user-btn"
                class="btn btn-primary">

                + Add User

            </button>

        </div>

        <div id="users-list"></div>

    `;

    const list = document.querySelector("#users-list");

    response.data.forEach(user => {

        const row = document.createElement("div");
        row.classList.add("dashboard-item");

        row.innerHTML = `
            <span>${user.name}</span>
            <span>${user.email}</span>
            <span>${user.role}</span>
            <div>

                <button
                    class="btn btn-secondary edit-user"
                    data-id="${user.id}">
                    Edit
                </button>

                <button
                    class="btn btn-danger delete-user"
                    data-id="${user.id}">
                    Delete
                </button>

            </div>

        `;

        list.appendChild(row);
    });

    initUserEvents(response.data);
}

function initUserEvents(users) {

    document
        .querySelector("#add-user-btn")
        ?.addEventListener(
            "click",
            openCreateUserModal
        );

    document
        .querySelectorAll(".edit-user")
        .forEach(btn => {

            btn.addEventListener(
                "click",
                () => {

                    const user = users.find(u => u.id == btn.dataset.id);
                    openEditUserModal(user);
                }
            );
        });

    const form = document.querySelector("#user-form");

    if (form) {
        form.onsubmit = saveUser;
    }

    document
    .querySelector("#close-user-modal")
    ?.addEventListener(
        "click",
        () => {

            document
                .querySelector("#user-modal")
                .classList.add("hidden");
        }
    );

    document
        .querySelectorAll(".delete-user")
        .forEach(btn => {

            btn.addEventListener(
                "click",
                async () => {

                    await deleteUserHandler(Number(btn.dataset.id));
                }
            );
        });
}

function openCreateUserModal() {

    editingUserId = null;
    document.querySelector("#user-form").reset();
    document.querySelector("#user-modal-title").textContent ="Add User";
    document.querySelector("#user-modal").classList.remove("hidden");
}

function openEditUserModal(user) {

    editingUserId = user.id;
    document.querySelector("#user-name").value = user.name;
    document.querySelector("#user-email").value = user.email;
    document.querySelector("#user-role").value = user.role;
    document.querySelector("#user-password").value = "";
    document.querySelector("#user-modal-title").textContent ="Edit User";
    document.querySelector("#user-modal").classList.remove("hidden");
}

async function saveUser(e) {

    e.preventDefault();
    const data = {
        name: document.querySelector("#user-name").value,
        email: document.querySelector("#user-email").value,
    };

    const password = document.querySelector("#user-password").value;

    if (password) {
        data.password = password;
    }

    const role = document.querySelector("#user-role").value;

    let response;

    if (editingUserId) {

        response = await updateUser(editingUserId,data);

    } else {

        response = await createUser(data,role);
    }


    if (!response.success) {

        alert("Operation failed");
        return;
    }

    document.querySelector("#user-modal").classList.add("hidden");

    renderUsers();
}

async function deleteUserHandler(id) {

    if (!confirm("Delete user?")) {
        return;
    }

    const response = await deleteUser(id);

    if (response.data.success) {
        renderUsers();
    }
    else {
        alert(response.data);
    }
}