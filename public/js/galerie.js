const galerieImg = document.getElementById("allImg");

// ici récupérer les informations des images depuis la base de données et les ajouter dans ce innerHTML
let titre = 'https://www.google.com';
let imgSrc = '../img/chef.jpg';

let myImg = getImg(titre, imgSrc);


galerieImg.innerHTML = myImg;

function getImg(titre, urlImg) {
    // sanitizeHtml permet de sécuriser le code HTML pour éviter les attaques XSS
    titre = sanitizeHtml(titre);
    urlImg = sanitizeHtml(urlImg);
    return `<div class="col pt-3"> 
            <div class="img-card">
                <img src="${urlImg}" alt="Pain" class="w-100">
                <p class="titre-img">${titre}</p>
                <div class="action-img-buttons" data-show="admin">
                    <button type="button" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#EditionPhotoModal"><i class="bi bi-pencil-square"></i></button>
                    <button type="button" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#DeletePhotoModal"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        </div>`;
}

