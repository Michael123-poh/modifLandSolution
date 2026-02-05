<?php
session_start();
$currentPage = basename($_SERVER['PHP_SELF']);

require_once __DIR__ . '/../models/H_databaseConnection.php';
require_once __DIR__ . '/../models/H_functionsModels.php';


$H_dbConnect = $H_dbConnect ?? F_databaseConnection("localhost", "systeme_land", "root", "");


$Y_urlDecoder = $Y_urlDecoder ?? [];


$action = $_GET['action'] ?? ($Y_urlDecoder['action'] ?? null);
$idDossier = $_GET['id'] ?? ($Y_urlDecoder['id'] ?? null);

if ($action === 'getDetails' && $idDossier) {
    $dossierDetails = F_executeRequeteSql(
        "SELECT dossiers.*, acheteur.*, selection.*, blocs.*, sites.*
         FROM dossiers
         INNER JOIN acheteur ON dossiers.idAcheteur = acheteur.idAcheteur
         INNER JOIN selection ON acheteur.idAcheteur = selection.idAcheteur
         INNER JOIN blocs ON selection.idBloc = blocs.idBloc
         INNER JOIN sites ON blocs.numeroTitreFoncier = sites.numeroTitreFoncier
         WHERE dossiers.idDossier = ?",
        [$idDossier]
    );

    if (is_array($dossierDetails) && count($dossierDetails) > 0) {
        $dossier = $dossierDetails[0];
    } elseif (is_object($dossierDetails)) {
        $dossier = $dossierDetails;
    } else {
        echo "<div class='p-4 text-danger'>Aucun dossier trouvé.</div>";
        exit;
    }

    ob_start();
    ?>
    <div class="modal-header bg-primary-blue text-white">
        <h5 class="modal-title">Détails du Dossier #<?= htmlspecialchars(substr($dossier->idDossier, -5)) ?></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-primary-blue">Informations Client</h6>
                <table class="table table-sm">
                    <tr><td><strong>Nom:</strong></td><td><?= htmlspecialchars($dossier->nomAcheteur) ?></td></tr>
                    <tr><td><strong>CNI:</strong></td><td><?= htmlspecialchars($dossier->numeroCNI) ?></td></tr>
                    <tr><td><strong>Téléphone:</strong></td><td><?= htmlspecialchars($dossier->telephoneAcheteur) ?></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="text-primary-green">Informations Terrain</h6>
                <table class="table table-sm">
                    <tr><td><strong>Site:</strong></td><td><?= htmlspecialchars($dossier->numeroTitreFoncier) ?> - <?= htmlspecialchars($dossier->nomBloc) ?></td></tr>
                    <tr><td><strong>Superficie:</strong></td><td><?= number_format($dossier->superficieSelection ?? 0, 0, '', ' ') ?> m²</td></tr>
                    <tr><td><strong>Prix total:</strong></td><td><?= number_format($dossier->montantTotalSelection ?? 0, 0, '', ' ') ?> FCFA</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
    </div>
    <?php
    echo ob_get_clean();
    exit;
}


