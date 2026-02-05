// Get references to HTML elements
const itemsContainer = document.getElementById('employes-cards-container');
const paginationControls = document.getElementById('pagination-controls');
const noItemsMessage = document.getElementById('no-items-message');

// Pagination settings
const itemsPerPage = 6;
let currentPage = 1;

// Références aux champs du formulaire de MODIFICATION
const updateModal = document.getElementById('updateEmployeModal');
const updateForm = document.getElementById('updateEmployeForm');
const updateIdEmployeInput = document.getElementById('update_idEmploye');
const updateNomInput = document.getElementById('update_nom');
const updatePrenomInput = document.getElementById('update_prenom');
const updatePseudoEmployeInput = document.getElementById('update_pseudoEmploye');
const updateDateNaisEmployeInput = document.getElementById('update_dateNaisEmploye');
const updateTelephoneEmployeInput = document.getElementById('update_telephoneEmploye');
const updateEmailEmployeInput = document.getElementById('update_emailEmploye');
const updateAdresseEmployeInput = document.getElementById('update_adresseEmploye');
const updatePosteEmployeSelect = document.getElementById('update_posteEmploye');


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
    wrapper.innerHTML = '';
    noItemsMessage.innerHTML = '';

    page--;

    const start = rowsPerPage * page;
    const end = start + rowsPerPage;
    const paginatedItems = items.slice(start, end);

    if (paginatedItems.length === 0) {
        noItemsMessage.innerHTML = '<p>Aucun employé à afficher pour cette page.</p>';
        return;
    }

    paginatedItems.forEach(item => {
        // L'URL de modification n'est PLUS utilisée ici directement pour le lien
        // Car nous allons utiliser le data-bs-toggle pour ouvrir la modale
        // et pré-remplir avec JS.
        // La construction de l'URL pour l'action du formulaire se fera via JS si nécessaire.

        const itemElement = document.createElement('div');
        itemElement.className = 'col-lg-4 col-md-6 mb-4';

        itemElement.innerHTML = `
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-primary-green text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">${item.nomEmploye || 'Nom Inconnu'}</h6>
                    <span class="badge bg-light text-dark">Actif</span>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Pseudo:</strong></div>
                        <div class="col-sm-8">${item.pseudoEmploye || 'Aucun pseudo'}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Âge:</strong></div>
                        <div class="col-sm-8">${F_calculerAge(item.dateNaisEmploye) || 'N/A'} ans</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Adresse:</strong></div>
                        <div class="col-sm-8">${item.adresseEmploye || 'Aucune adresse'}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Téléphone:</strong></div>
                        <div class="col-sm-8">${item.telephoneEmploye || 'Aucun numéro'}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Poste:</strong></div>
                        <div class="col-sm-8">${item.libelleFonction || 'Non spécifié'}</div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group w-100" role="group">
                        <!-- Supprime le href direct, utilise data-bs-toggle pour la modale -->
                        <!-- Ajoute un data-attribute pour stocker TOUTES les données de l'employé en JSON -->
                        <a href="#" class="btn btn-outline-primary btn-sm update-btn" 
                           data-bs-toggle="modal" 
                           data-bs-target="#updateEmployeModal"
                           data-employe-id="${item.idEmploye}"
                           data-employe-data='${JSON.stringify(item)}'>
                            <i class="bi bi-eye"></i> Modifier
                        </a>
                    </div>
                </div>
            </div>
        `;
        wrapper.appendChild(itemElement);
    });
    // Après avoir affiché les éléments, attachez les écouteurs pour les boutons de modification
    attachUpdateEventListeners();
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

