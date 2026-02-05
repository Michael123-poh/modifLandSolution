<?php
session_start();
$currentPage = basename($_SERVER['PHP_SELF']); 

// 1. Connexion à la BD (comme dans votre exemple)
require_once('models/H_databaseConnection.php');
$H_dbConnect = F_databaseConnection("localhost", "systeme_land", "root", "");

$Y_idEmployes = $Y_urlDecoder['H_idEmploye']; 

// 2. Selection des sites
$Y_executeSites= F_executeRequeteSql("SELECT * FROM sites ");

// 3. Selection des Blocs par site
$Y_executeBlocs = F_executeRequeteSql("SELECT * FROM blocs INNER JOIN sites on blocs.numeroTitreFoncier = sites.numeroTitreFoncier"); 

// 4. Creation d'un site
if (isset($_POST['creer_Site'])) {
    extract($_POST);
    $taille = F_tailleChaineSupChaineMax(10, $nomSite);
    if ($taille == 2 ){
        $_SESSION['message'] = "Le nom du site doit avoir au moins 10 caratere.";
    }else{
        $Y_insertSite = "INSERT INTO sites (numeroTitreFoncier, localisationSite, superficieInitialeSite, superficieCourranteSite, prix_Vente, dateCreateSite) VALUES (?, ?, ?, ?, ?, NOW())";
        $tableauValeurs = [$nomSite, $localisation, $superficie, $superficie, $prixMoyen];
        $Y_excuteInsertSite = F_executeRequeteSql($Y_insertSite, $tableauValeurs);
        header('Location:'.contructUrl('Y_site' , ['H_idEmploye'=>$_SESSION['H_idEmploye']]));
        exit;
    }
}

// 5. Creation d'un bloc
if (isset($_POST['enregistrer'])) {
    extract($_POST);

    
        // 2. Recuperer le dernier idBloc
        $Y_executeDernierBloc = F_executeRequeteSql("SELECT idBloc FROM blocs ORDER BY idBloc DESC LIMIT 1");

        foreach ($Y_executeDernierBloc as $dernier_Bloc) {
            $dernierIdBloc = $dernier_Bloc->idBloc;
        }

        // 3. Generer le nouveau idBloc
        $nouvelleIdBloc = F_genereMatricule($dernierIdBloc, 'BLC00001');


        $Y_insertBloc = "INSERT INTO blocs (idBloc, nomBloc, superficieinitialBloc, superficieCourranteBloc, numeroTitreFoncier, dateCreateBloc) VALUES (?, ?, ?, ?, ?, NOW())";
        $tableauValeurs = [$nouvelleIdBloc, $numeroBloc, $superficieBloc, $superficieBloc, $siteBloc];
        $Y_excuteInsertBloc = F_executeRequeteSql($Y_insertBloc, $tableauValeurs);
        header('Location:'.contructUrl('Y_site' , ['H_idEmploye'=>$_SESSION['H_idEmploye']]));
        exit;

}

// 6. Nombre de sites avec statut = 0 (actif)
$Y_executeNombreSitesActif = F_executeRequeteSql("SELECT COUNT(*) AS nb_sites_actifs FROM sites WHERE statut = 0");
foreach($Y_executeNombreSitesActif as $nombreSite){

    if ($nombreSite->nb_sites_actifs == NULL){
        $nombre1 = 0;
    }else
        $nombre1 = $nombreSite->nb_sites_actifs;
}

// 7. Nombre de blocs disponibles (statutBloc = 0)
$Y_executeNombreBlocsDisponibles = F_executeRequeteSql("SELECT COUNT(*) AS nb_blocs_disponibles FROM blocs WHERE statutBloc = 0");
foreach($Y_executeNombreBlocsDisponibles as $nombreBlocDisponible){

    if ($nombreBlocDisponible->nb_blocs_disponibles == NULL){
        $nombre2 = 0;
    }else
        $nombre2 = $nombreBlocDisponible->nb_blocs_disponibles;
}

// 8. Nombre de blocs réservés (statutBloc = 2)
$Y_executeNombreBlocsReserves = F_executeRequeteSql("SELECT COUNT(*) AS nb_blocs_reserves FROM blocs WHERE statutBloc = 2");
foreach($Y_executeNombreBlocsReserves as $nombreBlocReserve){

    if ($nombreBlocReserve->nb_blocs_reserves == NULL){
        $nombre3 = 0;
    }else
        $nombre3 = $nombreBlocReserve->nb_blocs_reserves;
}

// 9. Nombre de blocs vendus (statutBloc = 1)
$Y_executeNombreBlocsVendus = F_executeRequeteSql("SELECT COUNT(*) AS   nb_blocs_vendus FROM blocs WHERE statutBloc = 1");
foreach($Y_executeNombreBlocsVendus as $nombreBlocVendu){

    if ($nombreBlocVendu->nb_blocs_vendus == NULL){
        $nombre4 = 0;
    }else
        $nombre4 = $nombreBlocVendu->nb_blocs_vendus;
}

// 10. Modification d’un site
if (isset($_POST['modifier_Site'])) {
    extract($_POST);

    $Y_updateSite = "UPDATE sites 
                     SET localisationSite = ?, 
                         superficieCourranteSite = ?, 
                         prix_Vente = ?, 
                         statut = ?
                     WHERE numeroTitreFoncier = ?";
    $tableauValeurs = [$localisation, $superficie, $prixMoyen, $statutSite, $nomSite];

    $Y_executeUpdateSite = F_executeRequeteSql($Y_updateSite, $tableauValeurs);

    $_SESSION['message'] = "Site mis à jour avec succès.";
    header('Location:'.contructUrl('Y_site' , ['H_idEmploye'=>$_SESSION['H_idEmploye']]));
    exit;
}

// 11. Modification d’un bloc 
if (isset($_POST['modifier_Bloc'])) {
    extract($_POST);

    $Y_updateBloc = "UPDATE blocs 
                     SET nomBloc = ?, 
                         superficieCourranteBloc = ?, 
                         statutBloc = ? 
                     WHERE idBloc = ?";
    $tableauValeurs = [$nomBloc, $superficieBloc, $statutBloc, $idBloc];

    $Y_executeUpdateBloc = F_executeRequeteSql($Y_updateBloc, $tableauValeurs);

    $_SESSION['message'] = "Bloc mis à jour avec succès.";
    header('Location:'.contructUrl('Y_site' , ['H_idEmploye'=>$_SESSION['H_idEmploye']]));
    exit;
}


require('views/sites/sitesView.php');
?>