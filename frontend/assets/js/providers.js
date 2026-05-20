import { getProviders } from "./api.js";

document.addEventListener("DOMContentLoaded", async () => {

    const res = await getProviders();

    console.log("PROVIDERS RESPONSE:", res);

    if (!res || !res.success) {
        console.error("API error:", res);
        return;
    }

    const container = document.querySelector("#providers-container");

    if (!container) {
        console.error("Missing container");
        return;
    }

    const providers = res.data;

    if (!Array.isArray(providers)) {
        console.error("Data is not array:", providers);
        return;
    }

    providers.forEach(p => {
        const div = document.createElement("div");

        div.innerHTML = `
            <h3>${p.name}</h3>
            <p>${p.type}</p>
            <p>${p.city}</p>
        `;

        container.appendChild(div);
    });
});