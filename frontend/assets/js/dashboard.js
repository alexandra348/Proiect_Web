import { 
    getStatistics,
    getUsers,
    getProviders,
    getDrinks
} from "./api.js";

async function loadDashboard() {

    const [stats, users, providers, drinks] = await Promise.all([
        getStatistics(),
        getUsers(),
        getProviders(),
        getDrinks()
    ]);

    if (!stats.success) {
        console.error("Stats error:", stats.error);
        return;
    }

    // 🔥 CALCULE REAL-TIME

    document.querySelector("#total-drinks").textContent =
        drinks.data?.length || 0;

    document.querySelector("#total-users").textContent =
        users.data?.length || 0;

    document.querySelector("#total-providers").textContent =
        providers.data?.length || 0;

    console.log("Dashboard loaded successfully");
}

loadDashboard();