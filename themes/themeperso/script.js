//GALERIE ACCUEIL DES PROJETS _ CPT UI 
let currentPage = 1;
const postsPerPage = 8;
let allPosts = []; // Pour stocker tous les posts récupérés via AJAX
const gallery = document.querySelector('#gallery');
const loadMoreButton = document.querySelector('.load_more_button');

// Fonction pour gérer le chargement (initial ou suivant)
function loadPhotos(resetGallery = false, competenceId = null, anneeID = '') {

    let totalPages ; // Variable pour stocker le total des pages
    
    // Si c'est un NOUVEAU filtre (resetGallery = true), on recommence à la page 1
    if (resetGallery) {
        currentPage = 1;
        gallery.innerHTML = ''; // Vider la galerie
    }

    // --- CONSTRUCTION DE L'URL DYNAMIQUE ---
    let restUrl = `/wp-json/wp/v2/projet?per_page=${postsPerPage}&page=${currentPage}`;
    //Ajout paramètre filtres si présents issus de taxnomies ACF
        if (competenceId) {
            restUrl += `&competence=${competenceId}`;
        }
        if (anneeID) {
            restUrl += `&annee=${anneeID}`;
        }

    //Utilisation de l'API REST pour TOUTES les requêtes
    fetch(restUrl)
    .then(response => {
        //Récupérer le total des pages (X-WP-TotalPages) dans le header
        //parseInt pour convertir en nombre entier en JS
        totalPages = parseInt(response.headers.get('X-WP-TotalPages'));
        return response.json();
    })
    .then(data => {
        //Vérifier si des articles ont été retournés
        //si 0length=0article et page 1 c'est qu'il n'y a aucun post au total
        if (!data.length && currentPage === 1) {
            // Aucun post trouvé au total
            gallery.innerHTML = '<p>Aucun projet trouvé pour ce filtrage !</p>';
            return;
        }

        // Boucle pour afficher les articles
        data.forEach(post => {
            const article = document.createElement('article');
            article.classList.add('gallery-item');
            article.innerHTML = `
            <a href="${post.link}">
                <img src="${post.featured_image_url}" alt="${post.title.rendered}" />
            </a>
            `;
        
            const info = document.createElement('div');
            info.classList.add('info_overlay');
            info.innerHTML = `
            <img src="wp-content/themes/themeperso/assets/iconeSurvol/Icon_fullscreen.png" alt="Aperçu lightbox" class="apercu"/>
            <a href="${post.link}">
                <img src="wp-content/themes/themeperso/assets/iconeSurvol/Icon_eye.png" alt="Plein écran" class="pleinEcran"/>
            </a>
            <div class="infos-content">
                <p>${post.title.rendered}</p>
                <p>${post.competence_name}</p>
            </div>
            `;
        
            article.appendChild(info);
            gallery.appendChild(article);
        
            // Stocker les données utiles en dataset pour accès simple
            article.dataset.singleprojet = post.featured_image_url;
            article.dataset.annee = post.annee_name;
            article.dataset.competence = post.competence_name;
            article.dataset.link = post.link;
        });    
        
        //Mise à jour de la page courante 
        //lorsque les photos sont chargées avec succès la page courante s'incrémente de 1
        // le bouton n'est plus disabled et son texte redevient "Charger plus"
        currentPage++;
        if (currentPage > totalPages) {
            if (loadMoreButton) loadMoreButton.style.display = 'none';
        } else {
            if (loadMoreButton) loadMoreButton.style.display = 'block';
        }

        //Réinitialiser les écouteurs de la lightbox
        //cad réappeler la fonction GalleryListeners,
        //afin que les éléments de la nouvelle page soient eux aussi push dans le tableau Gallery 
        // et aient eux aussi des écouteurs 
        initGalleryListeners();
    })
    .catch(error => {
        console.error("Erreur de chargement des projets:", error);
        if (loadMoreButton) {
            loadMoreButton.textContent = 'Erreur de chargement';
        }    
    });
}

let currentIndex = 0;
const galleryItems = [];


