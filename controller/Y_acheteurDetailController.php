<?php
session_start();
$currentPage = basename($_SERVER['PHP_SELF']); 

// 1. Connexion à la BD (comme dans votre exemple)
require_once('models/H_databaseConnection.php');
$H_dbConnect = F_databaseConnection("localhost", "systeme_land", "root", "");

// Changement principale sur les liens et variables recu par l'url(ce qui suit)
// Revue de l'insertion des des versements et l'ajout du champ modePaiement dans la table transactions
// Recuperer l'URL décodée
$Y_idEmployes = $Y_urlDecoder['H_idEmploye']; 
$Y_idAcheteur = $Y_urlDecoder['Y_idAcheteur'];

$voirPiece = $Y_urlDecoder['voirPiece'] ?? null;



// 2. Récupération de l'ID de l'acheteur depuis la barre de navigation
$Y_idEmployes =  $Y_urlDecoder['H_idEmploye']; 

// 2. Selection des details des acheteurs
$Y_executeAcheteurDetail = F_executeRequeteSql("SELECT * FROM dossiers INNER JOIN acheteur ON dossiers.idAcheteur = acheteur.idAcheteur INNER JOIN selection ON acheteur.idAcheteur = selection.idAcheteur INNER JOIN blocs ON selection.idBloc = blocs.idBloc INNER JOIN sites ON blocs.numeroTitreFoncier = sites.numeroTitreFoncier WHERE selection.statutSelection='actif' AND acheteur.idAcheteur = ?", [$Y_idAcheteur]);
$dateNaisssance = date('d/m/Y', strtotime($Y_executeAcheteurDetail->dateNaisAcheteur));

if (is_array($Y_executeAcheteurDetail) && !empty($Y_executeAcheteurDetail)) {
    $Y_executeAcheteurDetail = $Y_executeAcheteurDetail[0];
}


//var_dump($Y_idAcheteur);
// 3. Afficher l'historique de transaction de l'acheteur
$Y_executeHistoriqueTransaction = F_executeRequeteSql('SELECT * FROM transactions INNER JOIN versements ON transactions.idVersement = versements.idVersement WHERE idAcheteur = ?', [$Y_idAcheteur]);


// 4. Afficher le montant Total a payer, le montant verse, le montant restant
$Y_executeMontantTotal = F_executeRequeteSql('SELECT montantTotalSelection, montantVersement, idVersement FROM selection INNER JOIN versements ON selection.idSelection = versements.idSelection WHERE versements.idAcheteur = ?', [$Y_idAcheteur]);

$idVersement = $Y_executeMontantTotal->idVersement ?? null;

// 5. Calcule du pourcentage de paiement
$total = $Y_executeMontantTotal->montantTotalSelection ?? 0;
$paye = $Y_executeMontantTotal->montantVersement ?? 0;
$pourcentage = $total > 0 ? round(($paye / $total) * 100) : 0;

// 6. Enregistrer un nouveau paiement
if (isset($_POST['Enregistrer'])){
    $_SESSION['H_idAcheteur'] = $Y_idAcheteur;
    extract($_POST);

    // Verifier si le montant Total est egale au montant verse
    if (($paye + $montant) < $total) {
        // Reecuperer le dernier ID de transaction
        $Y_executeGetLastIdTransaction = F_executeRequeteSql('SELECT MAX(idTransaction) AS lastId FROM transactions');

        foreach ($Y_executeGetLastIdTransaction as $lastId) {
            $dernierIdTransaction = $lastId->lastId;
        }
        
        // Generer un nouvel ID de transaction
        $idTransaction = F_genereMatricule($dernierIdTransaction, 'TRS00001');

        // Insertion du paiement dans la base de données
        $Y_executeInsertPaiement = F_executeRequeteSql('INSERT INTO transactions (idTransaction, montantTransaction, modePaiement, dateTransaction, dateCreateTransaction, idVersement, idEmploye) VALUES (?, ?, ?, ?, NOW(), ?, ?)', 
        [$idTransaction, $montant, $modePaiement, $dateVersement, $idVersement, $Y_idEmployes]);

        // Mettre à jour le montant du versement
        $Y_executeUpdateVersement = F_executeRequeteSql('UPDATE versements SET montantVersement = montantVersement + ? WHERE idVersement = ?', 
        [$montant, $idVersement]);

        // Redirection vers la page de détails de l'acheteur
        header('Location:'.contructUrl('Y_acheteurDetail' , ['H_idEmploye'=>$_SESSION['H_idEmploye'], 'Y_idAcheteur'=>$Y_idAcheteur]));
        exit;
    } else {
        $errorMessage = "Somme Total Atteint";
    }
}

