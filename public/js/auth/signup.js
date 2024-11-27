// alert("coucou");
// Implémenter le js de ma page

const inputNom = document.getElementById("NomInput");
const inputPrenom = document.getElementById("PrenomInput");
const inputMail = document.getElementById("EmailInput");
const inputPassword = document.getElementById("PasswordInput");
const inputValidatePassword = document.getElementById("ValidatePasswordInput");
const btnValidation = document.getElementById("btn-validation-inscription");
const formSignup = document.getElementById("formulaireSignup");

//keyup = activé quand on relache après avoir appuyé sur une touche
inputNom.addEventListener("keyup", validateForm);
inputPrenom.addEventListener("keyup", validateForm);
inputMail.addEventListener("keyup", validateForm);
inputPassword.addEventListener("keyup", validateForm);
inputValidatePassword.addEventListener("keyup", validateForm);

btnValidation.addEventListener("click", signupUser);

function validateForm() {
    const nomOk = validateRequired(inputNom);
    const prenomOk = validateRequired(inputPrenom);
    const mailOK = validateEmail(inputMail);
    const passwordOK = validatePassword(inputPassword);
    const passwordConfirmOK = validateConfirmationPassword(inputPassword, inputValidatePassword);

    if (nomOk && prenomOk && mailOK && passwordOK && passwordConfirmOK) {
        btnValidation.disabled = false;
    }
    else {
        btnValidation.disabled = true;
    }
}

// je crée une fonction pour valider les champs requis
function validateRequired(input) {
    if (input.value != '') {
        //c'est ok
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        return true;
    }
    else {
        //c'est pas ok
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
        return false;
    }
}

// je rajoute un event listener pour connecter à mon API et géré l'inscription
function signupUser() {
    let dataForm = new FormData(formSignup);
    
    // const ?
    let myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/json");

    let raw = JSON.stringify({
    "firstName": dataForm.get("Nom"),
    "lastName": dataForm.get("Prenom"),
    "email": dataForm.get("Email"),
    "password": dataForm.get("Mdp")
    });

    let requestOptions = {
    method: "POST",
    headers: myHeaders,
    body: raw,
    redirect: "follow"
    };

    fetch(apiUrl+"registration", requestOptions)
    // .then((response) => response.text())
    .then((response) => 
        {
            // debugger;
            if(response.ok){
                return response.json();
            }
            else {
                alert("Erreur lors de l'inscription");
            }

        })
    .then((result) => 
    {
        //redirection vers la page de connexion après inscription
        // alert("Bravo "+dataForm.get("Prenom")+", Inscription réussie");
        // alert(`Bravo ${dataForm.get("Prenom")}, Inscription réussie`);
        alert("Bravo " + String(dataForm.get("Prenom")) + ", Inscription réussie");
        document.location.href = "/signin";
        // console.log(result)
    })
    .catch((error) => console.log('error', error));
    // .catch((error) => console.error(error));
}

function validateEmail(input) {
    //définir mon regex (expression régulière)
    // const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const mailUser = inputMail.value;

    if (mailUser.match(emailRegex)) {
        //c'est ok
        inputMail.classList.add("is-valid");
        inputMail.classList.remove("is-invalid");
        return true;
    }
    else {
        //c'est pas ok
        inputMail.classList.add("is-invalid");
        inputMail.classList.remove("is-valid");
        return false;
    }
}

function validatePassword(input){
    //Définir mon regex
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/;
    const passwordUser = input.value;
    if(passwordUser.match(passwordRegex)){
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        return true;
    }
    else{
        input.classList.remove("is-valid");
        input.classList.add("is-invalid");
        return false;
    }
}

function validateConfirmationPassword(inputPwd, inputConfirmPwd){
    if(inputPwd.value == inputConfirmPwd.value){
        inputConfirmPwd.classList.add("is-valid");
        inputConfirmPwd.classList.remove("is-invalid");
        return true;
    }
    else{
        inputConfirmPwd.classList.add("is-invalid");
        inputConfirmPwd.classList.remove("is-valid");
        return false;
    }
}