// Fonction pour ouvrir la lightbox
function openLightbox(index) {
    currentIndex = index;

    //si overlay existe déjà, le supprimer avant d'en créer un nouveau
    if(document.querySelector('.lightbox-overlay')){
        document.querySelector('.lightbox-overlay').remove();
    }

    const overlay = document.createElement('div');
    overlay.classList.add('lightbox-overlay');
    document.body.appendChild(overlay);

    const item = galleryItems[currentIndex];

    // Mettre à jour la lightbox (overlay et contenu)

    let lightbox_content = document.querySelector('.lightbox');
    if (!lightbox_content) {
        lightbox_content = document.createElement('div');
        lightbox_content.classList.add('lightbox');
        document.body.appendChild(lightbox_content);
    }

    lightbox_content.innerHTML = `
        <article class="fleche_prec">
            <i class="fa-solid fa-arrow-left-long"></i>
            <p>Précédente</p>
        </article>
        <article class="previsualisation">
            <a href="${item.dataset.link}">
                <img src="${item.dataset.singleprojet}" alt="Photo sélectionnée" />
            </a>
            <div class="light_rang2">
                <p>${item.dataset.annee}</p>
                <p>${item.dataset.competence}</p>
            </div>
        </article>
        <article class="fleche_suiv">
            <p>Suivante</p>
            <i class="fa-solid fa-arrow-right-long"></i>
        </article>
    `;

    // Bloquer scroll
    document.body.classList.add('no-scroll');

    // Écouteur fermeture overlay
    overlay.addEventListener('click', () => {
        lightbox_content.remove();
        overlay.remove();
        document.body.classList.remove('no-scroll');
    });

    // Navigation précédente
    const precedente = lightbox_content.querySelector('.fleche_prec');
    precedente.addEventListener('click', () => {
        // moyen de boucler en récupérant le modulo de la longueur du tableau CAD le reste de la division
        // si photo n°6, donc élément n°5 pour une longueur de 6, (5-1+6)%6 = 4 donc on revient à l'élément d'avant
        // car 10%6 => 10/6 = 1 reste 4 donc on récupère le 4
        currentIndex = (currentIndex - 1 + galleryItems.length) % galleryItems.length;
        openLightbox(currentIndex);
    });

    // Navigation suivante
    const suivante = lightbox_content.querySelector('.fleche_suiv');
    suivante.addEventListener('click', () => {
        // moyen de boucler en récupérant le modulo de la longueur du tableau CAD le reste de la division
        //si photo 5, élément n°4 pour une longueur de 5, (4+1)%5 = 0 donc on revient au début
        currentIndex = (currentIndex + 1) % galleryItems.length;
        openLightbox(currentIndex);
    });
}

// Fonction pour initialiser les écouteurs sur tous les articles (nouveaux et anciens)
function initGalleryListeners() {
    galleryItems.length = 0; // Réinitialiser le tableau
    Array.from(document.querySelectorAll('.gallery-item')).forEach(item => galleryItems.push(item));// Remplir le tableau avec les éléments actuels
    galleryItems.forEach(item => {
        const infoOverlay = item.querySelector('.info_overlay');

        // Vérifier si les écouteurs ont déjà été ajoutés pour cet élément pour éviter les doublons
        if (!item.dataset.listenersAdded) { 
            // 1. Écouteurs de survol
            item.addEventListener('mouseenter', () => {
                infoOverlay.classList.add('visible');
            });
            item.addEventListener('mouseleave', () => {
                infoOverlay.classList.remove('visible');
            });

            // 2. Écouteur d'ouverture Lightbox
            const apercu = item.querySelector('.apercu');
            apercu.addEventListener('click', () => {
                console.log("apercu visible par js");

                // Trouver l'index de l'élément dans le tableau global
                const index = galleryItems.indexOf(item); 
                openLightbox(index);
            });
            item.dataset.listenersAdded = true; // Marquer l'élément comme initialisé
        }    
    });
}


// Initialisation des écouteurs pour les éléments de filtre
// Sélection des menus de filtre (catégorie et format) selon les ID du template
const selectCompt = document.querySelector('#competenceSelect'); 
const selectAnnee = document.querySelector('#anneeSelect');
function declencherFiltre() {
    let comptId;
        if(selectCompt) {
            comptId = selectCompt.value;
            console.log("Compétence sélectionnée :", comptId);
        } else {
            comptId = null;
        }  
     
        let anneeId;
        if(selectAnnee) {
            anneeId = selectAnnee.value;
            console.log("Année sélectionnée :", anneeId);
        } else {
            anneeId = null;
        }  

    // On appelle loadPhotos avec resetGallery = true pour vider la grille
    loadPhotos(true, comptId, anneeId);
}
// On écoute le changement
if(selectCompt) selectCompt.addEventListener('change', declencherFiltre);
if(selectAnnee) selectAnnee.addEventListener('change', declencherFiltre);

// Et l'écouteur au bouton charger plus
if (loadMoreButton) {
    loadMoreButton.addEventListener('click', function(e) {
        e.preventDefault();

        const comptId = selectCompt ? selectCompt.value : null;
        const anneeId = selectAnnee ? selectAnnee.value : null;
        loadPhotos(false, comptId, anneeId); // false pour ne pas reset la galerie
    });
}

// Initialisation du chargement des photos
if (gallery) {
    // 1. Chargement Initial (Page 1)
    loadPhotos(); 
}

//MENU TOGLLE POUR MOBILE
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const menuContainer = document.querySelector('.nav-menu-container');

    if (menuToggle && menuContainer) {
        menuToggle.addEventListener('click', function() {
            // On ajoute ou retire la classe 'is-open'
            menuContainer.classList.toggle('is-open');
            document.body.classList.add('no-scroll');
            
            // Accessibilité : on change l'état du bouton
            const isOpen = menuContainer.classList.contains('is-open');
            //aria-expanded doit être à true lorsque le menu est ouvert et false lorsqu'il est fermé, pour indiquer l'état du menu aux technologies d'assistance
            menuToggle.setAttribute('aria-expanded', isOpen);
            
            // Optionnel : on change l'icône du burger en croix
            menuToggle.classList.toggle('active');
        });
    }
});
