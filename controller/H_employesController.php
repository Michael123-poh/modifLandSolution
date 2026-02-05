<?php
session_start();
$currentPage = basename($_SERVER['PHP_SELF']); 

// 1. Connexion à la BD
require_once('models/H_databaseConnection.php');
$H_dbConnect = F_databaseConnection("localhost", "systeme_land", "root", "");
$Y_idEmployes = $Y_urlDecoder['H_idEmploye'];
$_SESSION['H_idEmploye'] = $Y_idEmployes;

// Sélection de TOUS les employés avec leurs informations de poste
$H_executeEmployes = F_executeRequeteSql("SELECT employe.*, typeemploye.libelleFonction FROM employe INNER JOIN typeemploye ON employe.idTypeEmploye = typeemploye.idTypeEmploye ORDER BY employe.dateCreateEmploye DESC");

// Ajoutez un check pour s'assurer que les données sont bien un tableau (ou un objet traversable)
if (!is_array($H_executeEmployes) && !($H_executeEmployes instanceof Traversable)) {
    $H_executeEmployes = []; // Initialisez comme un tableau vide pour éviter les erreurs si la requête échoue
}

// Convertissez TOUS les acheteurs en JSON pour le JavaScript
$items_json = json_encode($H_executeEmployes);

//recuperation de tous les types d'employés dans la base de données
$H_executeTypeEmployes = F_executeRequeteSql("SELECT * FROM typeemploye");

// if(isset($_GET[$Y_urlDecoder['H_idEmployeUpdate']])) {
//     $H_idEmployeUpdate = $Y_urlDecoder['H_idEmployeUpdate'];
//     $H_idEmployeConnected = $Y_urlDecoder['H_idEmploye']; 
//     //recuperation des informations enregistrees de l'Employe
//     $H_executeGetInfoEmploye = F_executeRequeteSql("SELECT * FROM employe WHERE idEmploye = ?", [$H_idEmployeUpdate]);
//     $H_executeGetPosteEmploye = F_executeRequeteSql("SELECT typeemploye.libelleFonction FROM typeemploye INNER JOIN employe USING(idTypeEmploye) WHERE idEmploye = ?", [$H_idEmployeUpdate]);
   
// } 
// 4. Inclusion de la vue
require('views/employes/employesView.php');

?>