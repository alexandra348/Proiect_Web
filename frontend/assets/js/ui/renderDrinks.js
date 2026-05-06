export function renderDrinks(drinks) {
  const container = document.getElementById("drinks");

  if (!drinks.length) {
    container.innerHTML = "<p>Nu există băuturi.</p>";
    return;
  }

  container.innerHTML = drinks.map(d => `
    <div style="border:1px solid #ccc; padding:10px; margin:10px;">
      <h3>${d.name}</h3>
      <p>Preț: ${d.price} lei</p>
    </div>
  `).join("");
}