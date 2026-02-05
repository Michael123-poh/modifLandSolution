<?php
session_start();
$currentPage = basename($_SERVER['PHP_SELF']); 

require_once('models/H_databaseConnection.php');
$H_dbConnect = F_databaseConnection("localhost", "systeme_land", "root", "");

// Récupération de l'ID employé depuis l'URL décodée
$Y_idEmployes = $Y_urlDecoder['H_idEmploye'];

// 1. RÉCUPÉRATION DES STATS GLOBALES
$totalSites = F_executeRequeteSql("SELECT COUNT(*) AS total FROM sites");
foreach($totalSites as $Tsite){
    $Totalesite = $Tsite->total;
}

$totalBlocs = F_executeRequeteSql("SELECT COUNT(*) AS total FROM blocs");
foreach($totalBlocs as $Tbloc){
    $Totalebloc = $Tbloc->total;
}

$totalAcheteurs = F_executeRequeteSql("SELECT COUNT(*) AS total FROM acheteur");
foreach($totalAcheteurs as $Tacheteur){
    $Totaleacheteur = $Tacheteur->total;
}

$totalSelections = F_executeRequeteSql("SELECT COUNT(*) AS total FROM selection");
foreach($totalSelections as $Tselection){
    $Totaleselection = $Tselection->total;
}

$totalEncaisse = F_executeRequeteSql("SELECT SUM(montantTransaction) AS total FROM transactions");
foreach($totalEncaisse as $Tencaisse){
    $Totaleencaisse = $Tencaisse->total;
}
// Montant total à payer
$totalAPayer = F_executeRequeteSql("SELECT SUM(montantTotalSelection) AS total FROM selection");
foreach($totalAPayer as $Tpayer){
    $Totalepayer = $Tpayer->total;
}

$resteAPayer = $Totalepayer - $Totaleencaisse;

// 2. DERNIÈRES TRANSACTIONS
$derniereTransactions = F_executeRequeteSql("
    SELECT transactions.idTransaction, transactions.montantTransaction, transactions.dateTransaction, acheteur.nomAcheteur 
    FROM transactions 
    INNER JOIN versements ON transactions.idVersement = versements.idVersement
    INNER JOIN acheteur ON versements.idAcheteur = acheteur.idAcheteur
    ORDER BY dateTransaction DESC 
    LIMIT 5
");

// 3. RÉPARTITION DES VENTES PAR SITE (POUR CAMEMBERT)
$ventesParSite = F_executeRequeteSql("
    SELECT sites.numeroTitreFoncier, SUM(selection.montantTotalSelection) AS total
    FROM selection 
    INNER JOIN blocs ON selection.idBloc = blocs.idBloc
    INNER JOIN sites ON blocs.numeroTitreFoncier = sites.numeroTitreFoncier
    GROUP BY sites.numeroTitreFoncier
");

// 4. PAIEMENTS PAR MOIS (POUR COURBE)

$paiementsParMois = [];
$rows = F_executeRequeteSql("
    SELECT DATE_FORMAT(dateTransaction, '%b %Y') AS mois, SUM(montantTransaction) AS total
    FROM transactions 
    GROUP BY mois 
    ORDER BY MIN(dateTransaction)
");

// Préparer tableau associatif pour Chart.js
if (!empty($rows)) {
    if (isset($rows->mois)) {
        // Si un seul résultat
        $paiementsParMois[$rows->mois] = (int)$rows->total;
    } else {
        foreach ($rows as $r) {
            $paiementsParMois[$r->mois] = (int)$r->total;
        }
    }
}

// 5. CHARGER LA VUE
require('views/dashboard/dashboardView.php');