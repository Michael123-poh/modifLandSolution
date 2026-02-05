<?php
require('views/template/header.php');
require('views/template/navbar.php');
?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2 text-primary-blue">Acheteurs</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-primary bg-primary-green border-0" data-bs-toggle="modal" data-bs-target="#addAcheteurModal">
                            <i class="bi bi-person-plus me-2"></i>
                            Nouvel acheteur
                        </button>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un acheteur...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <select id="siteSelect" class="form-select">
                            <option value="Tous">Tous les sites</option>
                            <?php foreach ($H_executeSites as $site) { ?>
                                <option value="<?= $site->numeroTitreFoncier ?>"><?= $site->numeroTitreFoncier ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row" id="acheteurs-cards-container">
                    <div id="no-items-message" class="col-12 text-center text-muted">
                        </div>
                </div>

                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center" id="pagination-controls">
                    </ul>
                </nav>
            </main>
        </div>
    </div>

    <div class="modal fade" id="addAcheteurModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary-green text-white">
                    <h5 class="modal-title">Nouvel acheteur</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?= contructUrl('H_creerAcheteur' , ['H_idEmploye'=>$_SESSION['H_idEmploye']]) ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="nom" name="H_nomAcheteur" placeholder="Nom">
                                    <label for="nom">Nom *</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="prenom" name="H_prenomAcheteur" placeholder="Prénom">
                                    <label for="prenom">Prénom *</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <select class="form-select" id="typeDocument" name="H_typeDoc">
                                        <option value="cni">CNI</option>
                                        <option value="passeport">Passeport</option>
                                    </select>
                                    <label for="typeDocument">Type de document *</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="numeroDocument" name="H_numDoc" placeholder="Numéro">
                                    <label for="numeroDocument">Numéro de document *</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="age" name="H_dateNais" placeholder="Âge">
                                    <label for="age">Date de Naissance *</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="tel" class="form-control" id="telephone" name="H_telephoneAchteur" placeholder="Téléphone">
                                    <label for="telephone">Téléphone</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="H_adresseAcheteur" name="H_adresseAcheteur" placeholder="Adresse">
                                    <label for="H_adresseAcheteur">Adresse de l'acheteur *</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <select class="form-select" id="H_commercial" name="H_commercial">
                                        <?php foreach ($H_executeEmployes as $employe) { ?>
                                            <option value="<?= $employe->nomEmploye ?>"><?= $employe->nomEmploye ?></option>
                                        <?php } ?>
                                    </select>
                                    <label for="H_commercial">Nom du commercial *</label> 
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <select class="form-select" id="site" name="H_site">
                                        <?php foreach ($H_executeSites as $site) { ?>
                                            <option value="<?= $site->numeroTitreFoncier ?>"><?= $site->numeroTitreFoncier ?></option>
                                        <?php } ?>
                                    </select>
                                    <label for="site">Sélectionner un site *</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <select class="form-select" id="bloc" name="H_bloc">
                                        <?php foreach ($H_executeBloc as $bloc) { ?>
                                            <option value="<?= $bloc->idBloc ?>"><?= $bloc->nomBloc ?></option>
                                        <?php } ?>
                                    </select>
                                    <label for="bloc">Sélectionner un bloc *</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="superficie" name="H_superficie" placeholder="Superficie">
                                    <label for="superficie">Superficie (m²) *</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="prixMetre" name="H_prixMetreCarre" placeholder="Prix par mètre">
                                    <label for="prixMetre">Prix par m² (FCFA) *</label> 
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="H_montantVersement" name="H_montantVersement" placeholder="Montant du versement">
                                    <label for="H_montantVersement">Montant du versement (FCFA)*</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <textarea class="form-control" id="notes" name="H_notesAcheteur" style="height: 100px" placeholder="Notes"></textarea>
                                    <label for="notes">Notes</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" name="Annuler">Annuler</button>
                            <button type="submit" class="btn btn-primary bg-primary-green border-0" name="Enregistrer" value="">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <button class="btn btn-primary bg-primary-green border-0 btn-floating d-md-none" data-bs-toggle="modal" data-bs-target="#addAcheteurModal">
        <i class="bi bi-plus" style="font-size: 1.5rem;"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const allItems = <?php echo $json_items; ?>;
    </script>
    <script src="../views/assets/js/paginationAcheteurs.js"></script>
</body>
</html>