// Fonction pour configurer les boutons de pagination
function setupPagination(items, wrapper, rowsPerPage) {
    wrapper.innerHTML = '';

    const pageCount = Math.ceil(items.length / rowsPerPage);

    let prevButton = document.createElement('li');
    prevButton.classList.add('page-item');
    let prevLink = document.createElement('a');
    prevLink.classList.add('page-link');
    prevLink.href = '#';
    prevLink.innerHTML = '&laquo; Précédent';
    prevButton.appendChild(prevLink);
    prevLink.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage > 1) {
            currentPage--;
            displayItems(items, itemsContainer, itemsPerPage, currentPage);
            setupPagination(items, paginationControls, itemsPerPage);
        }
    });
    wrapper.appendChild(prevButton);

    for (let i = 1; i <= pageCount; i++) {
        let btn = paginationButton(i, items);
        wrapper.appendChild(btn);
    }

    let nextButton = document.createElement('li');
    nextButton.classList.add('page-item');
    let nextLink = document.createElement('a');
    nextLink.classList.add('page-link');
    nextLink.href = '#';
    nextLink.innerHTML = 'Suivant &raquo;';
    nextButton.appendChild(nextLink);
    nextLink.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage < pageCount) {
            currentPage++;
            displayItems(items, itemsContainer, itemsPerPage, currentPage);
            setupPagination(items, paginationControls, itemsPerPage);
        }
    });
    wrapper.appendChild(nextButton);

    const pageButtons = wrapper.querySelectorAll('.page-item');
    pageButtons.forEach(btn => {
        const link = btn.querySelector('.page-link');
        if (link && parseInt(link.textContent) === currentPage) {
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
        e.preventDefault();
        currentPage = page;
        displayItems(items, itemsContainer, itemsPerPage, currentPage);
        setupPagination(items, paginationControls, itemsPerPage);
    });

    return button;
}

// Fonction pour attacher les écouteurs d'événements aux boutons de modification
function attachUpdateEventListeners() {
    const updateButtons = document.querySelectorAll('.update-btn');
    updateButtons.forEach(button => {
        // Assurez-vous d'ajouter l'écouteur qu'une seule fois si displayItems est appelé plusieurs fois
        // Une solution plus robuste serait de cloner les éléments ou de vérifier si l'écouteur est déjà là.
        // Pour cet exemple, on peut simplement s'assurer que displayItems n'est appelé qu'une fois pour l'affichage initial
        // ou de détacher/réattacher les écouteurs.
        button.removeEventListener('click', handleUpdateClick); // Éviter les écouteurs dupliqués
        button.addEventListener('click', handleUpdateClick);
    });
}

function handleUpdateClick(event) {
    event.preventDefault(); // Empêche la navigation du lien

    const employeeDataString = this.dataset.employeData;
    const employeeData = JSON.parse(employeeDataString); // Parse les données JSON de l'employé
    H_idEmployeUpdate
    // Remplir les champs du formulaire de modification
    updateIdEmployeInput.value = employeeData.idEmploye || '';
    updateNomInput.value = employeeData.nomEmploye || '';
    updatePseudoEmployeInput.value = employeeData.pseudoEmploye || '';
    updateDateNaisEmployeInput.value = employeeData.dateNaisEmploye || '';
    updateTelephoneEmployeInput.value = employeeData.telephoneEmploye || '';
    updateEmailEmployeInput.value = employeeData.emailEmploye || '';
    updateAdresseEmployeInput.value = employeeData.adresseEmploye || '';

    // Pour le select de poste, il faut itérer sur les options pour trouver la bonne
    for (let i = 0; i < updatePosteEmployeSelect.options.length; i++) {
        if (updatePosteEmployeSelect.options[i].value == employeeData.idTypeEmploye) {
            updatePosteEmployeSelect.selectedIndex = i;
            break;
        }
    }
}