// 7. Upload de la pièce d'identité
if (isset($_POST['telecharger'])) {
    extract($_POST);

    // Declaration des variables
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["photo"]["name"];
        $filetype = $_FILES["photo"]["type"];
        $filesize = $_FILES["photo"]["size"];        

        // Vérification de l'extension du fichier
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) {
            die("Erreur : Veuillez sélectionner un format de fichier valide.");
        }

        // Vérification de la taille du fichier - 5Mo maximum
        $maxsize = 5 * 1024 * 1024; // 5 Mo
        if ($filesize > $maxsize) {
            die("Erreur : La taille du fichier est supérieure à la limite autorisée.");
        }

        // Vérification du type MIME du fichier
        if (in_array($filetype, $allowed)) {
            $newFilename = $telecharger . '.' . $ext;

            // Vérification si le fichier existe déjà
            if(file_exists("/images" . $newFilename)) {
                move_uploaded_file($_FILES["photo"]["tmp_name"], "images/" . $newFilename);
                $cheminPieceIdentite = "images/".$newFilename;
            } else {
                // Déplacement du fichier vers le dossier images
                move_uploaded_file($_FILES["photo"]["tmp_name"], "images/" . $newFilename);
                echo "Votre fichier a été téléchargé avec succès.".' noms:'.$newFilename;
                    // Insertion dans la base de données
                    // $Y_executeInsertPieceIdentite = F_executeRequeteSql('INSERT INTO piece_identite (idAcheteur, nomPieceIdentite, typePieceIdentite) VALUES (?, ?, ?)', 
                    // [$Y_idAcheteur, $newFilename, $typePieceIdentite]);
                $cheminPieceIdentite = "images/".$newFilename;
            }
        }
    }else {
       echo "Erreur : " . $_FILES["photo"]["error"];
    }
    // Redirection vers la page de détails de l'acheteur
    header('Location:'.contructUrl('Y_acheteurDetail' , ['H_idEmploye'=>$_SESSION['H_idEmploye'], 'Y_idAcheteur'=>$Y_idAcheteur]));
    exit;
}

// 8. Afficher la pièce d'identité
if (isset($_POST['numeroCNI'])) {
    extract($_POST);
    
    // stocker le chemein de la pièce d'identité dans une variable
    $_SESSION['cheminPieceIdentite'] = "../images/".$numeroCNI .".png";
    // header("Location: Y_acheteurDetailController.php?H_idEmploye=".$_SESSION['H_idEmploye']."&Y_idAcheteur=".$Y_idAcheteur."&voirPiece=1");
    header('Location:'.contructUrl('Y_acheteurDetail' , ['H_idEmploye'=>$_SESSION['H_idEmploye'], 'Y_idAcheteur'=>$Y_idAcheteur, 'voirPiece'=>1]));
    exit;

}

// 9. Recuperer ce chemin Vers l'Image de la pièce d'identité dans la session
if (isset($voirPiece) && isset($_SESSION['cheminPieceIdentite'])) {
    $cheminPieceIdentite = $_SESSION['cheminPieceIdentite'];
}

// 10. Recasement
if (isset($_POST['recaser'])) {
    $nouvelleSuperficie = $_POST['superficieRecasement'] ?? 0;
    $nouveauBloc       = $_POST['blocRecasement'] ?? null;

    if ($nouveauBloc && $nouvelleSuperficie > 0) {

        // Récupérer l’ancienne sélection
        $ancienneSelection = F_executeRequeteSql("SELECT * FROM selection WHERE idAcheteur = ? AND statutSelection='actif'", [$Y_idAcheteur]);
        if (is_array($ancienneSelection) && !empty($ancienneSelection)) {
            $ancienneSelection = $ancienneSelection[0];
        }

        // Archiver l’ancienne sélection
        if ($ancienneSelection) {
            F_executeRequeteSql(
                "UPDATE selection SET statutSelection='archive', dateMiseAJour=NOW() WHERE idSelection=?",
                [$ancienneSelection->idSelection]
            );
        }

        // Générer un nouvel ID pour la nouvelle sélection
        $lastSelection = F_executeRequeteSql("SELECT idSelection FROM selection ORDER BY idSelection DESC LIMIT 1");
        $dernierIdSel = (is_array($lastSelection) && !empty($lastSelection)) ? $lastSelection[0]->idSelection : null;
        $newSelectionId = F_genereMatricule($dernierIdSel, "SEL00001");

        // Créer la nouvelle sélection
        $montantParMetre = $Y_executeAcheteurDetail->montantParMetre ?? 0;
        $montantTotal = $nouvelleSuperficie * $montantParMetre;

        F_executeRequeteSql(
            "INSERT INTO selection (idSelection, idAcheteur, idBloc, superficieSelection, montantParMetre, montantTotalSelection, idEmploye, dateCreateSelection, statutSelection)
             VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 'actif')",
            [$newSelectionId, $Y_idAcheteur, $nouveauBloc, $nouvelleSuperficie, $montantParMetre, $montantTotal, $_SESSION['H_idEmploye']]
        );

        // Réassocier le versement existant à la nouvelle sélection
        $idVersement = $Y_executeMontantTotal->idVersement ?? null;
        if ($idVersement) {
            F_executeRequeteSql("UPDATE versements SET idSelection = ? WHERE idVersement = ?", [$newSelectionId, $idVersement]);
        }

        // Redirection vers la page détails
        header('Location:' . contructUrl('Y_acheteurDetail', [
            'H_idEmploye' => $_SESSION['H_idEmploye'],
            'Y_idAcheteur' => $Y_idAcheteur
        ]));
        exit;
    }
}


