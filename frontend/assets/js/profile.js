import {
    updateUser,
    updateProvider,
    deleteUser,
    deleteProvider
} from "./api.js";

const user = JSON.parse(
    localStorage.getItem("user")
);

if (user.role === "provider") {

    document.querySelector("#provider-info").classList.remove("hidden");
    document.querySelector("#provider-fields").classList.remove("hidden");
    document.querySelector("#view-type").textContent = user.type;
    document.querySelector("#view-address").textContent = user.address;
    document.querySelector("#view-city").textContent = user.city;
    document.querySelector("#type").value = user.type || "";
    document.querySelector("#address").value = user.address || "";
    document.querySelector("#city").value = user.city || "";
}

const deleteBtn = document.querySelector("#delete-btn");

if (user.role === "user" || user.role === "provider") {
    deleteBtn.classList.remove("hidden");
}

if (!user) {
    window.location.replace("/pages/login.html");
}

const form = document.querySelector("#profile-form");

document.querySelector("#view-name").textContent = user.name;
document.querySelector("#view-email").textContent = user.email;
document.querySelector("#view-role").textContent = user.role;
document.querySelector("#view-created").textContent = user.created_at;

const nameInput = document.querySelector("#name");
const emailInput = document.querySelector("#email");
const passwordInput = document.querySelector("#password");
const currentPasswordInput = document.querySelector("#currentPassword");

nameInput.value = user.name || "";
emailInput.value = user.email || "";


const profileView = document.querySelector("#profile-view");
const editBtn = document.querySelector("#edit-btn");
const cancelBtn = document.querySelector("#cancel-btn");

deleteBtn.addEventListener(
    "click",
    async () => {

        const confirmed = confirm("Are you sure you want to delete your account? This action cannot be undone.");

        if (!confirmed) {
            return;
        }

        try {

            let response;

            if (user.role === "user") {

                response = await deleteUser();

            } else {

                response = await deleteProvider();
            }

            if (!response.success) {
                throw new Error(response.error);
            }

            alert("Account deleted successfully.");

            localStorage.removeItem("token");
            localStorage.removeItem("user");

            window.location.replace("/pages/login.html");

        } catch (error) {

            console.error(error);
            alert(error.message || "Failed to delete account.");
        }
    }
);

editBtn.addEventListener("click", () => {

    profileView.classList.add("hidden");
    form.classList.remove("hidden");
});

cancelBtn.addEventListener("click", () => {

    form.classList.add("hidden");
    profileView.classList.remove("hidden");
});

form.addEventListener(
    "submit",
    async (e) => {

        e.preventDefault();

        try {

            const payload = {
                id: user.id,
                name: nameInput.value.trim(),
                email: emailInput.value.trim()
            };

            if (user.role === "provider") {

                payload.type = document.querySelector("#type").value.trim();
                payload.address = document.querySelector("#address").value.trim();
                payload.city = document.querySelector("#city").value.trim();
            }

            if (passwordInput.value.trim() || currentPasswordInput.value.trim()) {
                payload.password = passwordInput.value.trim();
                payload.currentPassword = currentPasswordInput.value.trim();
            }

            let response;

            if (user.role === "user" || user.role === "admin") {
                response = await updateUser(payload);

            } else if (user.role === "provider") {
                response = await updateProvider(payload);

            } else {
                throw new Error("Unknown role");
            }

            console.log(response);

            const updatedUser = {
                ...user,
                ...payload
            };

            delete updatedUser.password;

            localStorage.setItem(
                "user",
                JSON.stringify(updatedUser)
            );

            if (!response.success) {
                throw new Error(response.error);
            }

            if (response.data.passwordChanged) {

                alert("Password changed successfully. Please log in again.");

                localStorage.removeItem("token");
                localStorage.removeItem("user");

                window.location.replace("/pages/login.html");
                return;
            }

            alert("Profile updated successfully!");

        } catch (error) {

            console.error(error);
            alert("Failed to update profile.");
        }
    }
);