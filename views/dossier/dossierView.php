<?php
require('views/template/header.php');
require('views/template/navbar.php');
?>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2 text-primary-blue">Gestion des Dossiers</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-funnel"></i>
                                Filtrer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter -->
                <form method="get" class="row mb-4">
                    <!-- conserver l'id employe si besoin -->
                    <input type="hidden" name="H_idEmploye" value="<?= htmlspecialchars($_SESSION['H_idEmploye'] ?? '') ?>">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="recherche" class="form-control" placeholder="Rechercher un dossier..."
                                value="<?= htmlspecialchars($recherche) ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="statut" class="form-select" onchange="this.form.submit()">
                            <option value="tous" <?= $statut === 'tous' ? 'selected' : '' ?>>Tous les statuts</option>
                            <option value="en-cours" <?= $statut === 'en-cours' ? 'selected' : '' ?>>Avec PV (en cours)</option>
                            <option value="signature" <?= $statut === 'signature' ? 'selected' : '' ?>>En attente signature</option>
                            <option value="finalise" <?= $statut === 'finalise' ? 'selected' : '' ?>>Finalisé</option>
                        </select>
                    </div>
                </form>

                <!-- Dossiers List -->
                <div class="row">
                    <?php
                        foreach ($Y_executeDossiers as $dossier) {
                            // formatting the dates
                            $dateCreation = date('d/m/Y', strtotime($dossier->dateCreateDossier));
                            $dateMiseAJour = date('d/m/Y', strtotime($dossier->dateMiseAJour));
                            
                            // Recuperer les 5 dernier caracteres de l'idDossier
                            $idDossier = substr($dossier->idDossier, -5);
                    ?>
                    <!-- Dossier 1 -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm">
                            <?php 
                                if(!empty($dossier->numeroDocAcquisition)) {
                                    $statusClass = 'bg-success text-white';
                                    $etat = 'Finalisé';
                                }elseif(!empty($dossier->numeroDossierTech)) {
                                    $statusClass = 'bg-warning text-dark';
                                    $etat = 'Encours';
                                } else {
                                    $statusClass = 'bg-primary-blue text-white';
                                    $etat = 'Encours';
                                }
                            ?>
                            <div class="card-header <?= $statusClass ?> d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Dossier #<?= $idDossier ?> - <?= $dossier->nomAcheteur ?></h6>
                                <span class="badge bg-warning text-dark"><?= $etat ?></span>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <small class="text-muted">Site & Bloc</small>
                                        <div><?= $dossier->numeroTitreFoncier ?> - <?= $dossier->nomBloc ?> </div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Superficie</small>
                                        <div><?= number_format($dossier->superficieSelection, 0, '', ' ') ?> m²</div>
                                    </div>
                                </div>
                                
                                <!-- Progress Steps -->
                                <div class="mb-3">
                                    <?php
                                        // PV
                                        if (empty($dossier->numeroProcesVerbal)) {
                                            $procesVerbale = "<div class='progress-step current'>
                                                                <div class='progress-step-circle'>1</div>
                                                                <small class='d-block text-center mt-1'>PV</small>
                                                            </div>";
                                            $dossierTechnique = "<div class='progress-step'>
                                                                    <div class='progress-step-circle'>2</div>
                                                                    <small class='d-block text-center mt-1'>Dossier Tech.</small>
                                                                </div>";
                                            $contrat = "<div class='progress-step'>
                                                            <div class='progress-step-circle'>3</div>
                                                            <small class='d-block text-center mt-1'>Acquisition</small>
                                                        </div>";
                                        } elseif (empty($dossier->numeroDossierTech)) {
                                            $procesVerbale = "<div class='progress-step completed'>
                                                                <div class='progress-step-circle completed'><i class='bi bi-check'></i></div>
                                                                <small class='d-block text-center mt-1'>PV</small>
                                                            </div>";
                                            $dossierTechnique = "<div class='progress-step current'>
                                                                    <div class='progress-step-circle'>2</div>
                                                                    <small class='d-block text-center mt-1'>Dossier Tech.</small>
                                                                </div>";
                                            $contrat = "<div class='progress-step'>
                                                            <div class='progress-step-circle'>3</div>
                                                            <small class='d-block text-center mt-1'>Acquisition</small>
                                                        </div>";
                                        } elseif (empty($dossier->numeroDocAcquisition)) {
                                            $procesVerbale = "<div class='progress-step completed'>
                                                                <div class='progress-step-circle completed'><i class='bi bi-check'></i></div>
                                                                <small class='d-block text-center mt-1'>PV</small>
                                                            </div>";
                                            $dossierTechnique = "<div class='progress-step completed'>
                                                                    <div class='progress-step-circle completed'><i class='bi bi-check'></i></div>
                                                                    <small class='d-block text-center mt-1'>Dossier Tech.</small>
                                                                </div>";
                                            $contrat = "<div class='progress-step current'>
                                                            <div class='progress-step-circle'>3</div>
                                                            <small class='d-block text-center mt-1'>Acquisition</small>
                                                        </div>";
                                        } else {
                                            $procesVerbale = "<div class='progress-step completed'>
                                                                <div class='progress-step-circle completed'><i class='bi bi-check'></i></div>
                                                                <small class='d-block text-center mt-1'>PV</small>
                                                            </div>";
                                            $dossierTechnique = "<div class='progress-step completed'>
                                                                    <div class='progress-step-circle completed'><i class='bi bi-check'></i></div>
                                                                    <small class='d-block text-center mt-1'>Dossier Tech.</small>
                                                                </div>";
                                            $contrat = "<div class='progress-step completed'>
                                                            <div class='progress-step-circle completed'><i class='bi bi-check'></i></div>
                                                            <small class='d-block text-center mt-1'>Acquisition</small>
                                                        </div>";
                                        }
                                    ?>
                                    <small class="text-muted">Progression du dossier</small>
                                    <div class="d-flex align-items-center mt-2">
                                            <?= $procesVerbale ?>   
                                            <?= $dossierTechnique ?>
                                            <?= $contrat ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Date création</small>
                                        <div><?= $dateCreation ?></div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Dernière mise à jour</small>
                                        <div><?= $dateMiseAJour ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="btn-group w-100" role="group">
                                    <button type="button" class="btn btn-outline-primary btn-sm" 
                                        data-id="<?= $dossier->idDossier ?>" 
                                        onclick="chargerDetailsDossier(this)" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#dossierModal">
                                        <i class="bi bi-eye"></i> Détails
                                    </button>
                                    <form method="POST">
                                        <button type="submit" name="valider" value="<?= $dossier->idDossier ?>" class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-check-circle"></i> Valider étape
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-file-earmark-pdf"></i> Documents
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                   
                <!-- Pagination -->
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=Y_dossier&statut=<?= $statut ?>&recherche=<?= urlencode($recherche) ?>&p=<?= $page-1 ?>">Précédent</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i=1; $i<=$totalPages; $i++): ?>
                            <li class="page-item <?= $i==$page?'active':'' ?>">
                                <a class="page-link" href="?page=Y_dossier&statut=<?= $statut ?>&recherche=<?= urlencode($recherche) ?>&p=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=Y_dossier&statut=<?= $statut ?>&recherche=<?= urlencode($recherche) ?>&p=<?= $page+1 ?>">Suivant</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </main>
        </div>
    </div>

    <!-- Dossier Details Modal -->
    <div class="modal fade" id="dossierModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="dossier-details-content">
                <!-- Le contenu sera injecté ici par JS après requête AJAX -->
                <div class="text-center p-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- <script>
        function chargerDetailsDossier(button) {
            const idDossier = button.getAttribute('data-id');
            const modalContent = document.getElementById('dossier-details-content');

            // Affiche un loader pendant la récupération
            modalContent.innerHTML = `
                <div class="text-center p-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            `;

            fetch('/land_solution/controller/Y_dossierController.php?action=getDetails&id=' + idDossier +'&H_idEmploye=<?= $_SESSION['H_idEmploye'] ?>')
                .then(response => response.text())
                .then(html => {
                    modalContent.innerHTML = html;
                })
                .catch(error => {
                    console.error("Erreur AJAX :", error);
                    modalContent.innerHTML = `<div class="p-4 text-danger">Erreur lors du chargement des détails.</div>`;
                });
        }
    </script> -->
    <script>
        function chargerDetailsDossier(button) {
            const idDossier = button.getAttribute('data-id');
            const modalContent = document.getElementById('dossier-details-content');

            modalContent.innerHTML = `
                <div class="text-center p-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            `;

            // Construction correcte de l'URL
            const params = new URLSearchParams({
                page: 'Y_dossier',
                action: 'getDetails',
                id: idDossier,
                H_idEmploye: '<?= $_SESSION['H_idEmploye'] ?>'
            });

            fetch('/land_solution/controller/Y_dossierController.php?' + params.toString())
                .then(response => response.text())
                .then(html => {
                    modalContent.innerHTML = html;
                })
                .catch(error => {
                    console.error("Erreur AJAX :", error);
                    modalContent.innerHTML = `<div class="p-4 text-danger">Erreur lors du chargement des détails.</div>`;
                });
        }


    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>