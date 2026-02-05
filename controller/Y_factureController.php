<?php
session_start();
$currentPage = basename($_SERVER['PHP_SELF']); 

// 1. Connexion à la BD (comme dans votre exemple)
require_once('models/H_databaseConnection.php');
$H_dbConnect = F_databaseConnection("localhost", "systeme_land", "root", "");

// 

?>