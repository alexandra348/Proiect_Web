import { registerUser, createProvider } from "./api.js";

let accountType = "user";

const userTab = document.querySelector("#user-tab");
const providerTab = document.querySelector("#provider-tab");
const providerFields = document.querySelector("#provider-fields");

userTab.addEventListener("click", () => {

    accountType = "user";

    userTab.classList.add("active");
    providerTab.classList.remove("active");

    providerFields.classList.add("hidden");
});

providerTab.addEventListener("click", () => {

    accountType = "provider";

    providerTab.classList.add("active");
    userTab.classList.remove("active");

    providerFields.classList.remove("hidden");
});

document
    .querySelector("#register-form")
    .addEventListener("submit", async (e) => {

        e.preventDefault();

        try {

            const payload = {
                name: document.querySelector("#name").value,
                email: document.querySelector("#email").value,
                password: document.querySelector("#password").value
            };

            if (accountType === "provider") {
                payload.type = document.querySelector("#type").value;
                payload.address = document.querySelector("#address").value;
                payload.city = document.querySelector("#city").value;
            }

            const response =
                accountType === "user"
                    ? await registerUser(payload)
                    : await createProvider(payload);

            console.log(response);

            alert("Account created successfully!");

            window.location.replace("/pages/login.html");

        } catch (error) {

            console.error(error);

            alert("Registration failed.");
        }
    });