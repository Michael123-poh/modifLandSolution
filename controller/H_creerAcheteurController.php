<?php
    session_start(); //demarrer la session
    //************* appel du fichier de connexion a la base de donnée***** */
    require_once("models/H_databaseConnection.php");
    $H_dbConnect = F_databaseConnection("localhost", "systeme_land", "root", "");
    //**********appel du fichier des fonctions creer ************ */
    $H_idEmploye = $Y_urlDecoder['H_idEmploye']; 
    $H_empAsPrivilege = 'SELECT * FROM employesprivileges wHERE idEmploye =? and idPrivilege=?';
    $H_execute_req= F_executeRequeteSql($H_empAsPrivilege,[$H_idEmploye,"PRI00004"]);

    // declaration des variables et attributs
    $H_executeGetBlocSite = F_executeRequeteSql("SELECT * FROM blocs WHERE	superficieCourranteBloc  > 0");
    $H_getPrixMetreCarre = "SELECT sites.prix_Vente FROM sites INNER JOIN blocs ON sites.numeroTitreFoncier = blocs.numeroTitreFoncier WHERE blocs.idBloc = ?";
    $H_getSupCourrante = "SELECT superficieCourranteBloc FROM blocs WHERE idBloc =?";
    $H_tableauErreurs = [];
    $H_regexTelephone = "/^(6[2]|6[5-9])([0-9]{7})/";
    //  test
    // $_SESSION['H_employeConnecte'] = 'connected';

    if(($_SESSION['H_employeConnecte']==='connected'))
    {
        if(isset($H_idEmploye) && $H_idEmploye === $_SESSION['H_idEmploye'] )
        {
            if(isset($_POST['Enregistrer'])) //si le user clique sur le btn enregistrer
            {
                extract($_POST); //extraction du contenu du tableau $_POST
                $H_tableauValeurs = array($H_nomAcheteur, $H_prenomAcheteur, $H_typeDoc, $H_numDoc,  $H_dateNais, $H_telephoneAchteur, $H_adresseAcheteur, $H_site, $H_bloc, $H_superficie, $H_prixMetreCarre, $H_commercial, $H_montantVersement, $H_notesAcheteur);
                //var_dump($_POST);
        
                    if(F_exclureChampsVide($H_tableauValeurs) == true) //verifie si tous les champs sont remplis
                    {
                        if(mb_strlen($H_nomAcheteur) >= 2)
                        {
                            if(mb_strlen($H_prenomAcheteur) >= 2)
                            {
                                if(preg_match($H_regexTelephone, $H_telephoneAchteur)) // mb_strlen($H_telephoneAchteur) >= 9 && mb_strlen($H_telephoneAchteur) <= 18
                                {
                                  if (mb_strlen($H_numDoc) == 9 || mb_strlen($H_numDoc) == 6 || mb_strlen($H_numDoc) == 12)
                                            {
                                               // var_dump($H_tableauValeurs);

                                                    $H_executeGetSupCourrante = F_executeRequeteSql($H_getSupCourrante, [$H_bloc]);
                                                    if($H_superficie <= $H_executeGetSupCourrante->superficieCourranteBloc)
                                                    {    $H_executeGetPrixMetreCarre = F_executeRequeteSql($H_getPrixMetreCarre, [$H_bloc]);
                                                         if($H_prixMetreCarre >= $H_executeGetPrixMetreCarre->prix_Vente)
                                                        {
                                                            // ---------------------------------------------------- Dans la table Acheteur ------------------------------------------
                                                            //on recupere le dernier Acheteur enregistré dans la bd
                                                            $H_resultatLastAcheteur = F_executeRequeteSql('SELECT * FROM acheteur ORDER BY idAcheteur DESC LIMIT 1');
                                                
                                                            foreach($H_resultatLastAcheteur as $H_acheteur)
                                                            {
                                                                $H_idAcheteur = $H_acheteur->idAcheteur;
                                                               
                                                            }
                                                        
                                                            //s'il n'y'a aucun Acheteur on initiale le 1er Acheteur Si oui on ajoute un nouveau Acheteur
                                                                $H_newIdAcheteur = F_genereMatricule($H_idAcheteur, 'ACH00001'); //sinon on incremente le nième Acheteur
                                                            
                                                            //recuperation de l'id de l'employe qui enregistre l' Acheteur
                                                            $H_idEmploye = $_SESSION['H_idEmploye'];

                                                            // var_dump(array($H_newIdAcheteur, $H_idEmploye, strtoupper($H_nomAcheteur)." ".strtoupper($H_prenomAcheteur), $H_adresseAcheteur, $H_telephoneAchteur, $H_numDoc, , $H_dateNais, $H_commercial, $H_notesAcheteur));
                                                            // exit;
                                                            $H_insertAcheteur = 'INSERT INTO acheteur (idAcheteur, idEmploye, nomAcheteur, adresseAcheteur, telephoneAcheteur, numeroCNI, dateNaisAcheteur, nomCommercial, notesAcheteur, dateCreateAcheteur) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())';
                                                            $H_tableauParametres = [$H_newIdAcheteur, $H_idEmploye, strtoupper($H_nomAcheteur)." ".strtoupper($H_prenomAcheteur), $H_adresseAcheteur, $H_telephoneAchteur, $H_numDoc, $H_dateNais, $H_commercial, $H_notesAcheteur];
                                                            $H_executeInsertAcheteur = F_executeRequeteSql($H_insertAcheteur, $H_tableauParametres); //ajoute le nouveau Acheteur pour la descente
                                                            $H_tableauErreurs[] = 'Nouvel Acheteur enregistré avec success!!!';
    
                                                            // ---------------------------------------------------- Dans la table selection ------------------------------------------
                                                            //on recupere la dernier Selection enregistré dans la bd
                                                            $H_resultatLastSelection = F_executeRequeteSql('SELECT idSelection FROM selection ORDER BY idSelection DESC LIMIT 1');
                                                            foreach($H_resultatLastSelection as $H_Selection)
                                                            {
                                                                $H_idSelection = $H_Selection->idSelection;
                                                            }
                                                            //s'il n'y'a aucune selection on initiale la 1ere selection
                                                            $H_newIdSelection = F_genereMatricule($H_idSelection, 'SEL00001'); //sinon on incremente le nième Acheteur
                                                          
                                                            //recuperation du solde courant pour l'incrémenter
                                                            $H_montantTotalSelection = $H_superficie*$H_prixMetreCarre;
                                                            $H_insertSelection = 'INSERT INTO selection(idSelection, lot, superficieSelection, montantParMetre, montantTotalSelection, idBloc, idEmploye, idAcheteur, dateCreateSelection) VALUES(?, ?, ?, ?, ?, ?, ?, ?, NOW())';
                                                            $H_tableauParametres = [$H_newIdSelection, $H_site.' '.$H_bloc, $H_superficie, $H_prixMetreCarre, $H_montantTotalSelection, $H_bloc, $H_idEmploye, $H_newIdAcheteur];
                                                            $H_executeInsertSelection = F_executeRequeteSql($H_insertSelection, $H_tableauParametres); //ajoute la nouvelle Selection pour la descente
    
                                                            // ---------------------------------------------------- Dans la table Versements ------------------------------------------
                                                            //on recupere le dernier versement enregistré dans la bd
                                                            $H_lastVersement = 'SELECT idVersement FROM versements ORDER BY idVersement DESC LIMIT 1';
                                                            $H_resultatLastVersement = F_executeRequeteSql($H_lastVersement);
                                                            foreach($H_resultatLastVersement as $H_versement)
                                                            {
                                                                $H_idVersement = $H_versement->idVersement;
                                                            }
                                                            //s'il n'y'a aucun versement on initiale le 1er versement
                                                                $H_newIdVersement = F_genereMatricule($H_idVersement, 'VER00001'); //sinon on incremente le nième Acheteur
                                                            // else
                                                            //     $H_newIdVersement = 'VER00001';
                                                                        
                                                            //recuperation du solde courant pour l'incrémenter
    
                                                            $H_insertVersement = 'INSERT INTO versements(idVersement, fraisOuvertureDossier, montantVersement, idSelection, idAcheteur, dateVersement, dateCreateVersement) VALUES(?, ?, ?, ?, ?, NOW(), NOW())';
                                                            $H_tableauParametres = [$H_newIdVersement, $H_montantVersement, $H_montantVersement, $H_newIdSelection, $H_newIdAcheteur];
                                                            $H_executeInsertVersement = F_executeRequeteSql($H_insertVersement, $H_tableauParametres); //ajoute le nouveau Versement pour la descente
                                                            
                                                            // ---------------------------------------------------- Dans la table transaction ------------------------------------------
                                                            //on recupere la dernier transaction enregistré dans la bd
                                                            $H_resultatLastTransaction = F_executeRequeteSql('SELECT idTransaction FROM transactions ORDER BY idTransaction DESC LIMIT 1');
                                                            foreach($H_resultatLastTransaction as $H_transaction)
                                                            {
                                                                $H_idTransaction = $H_transaction->idTransaction;
                                                            }
                                                            //s'il n'y'a aucun Acheteur on initiale le 1er Acheteur
                                                            $H_newIdTransaction = F_genereMatricule($H_idTransaction, 'TSN00001'); //sinon on incremente le nième Acheteur
                                                 
                                                                        
                                                            $H_insertTransaction = 'INSERT INTO transactions(idTransaction, montantTransaction, idEmploye, idVersement, dateTransaction, dateCreateTransaction) VALUES(?, ?, ?, ?, NOW(), NOW())';
                                                            $H_tableauParametres = [$H_newIdTransaction, $H_montantVersement, $H_idEmploye, $H_newIdVersement];
                                                            $H_executeInsertTransaction = F_executeRequeteSql($H_insertTransaction, $H_tableauParametres); //ajoute la nouvelle transaction pour la descente
                                                            
                                                            // ---------------------------------------------------- Dans la table Dossier ------------------------------------------
                                                            //on recupere la dernier Dossier enregistré dans la bd
                                                            $H_resultatLastDossier = F_executeRequeteSql('SELECT idDossier FROM dossiers ORDER BY idDossier DESC LIMIT 1');
                                                            foreach($H_resultatLastDossier as $H_Dossier)
                                                            {
                                                                $H_idDossier = $H_Dossier->idDossier;
                                                            }
                                                            //s'il n'y'a aucun Acheteur on initiale le 1er Acheteur
                                                                $H_newIdDossier = F_genereMatricule($H_idDossier, 'DOS00001'); //sinon on incremente le nième Acheteur
                                                            // else
                                                            //     $H_newIdDossier = 'DOS00001';
                                                                        
                                                            $H_insertDossier = 'INSERT INTO dossiers(idDossier, idEmploye, idAcheteur, dateCreateDossier) VALUES(?, ?, ?, NOW())';
                                                            $H_tableauParametres = [$H_newIdDossier, $H_idEmploye, $H_newIdAcheteur];
                                                            $H_executeInsertDossier = F_executeRequeteSql($H_insertDossier, $H_tableauParametres); //ajoute la nouvelle Dossier pour la descente
                                                            
                                                            //-------------------------------------- mettre à jour la superficie du bloc et du site-------------------------------------------------------------
                                                            $H_updateBloc = "UPDATE blocs SET superficieCourranteBloc = superficieCourranteBloc-? WHERE idBloc = ?";
                                                            $H_executeUpdateBloc = F_executeRequeteSql($H_updateBloc, [$H_superficie, $H_bloc]);
    
                                                            $H_updateSite = "UPDATE blocs, sites SET superficieCourranteSite  = superficieCourranteSite -? WHERE blocs.numeroTitreFoncier = sites.numeroTitreFoncier AND blocs.idBloc = ?";
                                                            $H_executeUpdateSite = F_executeRequeteSql($H_updateSite, [$H_superficie, $H_bloc]);
                                                            header('Location:'.contructUrl('Y_acheteur' , ['H_idEmploye'=>$_SESSION['H_idEmploye']]));
                                                        
                                                        }
                                                        else
                                                        {
                                                            $H_tableauErreurs[] = 'Le prix du mètre carré est inferieur au seuil de la categorie de ce site';
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $H_tableauErreurs[] = 'La superficie est superieure à la superficie disponible!';
                                                    }
                                            
                                            }
                                            else
                                            {
                                                $H_tableauErreurs[] = 'Numero de CNI ou de passport Invalide!';
                                            }
                                   
                                }
                                else
                                {
                                    $H_tableauErreurs[] = 'Le numero de telephone est incorrect!';
                                }
                            }
                            else
                            {
                                $H_tableauErreurs[] = 'Le prenom est trop court!';
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
        }
    }
    else
        //var_dump($_SESSION['H_employeConnecte']);
        header('Location:index.php');
  
    
    require('controller/Y_acheteurController.php');
?>