import { getDrinks } from "../services/api.js";
import { renderDrinks } from "../ui/renderDrinks.js";

async function init() {
  try {
    const drinks = await getDrinks();
    renderDrinks(drinks);
  } catch (error) {
    console.error(error);
    document.getElementById("drinks").innerHTML = "Eroare la încărcare";
  }
}

init();