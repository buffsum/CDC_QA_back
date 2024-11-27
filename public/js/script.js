
const tokenCookieName = "accesstoken";
const roleCookieName = "role";
const signoutBtn = document.getElementById("signout-btn");
const apiUrl = "http://localhost:8000/api/";
getInfosUser();

//**** Gestion des roles + hide****/
function getRole() {
    return getCookie(roleCookieName);
}

function showAndHideElementsForRole() {
    const userConnected = isConnected();
    const role = getRole();

    let allElementsToEdit = document.querySelectorAll("[data-show]");

    allElementsToEdit.forEach(element => 
        {
        switch (element.dataset.show) {
            case "disconnected":
                if (userConnected) {
                    element.classList.add("d-none"); // d-none = classe de bootstrap pour cacher un élément
                }
                break;
            case "connected":
                if (!userConnected) {
                    element.classList.add("d-none");
                }
                break;
            case "admin":
                if (!userConnected || role != "admin") {
                    element.classList.add("d-none");
                }
                break;
            case "client":
                if (!userConnected || role != "client") {
                    element.classList.add("d-none");
                }
                break;
        }
    })
} 

/* roles
disconnected
connected (admin ou client)
    - admin
    - client
*/

// ****** FIN de gestion des roles ******

// ****** Gestion de la déconnexion ******
signoutBtn.addEventListener("click", signout);

function signout() {
    eraseCookie(tokenCookieName);
    eraseCookie(roleCookieName);
    // window.location.replace("/signin");
    window.location.reload();
}
// ****** FIN de gestion de la déconnexion ******

// ****** Gestion des cookies ******
function setToken(token) {
    // le nom de mon cookie sera "accesstoken", la valeur sera le token, et il expirera dans 7 jours
    setCookie(tokenCookieName, token, 7);
}

function getToken() {
    return getCookie(tokenCookieName);
}

function setCookie(name,value,days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function getCookie(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for(const element of ca) {
        let c = element;
        while (c.startsWith(' ')) c = c.substring(1,c.length);
        if (c.startsWith(nameEQ)) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function isConnected() {
    return !(getToken() == null || getToken == undefined);
}
// ancien code :
// {
//     if (getToken() == null || getToken == undefined) {
//         return false;
//     }
//     else {
//         return true;
//     }
// }
// ****** FIN de gestion des cookies ******

// ****** Fonction pour sécuriser le code HTML ******
function sanitizeHtml (text) {
    const tempHtml = document.createElement('div');
    tempHtml.textContent = text;
    return tempHtml.innerHTML;
}
// ****** FIN de sécurisation du code HTML ******


function getInfosUser(){
    console.log("getInfosUser");
    let myHeaders = new Headers();
    myHeaders.append("X-AUTH-TOKEN", getToken());

    let requestOptions = {
        method: 'GET',
        headers: myHeaders,
        redirect: 'follow'
    };

    fetch(apiUrl+"account/me", requestOptions)
    .then(response =>{
        if(response.ok){
            return response.json();
        }
        else{
            console.log("Impossible de récupérer les informations utilisateur");
        }
    })
    .then(result => {
        return result;
    })
    .catch(error =>{
        console.error("erreur lors de la récupération des données utilisateur", error);
    });
}

// ****** TEST de la connexion ******
// if (isConnected()) {
//     alert("Vous êtes connecté");
// }
// else {
//     alert("Vous n'êtes pas connecté");
// }
// ****** FIN de gestion des cookies ******