<?php
    session_start();
    $currentPage = basename($_SERVER['PHP_SELF']); 
    // 1. Connexion à la BD
    require_once('models/H_databaseConnection.php');
    $H_dbConnect = F_databaseConnection("localhost", "systeme_land", "root", "");

    // Appel du fichier des fonctions du modèle
    // require("models/H_functionsModels.php");

    // Inclure la bibliothèque FPDF
    require('models/pdf/fpdf.php');

    $H_idEmploye = $Y_urlDecoder['H_idEmploye'];

    // Vérifier la connexion de l'employé
    if (!isset($_SESSION['H_employeConnecte']) || $_SESSION['H_employeConnecte'] !== 'connected' || $H_idEmploye !== $_SESSION['H_idEmploye']) {
        header('Location: index.php');
        exit();
    }
    if (isset($_POST['exportPdfEmployes'])) 
    {

        // Classe PDF personnalisée pour inclure le filigrane(le logo)
        class PDF extends FPDF
        {
            // Ajout du filigrane
            function Header()
            {
                // Chemin vers le logo de l' entreprise
                $logoPath = 'images/logoWeldone.png'; 
                
                // Obtenir les dimensions de la page
                $pageWidth = $this->GetPageWidth();
                $pageHeight = $this->GetPageHeight();

                // Positionner et insérer l'image en filigrane
                $this->Image($logoPath, ($pageWidth - 100) / 2, ($pageHeight - 100) / 2, 100, 100);
            }
        }

        // Création d'une nouvelle instance de PDF
        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetFont('times', '', 12);

        // Récupérer la date du jour
        $date = date('d/m/Y');
        $pdf->Cell(0, 10, 'Date: ' . $date, 0, 1, 'L');

        // Espacement après la date
        $pdf->Ln(10);

        // Titre du document
        $pdf->SetFont('times', 'B', 16);
        $pdf->Cell(0, 10, 'LISTE DES EMPLOYES', 0, 1, 'C');

        // Espacement après le titre
        $pdf->Ln(10);

        // Définir les en-têtes du tableau
        $header = ['Numero', 'Noms et prenoms', 'Poste', 'Telephone', 'Email'];
        // Définir les largeurs des colonnes (en mm)
        $width_cell = [15, 65, 35, 25, 50]; 

        // Dessiner le tableau
        $pdf->SetFont('times', 'B', 10);
        for ($i = 0; $i < count($header); $i++) {
            $pdf->Cell($width_cell[$i], 7, $header[$i], 1, 0, 'C');
        }
        $pdf->Ln();

        // Récupération des données depuis la base de données
        $H_allEmployes =F_executeRequeteSql("SELECT employe.*, typeemploye.libelleFonction FROM employe INNER JOIN typeemploye ON employe.idTypeEmploye = typeemploye.idTypeEmploye ORDER BY employe.nomEmploye");
       

        // Remplir le tableau avec les données
        $pdf->SetFont('times', '', 10);
        $counter = 1;
        foreach ($H_allEmployes as $row) {
            $pdf->Cell($width_cell[0], 6, $counter++, 1, 0, 'C');
            $pdf->Cell($width_cell[1], 6, utf8_decode($row->nomEmploye), 1, 0, 'L');
            $pdf->Cell($width_cell[2], 6, utf8_decode($row->libelleFonction), 1, 0, 'C');
            $pdf->Cell($width_cell[3], 6, $row->telephoneEmploye, 1, 0, 'C');
            $pdf->Cell($width_cell[4], 6, $row->emailEmploye, 1, 0, 'C');
            $pdf->Ln();
        }

        // Envoyer le PDF au navigateur
        $pdf->Output('I', 'liste_employes.pdf');

        exit();
    } 
?>