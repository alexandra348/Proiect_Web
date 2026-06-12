import { initLoginButton } from "./navbar.js";

export async function loadComponent(id, path) {

    const container = document.getElementById(id);

    if(!container) return;

    try{
        const response = await fetch(path);
        container.innerHTML = await response.text();

    }catch(err){

        console.error(err);

    }

}

export function initDashboard()
{

    const user = JSON.parse(localStorage.getItem("user"));

    if(user) {
        if( user.role === "admin" ) {

            document.querySelector("#dashboard")
                    .innerHTML = `<a href="admin_dashboard.html" class="nav-item"> Dashboard </a>`;
                                        
        }
        else if( user.role === "provider" ) {

            document.querySelector("#dashboard")
                    .innerHTML = `<a href="provider_dashboard.html" class="nav-item"> Dashboard </a>`;
                                        
        }
        else {

            document.querySelector("#dashboard")
                    .innerHTML = `<a href="user_dashboard.html" class="nav-item"> Dashboard </a>`;
        }
    }
    else {
        document.querySelector("#dashboard")
                    .innerHTML = `<a href="user_dashboard.html" class="nav-item"> Dashboard </a>`;
    }
                                 
}

export async function initLayout(){

    await loadComponent("sidebar","/components/sidebar.html");
    initDashboard();
    initLoginButton();
    await loadComponent("footer","/components/footer.html");
    await loadComponent("drink-template","/components/drink-card.html");
    
    document
    .querySelector("#drink-search")
    ?.addEventListener(
        "keydown",
        async e => {

            if (e.key !== "Enter") {
                return;
            }

            const term = e.target.value.trim();
            if (!term) {
                return;
            }

            window.location.replace(`/pages/search.html?term=${encodeURIComponent(term)}`);
        }
    );

}
