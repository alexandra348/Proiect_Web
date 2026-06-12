import { getStatistics } from "./api.js";

async function loadStatistics() {

    const response = await getStatistics();

    if (!response.success) {
        console.error("Statistics error:", response.error);
        return;
    }

    const stats = response.data;

    renderSummaryCards(stats);

    renderTable(
        "#top-drinks-table",
        stats.top_drinks,
        "name",
        "popularity"
    );

    renderTable(
        "#top-ingredients-table",
        stats.top_ingredients,
        "name",
        "total"
    );

    renderTable(
        "#avoided-ingredients-table",
        stats.avoided_ingredients,
        "name",
        "total"
    );

    renderTable(
        "#top-restrictions-table",
        stats.top_restrictions,
        "name",
        "total"
    );

    renderBarChart(
        "#categories-chart",
        stats.top_categories,
        "total"
    );

    renderBarChart(
        "#providers-chart",
        stats.top_providers,
        "total"
    );

    renderBarChart(
    "#restrictions-chart",
    stats.top_restrictions,
    "total"
     );

    console.log("Statistics loaded successfully");
}

function renderSummaryCards(stats) {

    document.querySelector("#top-drinks-count").textContent =
        stats.top_drinks?.length || 0;

    document.querySelector("#top-category").textContent =
        stats.top_categories?.[0]?.name || "-";

    document.querySelector("#top-provider").textContent =
        stats.top_providers?.[0]?.name || "-";
}

function renderTable(selector, items, nameKey, valueKey) {

    const tbody = document.querySelector(selector);

    if (!tbody || !items) return;

    tbody.innerHTML = "";

    for (const item of items) {

        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${item[nameKey]}</td>
            <td>${item[valueKey]}</td>
        `;

        tbody.appendChild(row);
    }
}

function renderBarChart(selector, items, valueKey) {

    const container = document.querySelector(selector);

    if (!container || !items) return;

    container.innerHTML = "";

    const maxValue = Math.max(
        ...items.map(item => Number(item[valueKey]))
    );

    for (const item of items) {

        const percentage =
            maxValue === 0
                ? 0
                : (Number(item[valueKey]) / maxValue) * 100;

        const barWrapper = document.createElement("div");
        barWrapper.classList.add("bar-wrapper");

        barWrapper.innerHTML = `
            <div class="bar-label">
                <span>${item.name}</span>
                <span>${item[valueKey]}</span>
            </div>

            <div class="bar-track">
                <div class="bar-fill" style="width: ${percentage}%"></div>
            </div>
        `;

        container.appendChild(barWrapper);
    }
}

loadStatistics();