if (isset($_POST['valider'])) {
    $valider = $_POST['valider'];

    $row = F_executeRequeteSql("SELECT numeroProcesVerbal, numeroDossierTech, numeroDocAcquisition FROM dossiers WHERE idDossier = ?", [$valider]);
    if (is_array($row) && count($row) > 0) $row = $row[0];

    $processVerbal = $row->numeroProcesVerbal ?? '';
    $dossierTechnique = $row->numeroDossierTech ?? '';
    $aquisition = $row->numeroDocAcquisition ?? '';

    if (empty(trim($processVerbal))) {
        $Y_executeDernierPV = F_executeRequeteSql("SELECT numeroProcesVerbal FROM dossiers ORDER BY numeroProcesVerbal DESC LIMIT 1");
        $dernierNumeroPV = is_array($Y_executeDernierPV) ? ($Y_executeDernierPV[0]->numeroProcesVerbal ?? '') : ($Y_executeDernierPV->numeroProcesVerbal ?? '');
        $nouveauNumeroPV = F_genereMatricule($dernierNumeroPV, 'PV00001');
        F_executeRequeteSql("UPDATE dossiers SET numeroProcesVerbal = ?, dateProcesVerbal = NOW(), dateMiseAJour = NOW() WHERE idDossier = ?", [$nouveauNumeroPV, $valider]);
    } elseif (empty(trim($dossierTechnique))) {
        $Y_executeDernierDT = F_executeRequeteSql("SELECT numeroDossierTech FROM dossiers ORDER BY numeroDossierTech DESC LIMIT 1");
        $dernierNumeroDT = is_array($Y_executeDernierDT) ? ($Y_executeDernierDT[0]->numeroDossierTech ?? '') : ($Y_executeDernierDT->numeroDossierTech ?? '');
        $nouveauNumeroDT = F_genereMatricule($dernierNumeroDT, 'DT00001');
        F_executeRequeteSql("UPDATE dossiers SET numeroDossierTech = ?, dateDossierTech = NOW(), dateMiseAJour = NOW() WHERE idDossier = ?", [$nouveauNumeroDT, $valider]);
    } elseif (empty(trim($aquisition))) {
        $Y_executeDernierDocAcquisition = F_executeRequeteSql("SELECT numeroDocAcquisition FROM dossiers ORDER BY numeroDocAcquisition DESC LIMIT 1");
        $dernierNumeroDocAcquisition = is_array($Y_executeDernierDocAcquisition) ? ($Y_executeDernierDocAcquisition[0]->numeroDocAcquisition ?? '') : ($Y_executeDernierDocAcquisition->numeroDocAcquisition ?? '');
        $nouveauNumeroDocAcquisition = F_genereMatricule($dernierNumeroDocAcquisition, 'DA00001');
        F_executeRequeteSql("UPDATE dossiers SET numeroDocAcquisition = ?, dateDocAcquisition = NOW(), dateMiseAJour = NOW() WHERE idDossier = ?", [$nouveauNumeroDocAcquisition, $valider]);
    }

    header('Location:' . contructUrl('Y_dossier', ['H_idEmploye' => $_SESSION['H_idEmploye']]));
    exit;
}

$Y_idEmployes = $_GET['H_idEmploye'] ?? ($Y_urlDecoder['H_idEmploye'] ?? $_SESSION['H_idEmploye'] ?? null);

// filtres
$statut = $_GET['statut'] ?? ($Y_urlDecoder['statut'] ?? 'tous');
$recherche = trim($_GET['recherche'] ?? ($Y_urlDecoder['recherche'] ?? ''));
$page = max(1, (int)($_GET['p'] ?? $_GET['page'] ?? $Y_urlDecoder['p'] ?? $Y_urlDecoder['page'] ?? 1));
$limit = 6;
$offset = ($page - 1) * $limit;

// base FROM 
$baseFrom = "dossiers
    INNER JOIN acheteur ON dossiers.idAcheteur = acheteur.idAcheteur
    INNER JOIN selection ON acheteur.idAcheteur = selection.idAcheteur
    INNER JOIN blocs ON selection.idBloc = blocs.idBloc
    INNER JOIN sites ON blocs.numeroTitreFoncier = sites.numeroTitreFoncier";

$Y_sql = "SELECT dossiers.*, acheteur.*, selection.*, blocs.*, sites.*,
CASE
    WHEN NULLIF(dossiers.numeroDocAcquisition, '') IS NOT NULL THEN 'finalise'
    WHEN NULLIF(dossiers.numeroDossierTech, '') IS NOT NULL THEN 'signature'
    WHEN NULLIF(dossiers.numeroProcesVerbal, '') IS NOT NULL THEN 'en-cours'
    ELSE 'nouveau'
