<?php
session_start();
$currentPage = basename($_SERVER['PHP_SELF']); 

// 1. Connexion à la BD
require_once('models/H_databaseConnection.php');
$H_dbConnect = F_databaseConnection("localhost", "systeme_land", "root", "");
// Appel du fichier des fonctions du modèle
  //  require("models/H_functionsModels.php");

    $H_idEmploye = $Y_urlDecoder['H_idEmploye']; 
    // var_dump($_SESSION['H_employeConnecte']);
    $H_empAsPrivilege = 'SELECT * FROM employesprivileges wHERE idEmploye =? and idPrivilege=?';
    $H_execute_req= F_executeRequeteSql($H_empAsPrivilege,[$H_idEmploye,"PRI00002"]);

    // ================ recuperer les prospects de la bd ===============
    // Sélection de TOUS les prospects avec leurs informations 
$H_executeProspects = F_executeRequeteSql("SELECT * FROM prospects");

// Ajoutez un check pour s'assurer que les données sont bien un tableau (ou un objet traversable)
if (!is_array($H_executeProspects) && !($H_executeProspects instanceof Traversable)) {
    $H_executeProspects = []; // Initialisez comme un tableau vide pour éviter les erreurs si la requête échoue
}

// Convertissez TOUS les prospects en JSON pour le JavaScript
$items_json_prospects = json_encode($H_executeProspects);

