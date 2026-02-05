<?php
require('views/template/header.php');
require('Views/template/navbar.php');
?>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2 text-primary-blue">Sites & Blocs</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-primary bg-primary-blue border-0 me-2" data-bs-toggle="modal" data-bs-target="#addSiteModal">
                            <i class="bi bi-plus-circle me-2"></i>
                            Nouveau site
                        </button>
                        <button type="button" class="btn btn-success bg-primary-green border-0" data-bs-toggle="modal" data-bs-target="#addBlocModal">
                            <i class="bi bi-grid-3x3-gap me-2"></i>
                            Nouveau bloc
                        </button>
                    </div>
                </div>

                <!-- Sites Overview -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title text-primary-blue"><?= $nombre1 ?></h5>
                                <p class="card-text">Sites actifs</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title text-primary-green"><?= $nombre2 ?></h5>
                                <p class="card-text">Blocs disponibles</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title text-warning"><?= $nombre3 ?></h5>
                                <p class="card-text">Blocs réservés</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title text-danger"><?= $nombre4 ?></h5>
                                <p class="card-text">Blocs vendus</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sites List -->
                <div class="accordion" >
                    <!-- Site A -->
                    <?php
                        foreach($Y_executeSites as $site){
                        $valeur = $site->statut;
                        $statuts = "";

                        // Définition de la couleur et du statut en fonction de la valeur
                        switch ($valeur) {
                            case 0:
                                $statuts = "actif";
                                $couleur = "bg-success";
                                break;
                            case 1:
                                $statuts = "non-actif";
                                $couleur = "bg-danger";
                                break;
                            default:
                                $statuts = "attente";
                                $couleur = "bg-warning";
                                break;
                        }
                        // Formatage de la date
                        setlocale(LC_TIME, 'fr_FR.UTF-8');
                        $timestamp = strtotime($site->dateCreateSite);

                        // Selection des Blocs par site
                        $Y_executeBlocs = F_executeRequeteSql("SELECT * FROM blocs INNER JOIN sites ON blocs.numeroTitreFoncier = sites.numeroTitreFoncier WHERE sites.numeroTitreFoncier = ? ", [$site->numeroTitreFoncier]); 
                        $disponibles = 0;
                        $reservés = 0;
                        $vendus = 0;

                        if(empty($Y_executeBlocs->idBloc)){
                            foreach ($Y_executeBlocs as $blocs) {
                                if ($blocs->statutBloc == 0) {
                                    $disponibles+= 1;
                                } elseif ($blocs->statutBloc == 1) {
                                    $vendus+= 1;
                                } else {
                                    $reservés+= 1;   
                                }
                            }
                        }else{
                            if(($Y_executeBlocs->statutBloc) == 0)
                                $disponibles = 1;
                            elseif(($Y_executeBlocs->statutBloc) == 1)
                                $vendus = 1;
                            else
                                $reservés = 1;
                        }
                    ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#site<?= $site->numeroTitreFoncier ?>">
                                <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                    <div>
                                        <strong>Site:  <?= $site->numeroTitreFoncier ?></strong>
                                        <small class="text-muted d-block">Localisation: <?= $site->localisationSite ?></small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-success me-1"><?= $disponibles ?> disponibles</span>
                                        <span class="badge bg-warning me-1"><?= $reservés ?> réservés</span>
                                        <span class="badge bg-danger"><?= $vendus ?> vendus</span>
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="site<?= $site->numeroTitreFoncier ?>" class="accordion-collapse collapse show" data-bs-parent="#sitesAccordion">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="text-primary-blue">Plan des blocs</h6>
                                        <div class="bloc-grid">
                                            <!-- Affichage des blocs -->
                                            <?php if (empty($Y_executeBlocs)) { ?>
                                                <p class="text-muted">Aucun bloc disponible pour ce site.</p>
                                            <?php }if (empty($Y_executeBlocs->idBloc)) { foreach($Y_executeBlocs as $bloc) { 
                                                $valeur = $bloc->statutBloc;
                                                
                                                // Définition de la couleur et du statut en fonction de la valeur
                                                switch ($valeur) {
                                                    case 0:
                                                        $statut = "disponible";
                                                        $etats = "Disponible";
                                                        break;
                                                    case 1:
                                                        $statut = "vendu";
                                                        $etats = "Vendu";
                                                        break;
                                                    default:
                                                        $statut = "reserve";
                                                        $etats = "Réservé";
                                                        break;
                                                }    

                                            ?>
                                            <div class="bloc-item <?= $statut ?>" title="Bloc A1 - <?= $etats ?>"><?= substr(trim($bloc->nomBloc), -2) ?></div>
                                            <?php } } else{
                                                $valeur = $Y_executeBlocs->statutBloc;
                                                // Définition de la couleur et du statut en fonction de la valeur
                                                switch ($valeur) {
                                                    case 0:
                                                        $statut = "disponible";
                                                        $etats = "Disponible";
                                                        break;
                                                    case 1:
                                                        $statut = "vendu";
                                                        $etats = "Vendu";
                                                        break;
                                                    default:
                                                        $statut = "reserve";
                                                        $etats = "Réservé";
                                                        break;
                                                }
                                            ?>
                                                <div class="bloc-item <?= $statut ?>" title="Bloc A1 - <?= $etats ?>"><?= substr(trim($Y_executeBlocs->nomBloc), -2) ?></div>
                                            <?php } ?>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="text-primary-green">Informations du site</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Superficie Initiale:</strong></td>
                                                <td><?=  number_format($site->superficieInitialeSite, 0, '', ' ') ?> m²</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Superficie Actuel:</strong></td>
                                                <td><?= number_format($site->superficieCourranteSite, 0, '', ' ') ?> m²</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Date création:</strong></td>
                                                <td><?= strftime('%d %b. %Y', $timestamp) ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Statut:</strong></td>
                                                <td><span class="badge <?= $couleur ?>"><?= $statuts ?></span></td>
                                            </tr>
                                        </table>
                                        
                                        <div class="mt-3">
                                            <h6 class="text-primary-blue">Légende</h6>
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="bloc-item disponible me-2" style="width: 20px; height: 20px; font-size: 10px;"></div>
                                                <small>Disponible</small>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="bloc-item reserve me-2" style="width: 20px; height: 20px; font-size: 10px;"></div>
                                                <small>Réservé</small>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="bloc-item vendu me-2" style="width: 20px; height: 20px; font-size: 10px;"></div>
                                                <small>Vendu</small>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <button class="btn btn-outline-primary btn-sm w-100 mb-2" data-bs-toggle="modal" data-bs-target="#modifySite">
                                                <i class="bi bi-pencil"></i> Modifier le site
                                            </button>
                                            <button class="btn btn-outline-success btn-sm w-100">
                                                <i class="bi bi-grid-3x3-gap"></i> Gérer les blocs
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Site Modal -->
    <div class="modal fade" id="addSiteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary-blue text-white">
                    <h5 class="modal-title">Nouveau site</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post" >
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="nomSite" id="nomSite" placeholder="Nom du site" required>
                                <label for="nomSite">Nom du site *</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="localisation" id="localisation" placeholder="Localisation" required>
                                <label for="localisation">Localisation *</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="superficie" id="superficie" placeholder="Superficie" required>
                                    <label for="superficie">Superficie totale (m²)</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="prixMoyen" id="prixMoyen" placeholder="Prix moyen" required>
                                    <label for="prixMoyen">Prix moyen/m² (FCFA)</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <textarea class="form-control" id="description" style="height: 100px" placeholder="Description"></textarea>
                                <label for="description">Description</label>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="creer_Site" class="btn btn-primary bg-primary-blue border-0">Créer le site</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bloc Modal -->
    <div class="modal fade" id="addBlocModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary-green text-white">
                    <h5 class="modal-title">Nouveau bloc</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post" >
                        <div class="mb-3">
                            <div class="form-floating">
                                <select class="form-select" name="siteBloc" id="siteBloc" required>
                                    <option value="" selected disabled> Choisissez un site</option>
                                    <?php foreach($Y_executeSites as $site2) { ?>
                                    <option value="<?= $site2->numeroTitreFoncier ?>"><?= $site2->numeroTitreFoncier ?> - <?= $site2->localisationSite ?></option>
                                    <?php } ?>
                                </select>
                                <label for="siteBloc">Site *</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="numeroBloc" id="numeroBloc" placeholder="Numéro du bloc" required>
                                    <label for="numeroBloc">Numéro du bloc *</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="superficieBloc" id="superficieBloc" placeholder="Superficie" required>
                                    <label for="superficieBloc">Superficie (m²) *</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <select class="form-select" name="statutBloc" id="statutBloc">
                                        <option value="0">Disponible</option>
                                        <option value="2">Réservé</option>
                                        <option value="1">Vendu</option>
                                    </select>
                                    <label for="statutBloc">Statut *</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <textarea class="form-control" id="notesBloc" style="height: 100px" placeholder="Notes"></textarea>
                                <label for="notesBloc">Notes</label>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="enregistrer" class="btn btn-primary bg-primary-green border-0">Créer le bloc</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modifu Site Modal -->
    <div class="modal fade" id="modifySite" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary-blue text-white">
                    <h5 class="modal-title">Modifier Site</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="nomSite" id="nomSite" placeholder="Nom du site" required>
                                <label for="nomSite">Numéro titre foncier *</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="localisation" id="localisation" placeholder="Localisation" required>
                                <label for="localisation">Localisation *</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="superficie" id="superficie" placeholder="Superficie" required>
                                    <label for="superficie">Superficie Actuelle (m²)</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="prixMoyen" id="prixMoyen" placeholder="Prix moyen" required>
                                    <label for="prixMoyen">Prix moyen/m² (FCFA)</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <select class="form-select" name="statutSite" id="statutSite">
                                    <option value="0">Actif</option>
                                    <option value="1">Non actif</option>
                                    <option value="2">En attente</option>
                                </select>
                                <label for="statutSite">Statut</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="modifier_Site" class="btn btn-primary bg-primary-blue border-0">Modifier le site</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modify Bloc Modal -->
    <div class="modal fade" id="modifyBlocModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary-green text-white">
                    <h5 class="modal-title">Modifier Bloc</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <input type="hidden" name="idBloc" id="idBloc">
                        
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="nomBloc" id="nomBloc" placeholder="Nom du bloc" required>
                                <label for="nomBloc">Nom du bloc *</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="superficieBloc" id="superficieBloc" placeholder="Superficie" required>
                                <label for="superficieBloc">Superficie actuelle (m²)</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <select class="form-select" name="statutBloc" id="statutBloc">
                                    <option value="0">Disponible</option>
                                    <option value="2">Réservé</option>
                                    <option value="1">Vendu</option>
                                </select>
                                <label for="statutBloc">Statut *</label>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="modifier_Bloc" class="btn btn-primary bg-primary-green border-0">Modifier le bloc</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <button class="btn btn-primary bg-primary-blue border-0 btn-floating d-md-none" data-bs-toggle="modal" data-bs-target="#addSiteModal">
        <i class="bi bi-plus" style="font-size: 1.5rem;"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gestion des clics sur les blocs
        document.querySelectorAll('.bloc-item').forEach(bloc => {
            bloc.addEventListener('click', function() {
                const blocId = this.textContent;
                const statut = this.classList.contains('disponible') ? 'Disponible' : 
                              this.classList.contains('reserve') ? 'Réservé' : 'Vendu';
                
                alert(`Bloc ${blocId} - Statut: ${statut}`);
                // Ici vous pouvez ajouter la logique pour ouvrir un modal de détails du bloc
            });
        });
    </script>
</body>
</html>