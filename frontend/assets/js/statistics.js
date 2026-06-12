import { getStatistics } from "./api.js";
let statisticsData = null;

async function loadStatistics() {

    const response = await getStatistics();

    if (!response.success) {
        console.error("Statistics error:", response.error);
        return;
    }

    const stats = response.data;
    statisticsData = stats;

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

    initExportButtons();

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

function exportSectionToCSV(sectionKey) {

    if (!statisticsData) {
        alert("Statistics are not loaded yet.");
        return;
    }

    const config = sectionsConfig[sectionKey];
    const items = statisticsData[sectionKey];

    if (!config || !items) return;

    const rows = [];

    rows.push(["Name", "Value"]);

    for (const item of items) {
        rows.push([
            item.name,
            item[config.valueKey]
        ]);
    }

    const csvContent = rows
        .map(row =>
            row
                .map(value =>
                    `"${String(value).replaceAll('"', '""')}"`
                )
                .join(",")
        )
        .join("\n");

    downloadFile(
        csvContent,
        `${config.filename}.csv`,
        "text/csv"
    );
}

function addRows(rows, sectionName, items, nameKey, valueKey) {

    if (!items) return;

    for (const item of items) {
        rows.push([
            sectionName,
            item[nameKey],
            item[valueKey]
        ]);
    }
}

function downloadFile(content, filename, type) {

    const blob = new Blob([content], { type });

    const url = URL.createObjectURL(blob);

    const link = document.createElement("a");

    link.href = url;
    link.download = filename;

    document.body.appendChild(link);
    link.click();

    document.body.removeChild(link);

    URL.revokeObjectURL(url);
}


function exportSectionToSVG(sectionKey) {

    if (!statisticsData) {
        alert("Statistics are not loaded yet.");
        return;
    }

    const config = sectionsConfig[sectionKey];
    const items = statisticsData[sectionKey];

    if (!config || !items || items.length === 0) return;

    const width = 760;
    const barHeight = 32;
    const gap = 18;
    const padding = 40;
    const labelWidth = 220;
    const chartWidth = 420;

    const height =
        padding * 2 + items.length * (barHeight + gap) + 20;

    const maxValue = Math.max(
        ...items.map(item => Number(item[config.valueKey]))
    );

    let svg = `
<svg xmlns="http://www.w3.org/2000/svg" width="${width}" height="${height}">
    <rect width="100%" height="100%" fill="#F6F9FC"/>

    <text x="${padding}" y="32" font-size="22" font-weight="700" fill="#1F2937">
        ${escapeSvg(config.title)}
    </text>
`;

    items.forEach((item, index) => {

        const value = Number(item[config.valueKey]);

        const y =
            padding + index * (barHeight + gap) + 30;

        const barWidth =
            maxValue === 0
                ? 0
                : (value / maxValue) * chartWidth;

        svg += `
    <text x="${padding}" y="${y + 22}" font-size="14" fill="#1F2937">
        ${escapeSvg(item.name)}
    </text>

    <rect 
        x="${padding + labelWidth}" 
        y="${y}" 
        width="${barWidth}" 
        height="${barHeight}" 
        rx="8" 
        fill="#3FBF7F"
    />

    <text 
        x="${padding + labelWidth + barWidth + 12}" 
        y="${y + 22}" 
        font-size="14" 
        font-weight="700" 
        fill="#1F2937"
    >
        ${value}
    </text>
`;
    });

    svg += `
</svg>
`;

    downloadFile(
        svg,
        `${config.filename}.svg`,
        "image/svg+xml"
    );
}

const sectionsConfig = {
    top_drinks: {
        title: "Top Drinks",
        valueKey: "popularity",
        filename: "top-drinks"
    },

    top_categories: {
        title: "Top Categories",
        valueKey: "total",
        filename: "top-categories"
    },

    top_providers: {
        title: "Top Providers",
        valueKey: "total",
        filename: "top-providers"
    },

    top_ingredients: {
        title: "Top Ingredients",
        valueKey: "total",
        filename: "top-ingredients"
    },

    avoided_ingredients: {
        title: "Avoided Ingredients",
        valueKey: "total",
        filename: "avoided-ingredients"
    },

    top_restrictions: {
        title: "Top Restrictions",
        valueKey: "total",
        filename: "top-restrictions"
    }
};

function initExportButtons() {

    document
        .querySelectorAll(".export-csv-btn")
        .forEach(button => {
            button.addEventListener("click", () => {
                exportSectionToCSV(button.dataset.section);
            });
        });

    document
        .querySelectorAll(".export-svg-btn")
        .forEach(button => {
            button.addEventListener("click", () => {
                exportSectionToSVG(button.dataset.section);
            });
        });

    document
        .querySelector("#rss-btn")
        ?.addEventListener("click", () => {
        window.open("/api/statistics/rss", "_blank");
    });
}

function escapeSvg(value) {
    return String(value)
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;");
}