// 11. Facturation et versement
if (isset($_POST['facture'])) {
    extract($_POST);

    // Récupérer la date de la facture
    $voirTout = F_executeRequeteSql("SELECT dateCreateTransaction FROM transactions WHERE transactions.idTransaction = ?", [$facture]);
    // var_dump($voirTout);
    // die("ok");

    $Y_executeHistoriqueTransaction2 = F_executeRequeteSql('SELECT * FROM transactions INNER JOIN versements ON transactions.idVersement = versements.idVersement WHERE idTransaction = ?', [$facture]);

    $time = $voirTout->dateCreateTransaction;
    $timestamp = strtotime($time);

    // Faire la somme des versement du client aveant la date $time
    $SommeVersement = F_executeRequeteSql("SELECT SUM(montantTransaction) AS total FROM transactions WHERE idVersement = ? AND dateCreateTransaction <= ?", [$idVersement, $time]);
    $amounts = $SommeVersement->total;

    // Reste a payer
    if($amounts==00000)
    $reste = $Y_executeMontantTotal->montantTotalSelection ;
    else
    $reste = ($Y_executeMontantTotal->montantTotalSelection) - ($amounts);
    

    require('models/pdf/fpdf.php');
    $dimension = array(28, 21);
    $pdf = new FPDF('P','cm', $dimension);

    $pdf->AddPage();
    $pdf->Image("images/now-logo.png",1,1,4,2);
    $pdf->Image("images/now-logo.png",1,0.5,21,21);

    $pdf->SetFont('Arial','I',14);
    $pdf->Cell(16,1,'',0,0);
    $pdf->Cell(1,1,$facture,0,0);
    
    $pdf->SetFont('Arial','',10);
    $pdf->ln(4);
    $pdf->Cell(5,1,'Nom du Client',0,0);
    $pdf->Cell(6,1,':'.' '.$Y_executeAcheteurDetail->nomAcheteur,0,0);
    $pdf->Cell(3,1,'CNI',0,0);
    $pdf->Cell(3,1,':'.' '.$Y_executeAcheteurDetail->numeroCNI,0,1);

    $pdf->Cell(5,1,'A PAYER',0,0);
    $pdf->Cell(6,1,':'.' '.number_format($Y_executeAcheteurDetail->montantTotalSelection, 0, ',', ' ').' XAF',0,0);
    $pdf->Cell(3,1,'PAYE',0,0);
    $pdf->Cell(5,1,':'.' '.number_format($Y_executeHistoriqueTransaction2->montantVersement, 0, ',', ' ').' XAF',0,1);
    $pdf->Cell(5,1,'RECEPTIONER PAR',0,0);
    $pdf->Cell(6,1,': Caisse',0,0);
    $pdf->Cell(3,1,'BENEFICIARE',0,0);
    $pdf->Cell(4,1,':'.' '.$Y_executeAcheteurDetail->nomAcheteur,0,1);

  
    $pdf->Cell(5,1,'SITE',0,0);
    $pdf->Cell(6,1,':'.' '.$Y_executeAcheteurDetail->numeroTitreFoncier .' '.$Y_executeAcheteurDetail->nomBloc,0,0);
    $pdf->Cell(3,1,'DATE',0,0);
    $pdf->Cell(5,1,':'.' '.date('d F Y', $timestamp),0,1);
    $pdf->Cell(5,1,'RESTE',0,0);
    $pdf->Cell(6,1,':'.' '.number_format($reste, 0, ',', ' ').' XAF',0,0);
    $pdf->Cell(3,1,'MOTIF',0,0);
    $pdf->Cell(4,1,':'.' payee',0,1);
    $pdf->Cell(5,1,'SUPERFICIE',0,0);
    $pdf->Cell(6,1,':'.' '.number_format($Y_executeAcheteurDetail->superficieSelection, 0, ',', ' ').' m2',0,0);
    $pdf->Cell(3,1,'Tel',0,0);
    $pdf->Cell(5,1,':'.' '.$Y_executeAcheteurDetail->telephoneAcheteur,0,1);


    $pdf->SetFont('Arial','',8);
    $pdf->ln(1);
    $pdf->Cell(19,1,'Avec CB Wonder votre satisfaction nous oblige. Aucune possibilite de ',0,1,'C');
    $pdf->Cell(19,1,'remboursement mais votre recasement est assure en cas de soucis sans frais supplementaire',0,1,'C');

    $pdf->SetFont('Arial','',12);
    $pdf->ln(1);
    $pdf->Cell(5,1,'Signature Client',0,0);
    $pdf->Cell(4,1,'',0,0);
    $pdf->Cell(5,1,'',0,0);
    $pdf->Cell(5,1,'Signature Administration ',0,1);


    $pdf->Output();

    $connexion->close();

}


require('views/acheteur/acheteurDetails.php');
?>