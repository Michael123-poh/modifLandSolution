<?php
require('views/template/header.php');
require('views/template/navbar.php');
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="row justify-content-center my-4">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary-green text-white text-center py-3">
                    <h3 class="mb-0">Modifier un acheteur</h3>
                    <small>Prospecté par : <?= isset($H_executeGetInfoAcheteur) ? $H_executeGetInfoAcheteur->nomCommercial : '' ?></small>
                </div>
                <div class="card-body">
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="nom" name="H_nomAcheteur" placeholder="Nom et Prénom" value="<?= isset($H_executeGetInfoAcheteur) ? $H_executeGetInfoAcheteur->nomAcheteur : '' ?>">
                                    <label for="nom">Nom et Prénom *</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="age" name="H_dateNais" placeholder="Date de Naissance" value="<?= isset($H_executeGetInfoAcheteur) ? $H_executeGetInfoAcheteur->dateNaisAcheteur : '' ?>">
                                    <label for="age">Date de Naissance *</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="tel" class="form-control" id="telephone" name="H_telephoneAchteur" placeholder="Téléphone" value="<?= isset($H_executeGetInfoAcheteur) ? $H_executeGetInfoAcheteur->telephoneAcheteur : '' ?>">
                                    <label for="telephone">Téléphone</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="H_adresseAcheteur" name="H_adresseAcheteur" placeholder="Adresse" value="<?= isset($H_executeGetInfoAcheteur) ? $H_executeGetInfoAcheteur->adresseAcheteur : '' ?>">
                                    <label for="H_adresseAcheteur">Adresse de l'acheteur *</label>
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
                                        <input type="text" class="form-control" id="numeroDocument" name="H_numDoc" placeholder="Numéro" value="<?= isset($H_executeGetInfoAcheteur) ? $H_executeGetInfoAcheteur->numeroCNI : '' ?>">
                                        <label for="numeroDocument">Numéro de document *</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <select class="form-select" id="site" name="H_site">
                                            <?php foreach ($H_executeSites as $site) { ?>
                                                <option value="<?= $site->numeroTitreFoncier ?>" <?= (isset($H_executeGetLot) && $H_executeGetLot->numeroTitreFoncier == $site->numeroTitreFoncier) ? 'selected' : '' ?>>
                                                    <?= $site->numeroTitreFoncier ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <label for="site">Sélectionner un site *</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <select class="form-select" id="bloc" name="H_bloc">
                                            <?php foreach ($H_executeBloc as $bloc) { ?>
                                                <option value="<?= $bloc->idBloc ?>" <?= (isset($H_executeGetLot) && $H_executeGetLot->idBloc == $bloc->idBloc) ? 'selected' : '' ?>>
                                                    <?= $bloc->nomBloc ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <label for="bloc">Sélectionner un bloc *</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="superficie" name="H_superficie" placeholder="Superficie" value="<?= isset($H_executeGetInfoSelection) ? $H_executeGetInfoSelection->superficieSelection : '' ?>">
                                        <label for="superficie">Superficie (m²) *</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="prixMetre" name="H_prixMetreCarre" placeholder="Prix par mètre" value="<?= isset($H_executeGetInfoSelection) ? $H_executeGetInfoSelection->montantParMetre : '' ?>">
                                        <label for="prixMetre">Prix par m² (FCFA) *</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="H_montantVersement" name="H_montantVersement" placeholder="Montant du versement" value="<?= isset($H_executeGetInfoVersement) ? $H_executeGetInfoVersement->montantVersement : '' ?>">
                                        <label for="H_montantVersement">Montant du versement (FCFA)*</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <select class="form-select" id="H_commercial" name="H_commercial">
                                            <?php foreach ($H_executeEmployes as $employe) { ?>
                                                <option value="<?= $employe->nomEmploye ?>" <?= (isset($H_executeGetInfoAcheteur) && $H_executeGetInfoAcheteur->nomCommercial == $employe->nomEmploye) ? 'selected' : '' ?>>
                                                    <?= $employe->nomEmploye ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <label for="H_commercial">Nom du commercial *</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="notes" name="H_notesAcheteur" style="height: 100px" placeholder="Notes"><?= isset($H_executeGetInfoAcheteur) ? $H_executeGetInfoAcheteur->notesAcheteur : '' ?></textarea>
                                        <label for="notes">Notes</label>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer d-flex justify-content-end gap-2">
                                <button type="reset" class="btn btn-secondary" name="Annuler">Annuler</button>
                                <button type="submit" class="btn btn-primary bg-primary-green border-0" name="Modifier" >Modifier</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>