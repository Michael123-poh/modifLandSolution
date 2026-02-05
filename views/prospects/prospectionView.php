<?php
require('views/template/header.php');
require('views/template/navbar.php');
?>
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <!-- Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom border-border">
                    <h1 class="h2 font-heading font-bold text-foreground">Gestion de la Prospection</h1>
                </div>

                <!-- Action Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3" data-bs-toggle="modal" data-bs-target="#addProspectModal">
                        <div class="card bg-card border-border hover:shadow-lg transition-shadow duration-200 cursor-pointer" onclick="showAddProspectModal()">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-person-plus text-primary" style="font-size: 2rem;"></i>
                                <h6 class="card-title mt-2 font-heading font-semibold text-card-foreground">Ajouter Prospect</h6>
                                <p class="card-text text-muted-foreground small">Nouveau prospect</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3" data-bs-toggle="modal" data-bs-target="#addDescenteModal">
                        <div class="card bg-card border-border hover:shadow-lg transition-shadow duration-200 cursor-pointer" onclick="showAddDescenteModal()">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-geo-alt text-secondary" style="font-size: 2rem;"></i>
                                <h6 class="card-title mt-2 font-heading font-semibold text-card-foreground">Créer Descente</h6>
                                <p class="card-text text-muted-foreground small">Planifier visite terrain</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3" data-bs-toggle="modal" data-bs-target="#clientDescenteModal">
                        <div class="card bg-card border-border hover:shadow-lg transition-shadow duration-200 cursor-pointer" onclick="showClientDescenteModal()">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-people text-accent" style="font-size: 2rem;"></i>
                                <h6 class="card-title mt-2 font-heading font-semibold text-card-foreground">Client Descente</h6>
                                <p class="card-text text-muted-foreground small">Ajouter client visite</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3" data-bs-toggle="modal" data-bs-target="#descenteListModal">
                        <div class="card bg-card border-border hover:shadow-lg transition-shadow duration-200 cursor-pointer" onclick="showDescenteListModal()">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-calendar-check text-chart-3" style="font-size: 2rem;"></i>
                                <h6 class="card-title mt-2 font-heading font-semibold text-card-foreground">Liste Descentes</h6>
                                <p class="card-text text-muted-foreground small">Consulter planning</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card bg-card border-border mb-4">
                    <div class="card-body">
                        <h6 class="card-title font-heading font-semibold text-card-foreground mb-3">
                            <i class="bi bi-funnel me-2"></i>Filtres
                        </h6>
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <input type="text" name="" class="form-control bg-input border-border text-foreground" id="searchProspect" placeholder="Rechercher prospect..." onkeyup="filterProspects()">
                            </div>
                            <div class="col-md-3 mb-2">
                                <select class="form-select bg-input border-border text-foreground" id="statusFilter" onchange="filterProspects()">
                                    <option value="">Tous les statuts</option>
                                    <option value="nouveau">Nouveau</option>
                                    <option value="contacte">Contacté</option>
                                    <option value="interesse">Intéressé</option>
                                    <option value="non-interesse">Non intéressé</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <input type="date" name="" class="form-control bg-input border-border text-foreground" id="dateFilter" onchange="filterProspects()">
                            </div>
                            <div class="col-md-3 mb-2">
                                <button type="button" class="btn bg-secondary text-secondary-foreground w-100" onclick="resetFilters()">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Réinitialiser
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prospects Table -->
                <div class="card bg-card border-border">
                    <div class="card-header bg-muted border-border">
                        <h6 class="card-title mb-0 font-heading font-semibold text-card-foreground">
                            <i class="bi bi-table me-2"></i>Liste des Prospects
                            <span class="badge bg-primary text-primary-foreground ms-2" id="prospectCount">0</span>
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="prospectsTable">
                                <thead class="bg-muted">
                                    <tr>
                                        <th class="text-muted-foreground text-center font-medium px-4 py-3">Nom</th>
                                        <th class="text-muted-foreground text-center font-medium px-4 py-3">Téléphone</th>
                                        <th class="text-muted-foreground text-center font-medium px-4 py-3">Email</th>
                                        <th class="text-muted-foreground text-center font-medium px-4 py-3">Lieu de prospection</th>
                                        <th class="text-muted-foreground text-center font-medium px-4 py-3">Statut</th>
                                        <th class="text-muted-foreground text-center font-medium px-4 py-3">Date Ajout</th>
                                        <th class="text-muted-foreground text-center font-medium px-4 py-3">Notes</th>
                                        <th class="text-muted-foreground text-center font-medium px-4 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="prospectsTableBody">
                                    <!-- Les prospects seront ajoutés dynamiquement -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Ajouter Prospect -->
    <div class="modal fade" id="addProspectModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-card border-border">
                <div class="modal-header bg-muted border-border">
                    <h5 class="modal-title font-heading font-semibold text-card-foreground">
                        <i class="bi bi-person-plus me-2"></i>Ajouter un Prospect
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="<?= contructUrl('H_prospect' , ['H_idEmploye'=>$_SESSION['H_idEmploye']]) ?>" id="prospectForm" >
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-card-foreground font-medium">Nom complet *</label>
                                <input type="text" name="H_nomProspect" class="form-control bg-input border-border text-foreground" id="prospectName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-card-foreground font-medium">Téléphone *</label>
                                <input type="tel" name="H_telephoneProspect" class="form-control bg-input border-border text-foreground" id="prospectPhone" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-card-foreground font-medium">Email</label>
                                <input type="email" name="H_emailProspect" class="form-control bg-input border-border text-foreground" id="prospectEmail">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-card-foreground font-medium">Statut</label>
                                <select class="form-select bg-input border-border text-foreground" name="H_statutProspect" id="prospectStatus">
                                    <option value="nouveau">Nouveau</option>
                                    <option value="contacte">Contacté</option>
                                    <option value="interesse">Intéressé</option>
                                    <option value="non-interesse">Non intéressé</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-card-foreground font-medium">Adresse/Lieu de prospection</label>
                            <input type="text" name="H_lieuProspection" class="form-control bg-input border-border text-foreground" id="prospectAddress">
                        </div> 
                        <div class="mb-3">
                            <label class="form-label text-card-foreground font-medium">Notes</label>
                            <textarea name="H_notesProspect" class="form-control bg-input border-border text-foreground" id="prospectNotes" rows="3"></textarea>
                        </div>
                        <div class="modal-footer bg-muted border-border">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="addProspect" class="btn bg-primary text-primary-foreground" onclick="addProspect()">
                                <i class="bi bi-check-lg me-1"></i>Ajouter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Créer Descente -->
    <div class="modal fade" id="addDescenteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-card border-border">
                <div class="modal-header bg-muted border-border">
                    <h5 class="modal-title font-heading font-semibold text-card-foreground">
                        <i class="bi bi-geo-alt me-2"></i>Créer une Descente
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="<?= contructUrl('H_prospect' , ['H_idEmploye'=>$_SESSION['H_idEmploye']]) ?>" action="/land_solution/<?= encodeUrl(['page'=>'H_prospect' , 'H_idEmploye'=>$_SESSION['H_idEmploye']])?>" id="descenteForm">
                        <div class="mb-3">
                            <label class="form-label text-card-foreground font-medium">Nom de la descente *</label>
                            <input type="text" name="H_nomDescente" class="form-control bg-input border-border text-foreground" id="descenteName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-card-foreground font-medium">Date prévue *</label>
                            <input type="date" name="H_dateDescente" class="form-control bg-input border-border text-foreground" id="descenteDate" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-card-foreground font-medium">Lieu</label>
                            <input type="text" name="H_lieuDescente" class="form-control bg-input border-border text-foreground" id="descenteLocation">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-card-foreground font-medium">Nombre de personnes prevu</label>
                            <input type="number" name="H_nbrePersonne" class="form-control bg-input border-border text-foreground" id="descentePersonCount">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-card-foreground font-medium">Description</label>
                            <textarea name="H_descriptionDescente" class="form-control bg-input border-border text-foreground" id="descenteDescription" rows="2"></textarea>
                        </div>
                        <div class="modal-footer bg-muted border-border">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="addDescente" class="btn bg-primary text-white-foreground" onclick="createDescente()">
                                <i class="bi bi-check-lg me-1"></i>Créer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Client pour Descente -->
    <div class="modal fade" id="clientDescenteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-card border-border">
                <div class="modal-header bg-muted border-border">
                    <h5 class="modal-title font-heading font-semibold text-card-foreground">
                        <i class="bi bi-people me-2"></i>Ajouter Client pour Descente
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="<?= contructUrl('H_prospect' , ['H_idEmploye'=>$_SESSION['H_idEmploye']]) ?>" id="clientDescenteForm">
                        <div class="mb-3">
                            <label class="form-label text-card-foreground font-medium">Sélectionner une descente *</label>
                            <select class="form-select bg-input border-border text-foreground" id="selectDescente" name="H_idDescente" required>
                                <option value="">Choisir une descente...</option>
                                <?php foreach($listeDescentes as $descente){?>
                                    <option value="<?= $descente->idDescente ?>"><?= htmlspecialchars($descente->nomDescente) ?> - <?= htmlspecialchars($descente->dateDescente) ?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-card-foreground font-medium">Nom du client *</label>
                            <input type="text" name="H_nomClient" class="form-control bg-input border-border text-foreground" id="clientName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-card-foreground font-medium">Téléphone *</label>
                            <input type="tel" name="H_telephoneClient" class="form-control bg-input border-border text-foreground" id="clientPhone" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-card-foreground font-medium">Montant payé *</label>
                            <input type="number" name="H_montantDescente" class="form-control bg-input border-border text-foreground" id="clientEmail" required>
                        </div>
                        <div class="modal-footer bg-muted border-border">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="addCltDescente" class="btn btn-accent text-accent-foreground" onclick="addClientToDescente()">
                                <i class="bi bi-check-lg me-1"></i>Ajouter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Liste des Descentes -->
    <div class="modal fade" id="descenteListModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content bg-card border-border">
                <div class="modal-header bg-muted border-border">
                    <h5 class="modal-title font-heading font-semibold text-card-foreground">
                        <i class="bi bi-calendar-check me-2"></i>Liste des Descentes
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label text-card-foreground font-medium">Date début</label>
                            <input type="date" name="" class="form-control bg-input border-border text-foreground" id="startDate">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-card-foreground font-medium">Date fin</label>
                            <input type="date" name="" class="form-control bg-input border-border text-foreground" id="endDate">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn bg-primary text-primary-foreground w-100" onclick="filterDescentes()">
                                <i class="bi bi-search me-1"></i>Filtrer
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-muted">
                                <tr>
                                    <th class="text-muted-foreground font-medium">Descente</th>
                                    <th class="text-muted-foreground font-medium">Date</th>
                                    <th class="text-muted-foreground font-medium">Lieu</th>
                                    <th class="text-muted-foreground font-medium">Clients</th>
                                    <th class="text-muted-foreground font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="descenteTableBody">
                                <!-- Les descentes seront ajoutées dynamiquement -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-muted border-border">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.5.31/dist/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
    <script>
        const allItemsProspects = <?= $items_json_prospects; ?>;
        const allItemsDescentes = <?= $items_json_descentes; ?>;
        // La variable PHP pour l'ID de session, utilisée par JavaScript
        const H_idEmployeFromSession = "<?php echo isset($_SESSION['H_idEmploye']) ? $_SESSION['H_idEmploye'] : ''; ?>";
    </script>
    <script src="../views/assets/js/prospection.js"></script>
</body>
</html>
