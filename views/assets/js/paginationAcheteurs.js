// path/to/your/paginationAcheteurs.js (ou le chemin que vous avez choisi)

// Get references to HTML elements
const itemsContainer = document.getElementById('acheteurs-cards-container'); // Utilisez le nouvel ID
const paginationControls = document.getElementById('pagination-controls');
const noItemsMessage = document.getElementById('no-items-message');

// Pagination settings
const itemsPerPage = 6; // Vous aviez 4 colonnes, 6 ou 9 est bien pour la mise en page
let currentPage = 1;


// Equivalent de la function PHP pour construire l'URL encodée
// Ici ont encode les paramètres de la page en Base64 et les formate pour l'URL
function buildEncodedLink(page, params = {}) {
    let encodedParams = '';
    if (Object.keys(params).length > 0) {
        const jsonStr = JSON.stringify(params);
        encodedParams = btoa(jsonStr)
            .replace(/\+/g, '-')
            .replace(/\//g, '_')
            .replace(/=+$/, '');
    }
    return `/land_solution/${page}/${encodedParams}`;
}





// Fonction pour rendre les éléments de la page actuelle
function displayItems(items, wrapper, rowsPerPage, page) {
    wrapper.innerHTML = ''; // Efface les éléments existants
    noItemsMessage.innerHTML = ''; // Efface le message "aucun élément"

    page--; // Ajuste le numéro de page pour l'indexation par 0 (pour slice)

    const start = rowsPerPage * page;
    const end = start + rowsPerPage;
    const paginatedItems = items.slice(start, end);

    if (paginatedItems.length === 0) {
        noItemsMessage.innerHTML = '<p>Aucun acheteur à afficher pour cette page.</p>';
        return;
    }

    paginatedItems.forEach(item => {
        // Construire les URLs pour les detil des acheteurs
        // Utilisez la fonction buildEncodedLink pour créer les liens encodés
        // on stock le resultat dans une variable 
        const detailUrl = buildEncodedLink('Y_acheteurDetail', {
            H_idEmploye: item.idEmploye || '',
            Y_idAcheteur: item.idAcheteur || ''
        });


        // Construire l'URL pour le dossier
        // on stock le resultat dans une variable 
        const dossierUrl = buildEncodedLink('Y_dossier', {
            H_idEmploye: item.idEmploye || '',
            Y_idAcheteur: item.idAcheteur || ''
        });

        // Construire l'URL pour la mise à jour des acheteurs
        // on stock le resultat dans une variable 
        const updateUrl = buildEncodedLink('H_updateAcheteur', {
            H_idEmploye: item.idEmploye || '',
            Y_idAcheteur: item.idAcheteur || ''
        });

        // Crée l'élément HTML pour chaque acheteur
        // Assurez-vous que les noms de propriétés (e.g., item.nomAcheteur) correspondent à ceux de votre BD
        const itemElement = document.createElement('div');
        itemElement.className = 'col-lg-4 col-md-6 mb-4'; // Utilisez les classes de grille de Bootstrap

        // ATTENTION : Pour éviter les XSS, utilisez textContent si les données ne sont pas fiables
        // Ici, je vais recréer la structure de votre carte PHP
        itemElement.innerHTML = `
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-primary-green text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">${item.nomAcheteur || 'Nom Inconnu'}</h6>
                    <span class="badge bg-light text-dark">Actif</span>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>CNI:</strong></div>
                        <div class="col-sm-8">${item.numeroCNI || 'N/A'}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Âge:</strong></div>
                        <div class="col-sm-8">${F_calculerAge(item.dateNaisAcheteur) || 'N/A'} ans</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Site:</strong></div>
                        <div class="col-sm-8">${item.numeroTitreFoncier || 'N/A'} - ${item.nomBloc || 'N/A'}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Superficie:</strong></div>
                        <div class="col-sm-8">${formatNumber(item.superficieSelection) || 'N/A'} m²</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Prix/m²:</strong></div>
                        <div class="col-sm-8">${formatNumber(item.montantParMetre) || 'N/A'} FCFA</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Total:</strong></div>
                        <div class="col-sm-8"><strong class="text-success">${formatNumber(item.montantTotalSelection) || 'N/A'} FCFA</strong></div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group w-100" role="group">
                        <!-- Afficher ici -->
                        <a href="${detailUrl}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i> Voir
                        </a>
                        <a href="${dossierUrl}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-eye"></i> Dossier
                        </a>
                        <a href="${updateUrl}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                    </div>
                </div>
            </div>
        `;
        wrapper.appendChild(itemElement);
    });
}

// Fonction pour calculer l'âge (PHP F_calculerAge équivalent)
function F_calculerAge(dateNaissance) {
    if (!dateNaissance) return '';
    const birthDate = new Date(dateNaissance);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
}

// Fonction pour formater les nombres (PHP number_format équivalent)
function formatNumber(num) {
    if (num === null || typeof num === 'undefined') return '';
    return Number(num).toLocaleString('fr-FR'); // Adapte au format français avec espaces pour milliers
}


// Fonction pour configurer les boutons de pagination
function setupPagination(items, wrapper, rowsPerPage) {
    wrapper.innerHTML = ''; // Efface les contrôles existants

    const pageCount = Math.ceil(items.length / rowsPerPage);

    // Bouton 'Précédent'
    let prevButton = document.createElement('li');
    prevButton.classList.add('page-item');
    let prevLink = document.createElement('a');
    prevLink.classList.add('page-link');
    prevLink.href = '#';
    prevLink.innerHTML = '&laquo; Précédent';
    prevButton.appendChild(prevLink);
    prevLink.addEventListener('click', (e) => {
        e.preventDefault(); // Empêche le défilement vers le haut
        if (currentPage > 1) {
            currentPage--;
            displayItems(items, itemsContainer, itemsPerPage, currentPage);
            setupPagination(items, paginationControls, itemsPerPage); // Re-rendre les contrôles
        }
    });
    wrapper.appendChild(prevButton);

    // Boutons numérotés
    for (let i = 1; i <= pageCount; i++) {
        let btn = paginationButton(i, items);
        wrapper.appendChild(btn);
    }

    // Bouton 'Suivant'
    let nextButton = document.createElement('li');
    nextButton.classList.add('page-item');
    let nextLink = document.createElement('a');
    nextLink.classList.add('page-link');
    nextLink.href = '#';
    nextLink.innerHTML = 'Suivant &raquo;';
    nextButton.appendChild(nextLink);
    nextLink.addEventListener('click', (e) => {
        e.preventDefault(); // Empêche le défilement vers le haut
        if (currentPage < pageCount) {
            currentPage++;
            displayItems(items, itemsContainer, itemsPerPage, currentPage);
            setupPagination(items, paginationControls, itemsPerPage); // Re-rendre les contrôles
        }
    });
    wrapper.appendChild(nextButton);

    // Mettre à jour l'état actif et désactivé des boutons
    const pageButtons = wrapper.querySelectorAll('.page-item');
    pageButtons.forEach(btn => {
        const link = btn.querySelector('.page-link');
        if (link && parseInt(link.textContent) === currentPage) { // Vérifie si le texte est un nombre de page
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    if (currentPage === 1) {
        prevButton.classList.add('disabled');
    } else {
        prevButton.classList.remove('disabled');
    }
    if (currentPage === pageCount) {
        nextButton.classList.add('disabled');
    } else {
        nextButton.classList.remove('disabled');
    }
}

// Fonction utilitaire pour créer un seul bouton de pagination
function paginationButton(page, items) {
    let button = document.createElement('li');
    button.classList.add('page-item');
    let link = document.createElement('a');
    link.classList.add('page-link');
    link.href = '#';
    link.textContent = page;
    button.appendChild(link);

    link.addEventListener('click', (e) => {
        e.preventDefault(); // Empêche le défilement vers le haut
        currentPage = page;
        displayItems(items, itemsContainer, itemsPerPage, currentPage);
        setupPagination(items, paginationControls, itemsPerPage); // Re-rendre les contrôles
    });

    return button;
}


// Initialise la pagination au chargement de la page
window.onload = function() {
    // Vérifie si allItems est défini et est un tableau non vide
    if (typeof allItems !== 'undefined' && allItems && allItems.length > 0) {
        displayItems(allItems, itemsContainer, itemsPerPage, currentPage);
        setupPagination(allItems, paginationControls, itemsPerPage);
    } else {
        noItemsMessage.innerHTML = '<p class="mt-3">Aucun acheteur trouvé.</p>';
        paginationControls.innerHTML = ''; // Masque les contrôles si pas d'éléments
    }
};