// Initialise la pagination au chargement de la page
window.onload = function() {
    if (!allItems || allItems.length === 0) {
        noItemsMessage.innerHTML = '<p class="mt-3 text-center">Aucun employé trouvé.</p>';
        paginationControls.innerHTML = '';
    } else {
        displayItems(allItems, itemsContainer, itemsPerPage, currentPage);
        setupPagination(allItems, paginationControls, itemsPerPage);
    }
    // Ajoutez l'écouteur pour la modale Bootstrap pour s'assurer que le formulaire est rempli avant son affichage
    // C'est une alternative à l'écouteur sur chaque bouton de modification, utile si d'autres choses déclenchent la modale
    updateModal.addEventListener('show.bs.modal', function (event) {
        // Le bouton qui a déclenché la modale
        const button = event.relatedTarget; 
        const employeeDataString = button.dataset.employeData;
        const employeeData = JSON.parse(employeeDataString);
        
        // Remplir les champs du formulaire (répétition du code de handleUpdateClick pour la robustesse)
        updateIdEmployeInput.value = employeeData.idEmploye || '';
        updateNomInput.value = employeeData.nomEmploye || '';
        updatePseudoEmployeInput.value = employeeData.pseudoEmploye || '';
        updateDateNaisEmployeInput.value = employeeData.dateNaisEmploye || '';
        updateTelephoneEmployeInput.value = employeeData.telephoneEmploye || '';
        updateEmailEmployeInput.value = employeeData.emailEmploye || '';
        updateAdresseEmployeInput.value = employeeData.adresseEmploye || '';

        for (let i = 0; i < updatePosteEmployeSelect.options.length; i++) 
        {
            if (updatePosteEmployeSelect.options[i].value == employeeData.idTypeEmploye) {
                updatePosteEmployeSelect.selectedIndex = i;
                break;
            }
        }

        // --- NOUVELLE LOGIQUE : Mettre à jour l'action du formulaire AVEC l'ID de l'employé ---
        //const baseUrl = 'land_solution/H_updateEmploye/'; // L'URL de base pour le contrôleur
        const params = {
            H_idEmploye: H_idEmployeFromSession, // ID de l'employé connecté depuis la session PHP
            H_idEmployeUpdate: employeeData.idEmploye, // L'ID de l'employé à mettre à jour est déjà dans H_idEmployeUpdate (champ caché) et doit aller dans l'URL
            // Si votre contrôleur H_updateEmployeController.php s'attend à $Y_urlDecoder['H_idEmployeUpdate'] dans l'URL
            
        };

        // Construire l'URL d'action complète en utilisant buildEncodedLink directement
        // 'H_updateEmploye' est le nom de la page/contrôleur que votre routeur attend.
        const encodedActionUrl = buildEncodedLink('H_updateEmploye', params);

        // Construire l'URL encodée pour l'action du formulaire
        //const encodedActionUrl = baseUrl + buildEncodedLink('', params).substring(1); // Enlève le premier '/' si buildEncodedLink le met

        // Mettre à jour l'attribut action du formulaire
        updateForm.action = encodedActionUrl;
        console.log("Action du formulaire mise à jour à :", updateForm.action);
    });
};

// --- Fonctions de Recherche et Filtre (Ajoutées pour améliorer l'expérience) ---
const searchInput = document.getElementById('searchInput');
const roleFilter = document.getElementById('roleFilter');

function filterAndDisplayItems() {
    const searchTerm = searchInput.value.toLowerCase();
    const selectedRole = roleFilter.value;

    const filteredItems = allItems.filter(item => {
        const matchesSearch = (item.nomEmploye && item.nomEmploye.toLowerCase().includes(searchTerm)) ||
                              (item.prenomEmploye && item.prenomEmploye.toLowerCase().includes(searchTerm)) ||
                              (item.pseudoEmploye && item.pseudoEmploye.toLowerCase().includes(searchTerm)) ||
                              (item.emailEmploye && item.emailEmploye.toLowerCase().includes(searchTerm)) ||
                              (item.adresseEmploye && item.adresseEmploye.toLowerCase().includes(searchTerm)) ||
                              (item.libelleFonction && item.libelleFonction.toLowerCase().includes(searchTerm));
        
        const matchesRole = selectedRole === '' || (item.libelleFonction && item.libelleFonction === selectedRole);

        return matchesSearch && matchesRole;
    });

    currentPage = 1; // Réinitialise la page après le filtrage
    displayItems(filteredItems, itemsContainer, itemsPerPage, currentPage);
    setupPagination(filteredItems, paginationControls, itemsPerPage);
}

// Écouteurs d'événements pour la recherche et le filtre
searchInput.addEventListener('keyup', filterAndDisplayItems);
roleFilter.addEventListener('change', filterAndDisplayItems);