END AS statut_dossier
FROM $baseFrom
WHERE 1=1";

$params = [];

// filtre statut 
if ($statut !== 'tous') {
    switch ($statut) {
        case 'finalise':
            $Y_sql .= " AND NULLIF(dossiers.numeroDocAcquisition, '') IS NOT NULL";
            break;
        case 'signature':
            $Y_sql .= " AND NULLIF(dossiers.numeroDossierTech, '') IS NOT NULL
                         AND NULLIF(dossiers.numeroDocAcquisition, '') IS NULL";
            break;
        case 'en-cours':
            $Y_sql .= " AND NULLIF(dossiers.numeroProcesVerbal, '') IS NOT NULL
                         AND NULLIF(dossiers.numeroDossierTech, '') IS NULL";
            break;
    }
}

// recherche : utiliser des colonnes existantes (nomAcheteur, idDossier, nomBloc, numeroTitreFoncier, telephoneAcheteur)
if (!empty($recherche)) {
    $Y_sql .= " AND (acheteur.nomAcheteur LIKE ? OR dossiers.idDossier LIKE ? OR blocs.nomBloc LIKE ? OR sites.numeroTitreFoncier LIKE ? OR acheteur.telephoneAcheteur LIKE ?)";
    $s = "%$recherche%";
    $params = array_merge($params, [$s, $s, $s, $s, $s]);
}

$Y_sql .= " ORDER BY dossiers.dateMiseAJour DESC LIMIT $limit OFFSET $offset";

$Y_executeDossiers = F_executeRequeteSql($Y_sql, $params);

// normaliser le résultat pour la vue (toujours un tableau)
if ($Y_executeDossiers === false || $Y_executeDossiers === null) {
    $Y_executeDossiers = [];
} elseif (!is_array($Y_executeDossiers)) {
    $Y_executeDossiers = [$Y_executeDossiers];
}

// --- compter le total pour la pagination
$countSql = "SELECT COUNT(*) as total FROM $baseFrom WHERE 1=1";
$countParams = [];
if ($statut !== 'tous') {
    switch ($statut) {
        case 'finalise':
            $countSql .= " AND NULLIF(dossiers.numeroDocAcquisition, '') IS NOT NULL";
            break;
        case 'signature':
            $countSql .= " AND NULLIF(dossiers.numeroDossierTech, '') IS NOT NULL
                           AND NULLIF(dossiers.numeroDocAcquisition, '') IS NULL";
            break;
        case 'en-cours':
            $countSql .= " AND NULLIF(dossiers.numeroProcesVerbal, '') IS NOT NULL
                           AND NULLIF(dossiers.numeroDossierTech, '') IS NULL";
            break;
    }
}
if (!empty($recherche)) {
    $countSql .= " AND (acheteur.nomAcheteur LIKE ? OR dossiers.idDossier LIKE ? OR blocs.nomBloc LIKE ? OR sites.numeroTitreFoncier LIKE ? OR acheteur.telephoneAcheteur LIKE ?)";
    $s = "%$recherche%";
    $countParams = [$s, $s, $s, $s, $s];
}

$totalResult = F_executeRequeteSql($countSql, $countParams);
if (is_array($totalResult)) {
    $totalDossiers = (int)($totalResult[0]->total ?? 0);
} elseif (is_object($totalResult)) {
    $totalDossiers = (int)($totalResult->total ?? 0);
} else {
    $totalDossiers = 0;
}
$totalPages = max(1, (int)ceil($totalDossiers / $limit));

// passer à la vue
$viewParams = [
    'Y_executeDossiers' => $Y_executeDossiers,
    'statut' => $statut,
    'recherche' => $recherche,
    'page' => $page,
    'totalPages' => $totalPages,
    'totalDossiers' => $totalDossiers
];

extract($viewParams);
require('views/dossier/dossierView.php');
