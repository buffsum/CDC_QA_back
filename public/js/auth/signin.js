const mailInput = document.getElementById("EmailInput");
const passwordInput = document.getElementById("PasswordInput");
const btnSignin = document.getElementById("btnSignin");
const formSignin = document.getElementById("formulaireSignin");

btnSignin.addEventListener("click", checkCredentials);

function checkCredentials(){
    let dataForm = new FormData(formSignin);

    //Ici, il faudra appeler l'API pour vérifier les credentials en BDD
    // function signupUser() {
        // let dataForm = new FormData(formSignup);
        
        // const ?
        let myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/json");
    
        let raw = JSON.stringify({
        "username": dataForm.get("Email"),
        "password": dataForm.get("Mdp")
        });
    
        let requestOptions = {
        method: "POST",
        headers: myHeaders,
        body: raw,
        redirect: "follow"
        };
    
        // fetch("http://127.0.0.1:8000/api/login", requestOptions)
        fetch(apiUrl+"login", requestOptions)
        .then((response) => 
            {
                if(response.ok){
                    return response.json();
                }
                else {
                    mailInput.classList.add("is-invalid");
                    passwordInput.classList.add("is-invalid");
                }
    
            })
        .then((result) => 
        {
            alert("Vous êtes connecté");
            //récupère le token du User
            const token = result.apiToken;
            //place ce token en cookie
            setToken(token);
            // récupère le role du User
            setCookie(roleCookieName, result.roles[0], 7); // Role admin
            //rediriger vers la page d'accueil
            window.location.replace("/");
        })
        .catch((error) => console.log('error', error));
        // .catch((error) => console.error(error));
    }
// }