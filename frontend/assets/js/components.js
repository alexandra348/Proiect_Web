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
    await loadComponent("navbar","/components/navbar.html");
    initLoginButton();
    await loadComponent("footer","/components/footer.html");
    await loadComponent("drink-template","/components/drink-card.html");

}