// ================ recuperer les descentes de la bd ===============
// Sélection de TOUS les descentes avec leurs informations
$listeDescentes = F_executeRequeteSql("SELECT * FROM descentes ORDER BY dateDescente DESC");
// Convertissez TOUS les descentes en JSON pour le JavaScript
$items_json_descentes = json_encode($listeDescentes);

    // declaration des variables et attributs
    $H_tableauErreurs = [];
    $H_regexTelephone = "/^(6[2]|6[5-9])([0-9]{7})/";

    if(($_SESSION['H_employeConnecte']==='connected'))
    {
        if(isset($H_idEmploye) && $H_idEmploye === $_SESSION['H_idEmploye'] )
        {
            if(isset($_POST['addProspect'])) //si le user clique sur le btn enregistrer
            {
                extract($_POST); //extraction du contenu du tableau $_POST
                $H_tableauValeurs = array($H_nomProspect, $H_telephoneProspect, $H_lieuProspection, $H_statutProspect);
                //var_dump($_POST);
                // $H_emailProspect;
                    if(F_exclureChampsVide($H_tableauValeurs) == true) //verifie si tous les champs sont remplis
                    {
                        if(mb_strlen($H_nomProspect) >= 2)
                        {
                            if(preg_match($H_regexTelephone, $H_telephoneProspect)) // mb_strlen($H_telephoneProspect) >= 9 && mb_strlen($H_telephoneProspect) <= 18
                                {

                                    // ---------------------------------------------------- Dans la table Employe ------------------------------------------
                                    //on recupere le dernier prospect enregistré dans la bd
                                    $H_resultatLastProspect = F_executeRequeteSql('SELECT * FROM prospects ORDER BY idProspect DESC LIMIT 1');
                                    foreach($H_resultatLastProspect as $H_prospect)
                                        {
                                            $H_idProspect = $H_prospect->idProspect;
                                        }
                                                        
                                     //s'il n'y'a aucun Employe on initiale le 1er Employe Si oui on ajoute un nouveau Prospect
                                    $H_newIdProspect = F_genereMatricule($H_idProspect, 'PRT00001'); //sinon on incremente le nième Employe
                                                            
                                    //recuperation de l'id de l'Employe qui enregistre l' Employe
                                    $H_idEmploye = $_SESSION['H_idEmploye'];

                                    $H_insertProspect = 'INSERT INTO prospects (idProspect, idEmploye, nomProspect, lieuProspection, telephoneProspect, statutProspect, dateCreateProspect) VALUES(?, ?, ?, ?, ?, ?, NOW())';
                                    $H_tableauParametres = [$H_newIdProspect, $H_idEmploye, strtoupper($H_nomProspect), $H_lieuProspection, $H_telephoneProspect, $H_statutProspect];
                                    $H_executeInsertProspect = F_executeRequeteSql($H_insertProspect, $H_tableauParametres); //ajoute le nouveau Employe pour la descente
                                    
                                    if($H_notesProspect !== null)
                                    {
                                        $H_insertNotesProspect = 'UPDATE prospects SET notesProspect = ? WHERE idProspect = ?';
                                        $H_executeInsertNotesProspect = F_executeRequeteSql($H_insertNotesProspect, [$H_notesProspect, $H_newIdProspect]);
                                        
                                    }

                                    $H_tableauErreurs[] = 'Nouvel Employe enregistré avec success!!!';
    
                                    header('Location:'.contructUrl('H_prospect' , ['H_idEmploye'=>$_SESSION['H_idEmploye']]));
                                                        

                                            
                                   
                                }
                                else
                                {
                                    $H_tableauErreurs[] = 'Le numero de telephone est incorrect!';
                                }
                            
                        }
                        else
                        {
                            $H_tableauErreurs[] = 'Le nom est trop court!';
                        }
                    }
                    else
                    {
                        $H_tableauErreurs[] = 'Veuillez remplir tous les champs!';
                    }
                    $H_afficheErreur = F_flashErrors($H_tableauErreurs);
            }

// ============================================================================ ajout d'une descente =========================================================
             if(isset($_POST['addDescente'])) //si le user clique sur le btn enregistrer
            {
                extract($_POST); //extraction du contenu du tableau $_POST
                $H_tableauValeurs = array($H_nomDescente, $H_dateDescente, $H_nbrePersonne);
                    if(F_exclureChampsVide($H_tableauValeurs) == true) //verifie si tous les champs sont remplis
                    {
                        // ---------------------------------------------------- Dans la table Employe ------------------------------------------
                        //on recupere la derniere descente enregistrée dans la bd
                                    $H_resultatLastDescente = F_executeRequeteSql('SELECT * FROM descentes ORDER BY idDescente DESC LIMIT 1');
                                    foreach($H_resultatLastDescente as $H_descente)
                                    {
                                        $H_idDescente = $H_descente->idDescente;
                                    }
                                                        
                                     //s'il n'y'a aucun Employe on initiale le 1er Employe Si oui on ajoute un nouveau Prospect
                                    $H_newIdDescente = F_genereMatricule($H_idDescente, 'DSC00001'); //sinon on incremente le nième Employe
                                                            
                                    //recuperation de l'id de l'Employe qui enregistre l' Employe
                                    $H_idEmploye = $_SESSION['H_idEmploye'];

                                    $H_insertDescente = 'INSERT INTO descentes (idDescente, nomDescente, nbrePersonnePresente, dateDescente,  idEmploye, dateCreateDescente) VALUES(?, ?, ?, ?, ?, NOW())';
                                    $H_tableauParametres = [$H_newIdDescente,  $H_nomDescente, $H_nbrePersonne, $H_dateDescente, $H_idEmploye];
                                    $H_executeInsertProspect = F_executeRequeteSql($H_insertDescente, $H_tableauParametres); //ajoute le nouvelle la descente
                                    //lieuDescente,descriptionDescente
                                     if($H_lieuDescente !== null)
                                    {
                                            $H_insertlieuDescente = 'UPDATE descentes SET lieuDescente = ? WHERE idDescente = ?';
                                            $H_executeInsertlieuDescente = F_executeRequeteSql($H_insertlieuDescente, [$H_lieuDescente, $H_newIdDescente]);
                                    }
                                    if($H_descriptionDescente !== null)
                                    {
                                        $H_insertNotesDescente = 'UPDATE descentes SET descriptionDescente = ? WHERE idDescente = ?';
                                        $H_executeInsertNotesDescente = F_executeRequeteSql($H_insertNotesDescente, [$H_descriptionDescente, $H_newIdDescente]);
                                        
                                    }

                                    $H_tableauErreurs[] = 'Nouvel Employe enregistré avec success!!!';
    
                                    header('Location:'.contructUrl('H_prospect' , ['H_idEmploye'=>$_SESSION['H_idEmploye']]));
                                                        
                    }
                    else
                    {
                        $H_tableauErreurs[] = 'Veuillez remplir tous les champs!';
                    }
                    $H_afficheErreur = F_flashErrors($H_tableauErreurs);
            }


// ============================================================================ ajout d'un client pour une descente =========================================================
             if(isset($_POST['addCltDescente'])) //si le user clique sur le btn enregistrer
            {
                extract($_POST); //extraction du contenu du tableau $_POST
                $H_tableauValeurs = array($H_nomClient, $H_telephoneClient, $H_montantDescente);
                    if(F_exclureChampsVide($H_tableauValeurs) == true) //verifie si tous les champs sont remplis
                    {
                        // ---------------------------------------------------- Dans la table Employe ------------------------------------------
                        //on recupere la derniere descente enregistrée dans la bd
                                    $H_resultatLastClient = F_executeRequeteSql('SELECT * FROM clientsdescente ORDER BY idClient DESC LIMIT 1');
                                    foreach($H_resultatLastClient as $H_client)
                                    {
                                        $H_idClient = $H_client->idClient;
                                    }
                                                        
                                     //s'il n'y'a aucun Employe on initiale le 1er Employe Si oui on ajoute un nouveau Prospect
                                    $H_newIdClient= F_genereMatricule($H_idClient, 'CLT00001'); //sinon on incremente le nième Employe
                                                            
                                    //recuperation de l'id de l'Employe qui enregistre l' Employe
                                    $H_idEmploye = $_SESSION['H_idEmploye'];

                                    $H_insertClientDescente = 'INSERT INTO clientsdescente (idClient, nomClient, telephoneClient, montantDescente, idEmploye) VALUES(?, ?, ?, ?, ?)';
                                    $H_tableauParametres = [$H_newIdClient,  $H_nomClient, $H_telephoneClient, $H_montantDescente, $H_idEmploye];
                                    $H_executeInsertClientDescente = F_executeRequeteSql($H_insertClientDescente, $H_tableauParametres); //ajoute le nouvelle la descente
                                    
                                    $H_tableauErreurs[] = 'Nouveau client enregistré avec success!!!';
    
                                    header('Location:'.contructUrl('H_prospect' , ['H_idEmploye'=>$_SESSION['H_idEmploye']]));
                                                        
                    }
                    else
                    {
                        $H_tableauErreurs[] = 'Veuillez remplir tous les champs!';
                    }
                    $H_afficheErreur = F_flashErrors($H_tableauErreurs);
            }
        }
        else
            header('Location:index.php');
    }


// 4. Inclusion de la vue
require('views/prospects/prospectionView.php');

?>