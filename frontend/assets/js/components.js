export async function loadComponent(
id,
path
){

const container=
document.getElementById(id);

if(!container) return;

try{

const response=
await fetch(path);

container.innerHTML=
await response.text();

}catch(err){

console.error(err);

}

}

export async function initLayout(){

await loadComponent(
"navbar",
"/components/navbar.html"
);

await loadComponent(
"footer",
"/components/footer.html"
);

await loadComponent(
"drink-template",
"/components/drink-card.html"
);

}