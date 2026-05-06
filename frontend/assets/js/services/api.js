export async function getDrinks() {
  const response = await fetch("/api/drinks");

  if (!response.ok) {
    throw new Error("Eroare la API");
  }

  return response.json();
}