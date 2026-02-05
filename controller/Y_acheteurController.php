<?php
session_start();
$currentPage = basename($_SERVER['PHP_SELF']); 

// 1. Connexion à la BD
require_once('models/H_databaseConnection.php');
$H_dbConnect = F_databaseConnection("localhost", "systeme_land", "root", "");

$Y_idEmployes = $Y_urlDecoder['H_idEmploye']; 

// 2. Sélection de TOUS les acheteurs et leurs sélections
// Assurez-vous que cette requête retourne TOUS les enregistrements, sans LIMIT/OFFSET.
$Y_executeAcheteurs = F_executeRequeteSql("
    SELECT * 
    FROM acheteur 
    INNER JOIN selection ON acheteur.idAcheteur = selection.idAcheteur AND selection.statutSelection='actif'
    INNER JOIN blocs ON selection.idBloc = blocs.idBloc 
    INNER JOIN sites ON blocs.numeroTitreFoncier = sites.numeroTitreFoncier 
    ORDER BY acheteur.dateCreateAcheteur DESC
");

// Ajoutez un check pour s'assurer que les données sont bien un tableau (ou un objet traversable)
if (!is_array($Y_executeAcheteurs) && !($Y_executeAcheteurs instanceof Traversable)) {
    $Y_executeAcheteurs = []; // Initialisez comme un tableau vide pour éviter les erreurs si la requête échoue
}
// Convertissez TOUS les acheteurs en JSON pour le JavaScript
$json_items = json_encode($Y_executeAcheteurs);

// 3. Sélection des autres données (blocs, sites, employés)
$H_executeBloc = F_executeRequeteSql("SELECT * FROM blocs ");
$H_executeSites = F_executeRequeteSql("SELECT * FROM sites");
$H_executeEmployes = F_executeRequeteSql("SELECT nomEmploye FROM employe");


// 4. Inclusion de la vue
require('views/acheteur/acheteurView.php');

?>