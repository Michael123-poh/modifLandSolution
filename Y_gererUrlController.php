<?php

// 1. Connexion à la BD (comme dans votre exemple)
require_once('models/H_databaseConnection.php');
$H_dbConnect = F_databaseConnection("localhost", "systeme_land", "root", "");

//**********appel du fichier des fonctions creer ************ */
require("models/H_functionsModels.php");

// 2. Recuperer L'url apres le nom des domaine et prendre la derniere partie
$Y_url = trim($_SERVER['REQUEST_URI'], '/');

$segments = explode('/', $Y_url);

// 3. Decoder l'URL et charger la page correspondante
if (count($segments) >= 3) {

    array_shift($segments); // supprime land_solution
    $page = $segments[0];       // nom du contrôleur
    $encodedParams = $segments[1] ?? '';  // paramètres encodés

    $params = [];
    if ($encodedParams)
        // Fonction pour décoder l'URL
        $Y_urlDecoder = decodeUrl($encodedParams);

        $Y_pageController = $page . 'Controller.php';

        if(file_exists("controller/$Y_pageController")){
            require("controller/$Y_pageController");
        }else{
            echo "Page Introuvable";
        }

}
else if (count($segments) <= 1 || $segments[0] === 'land_solution') {
    // Page d'accueil ou redirection par défaut
   // echo "Bienvenue sur land_solution! Veuillez sélectionner une page.";
    require("index.php");

} else {
    echo "URL invalide.";
}
?>