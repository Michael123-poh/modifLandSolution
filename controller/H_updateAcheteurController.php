<?php
    session_start(); //demarrer la session
    //************* appel du fichier de connexion a la base de donnée***** */
    require_once("models/H_databaseConnection.php");
    $H_dbConnect = F_databaseConnection("localhost", "systeme_land", "root", "");

    // Recuperer l'URL décodée
    // Remplacer $_SESSION['H_idEmploye'] par $Y_idEmployes
    // et $_SESSION['Y_idAcheteur'] par $Y_idAcheteur
    $Y_idEmployes = $Y_urlDecoder['H_idEmploye']; 
    $Y_idAcheteur = $Y_urlDecoder['Y_idAcheteur'];


    //$H_empAsPrivilege = 'SELECT * FROM employesprivileges wHERE idEmploye =? and idPrivilege=?';
    //$H_execute_req= F_executeRequeteSql($H_empAsPrivilege,[$H_idEmploye,"PRI00004"]);

    //recuperation des informations enregistrees de l'acheteur
    $H_executeGetInfoAcheteur = F_executeRequeteSql("SELECT * FROM acheteur WHERE idAcheteur = ?", [$Y_idAcheteur]);
    $H_executeGetInfoSelection = F_executeRequeteSql("SELECT * FROM selection WHERE idAcheteur = ?", [$Y_idAcheteur]);
    $H_executeGetInfoVersement = F_executeRequeteSql("SELECT * FROM versements WHERE idAcheteur = ?", [$Y_idAcheteur]);
    
    //recuperation des informations liees au terrain
    $H_executeGetBlocSite = F_executeRequeteSql("SELECT * FROM blocs WHERE	superficieCourranteBloc  > 0");
    $H_getPrixMetreCarre = "SELECT sites.prix_Vente FROM sites INNER JOIN blocs ON sites.numeroTitreFoncier = blocs.numeroTitreFoncier WHERE blocs.idBloc = ?";
    $H_executeGetLot = F_executeRequeteSql("SELECT blocs.numeroTitreFoncier, blocs.idBloc FROM blocs INNER JOIN selection USING(idBloc) WHERE idAcheteur= ?", [$Y_idAcheteur]);
    //Recuperation de la supercie que l'acheteur avait avant modification
    $H_getSupercieActuelle = F_executeRequeteSql('SELECT superficieSelection FROM selection WHERE idAcheteur = ?', [$Y_idAcheteur]);

    // Sélection des autres données (blocs, sites, employés)
    $H_executeBloc = F_executeRequeteSql("SELECT * FROM blocs ");
    $H_executeSites = F_executeRequeteSql("SELECT * FROM sites");
    $H_executeEmployes = F_executeRequeteSql("SELECT nomEmploye FROM employe");

    // declaration des variables et attributs
    $H_tableauErreurs = [];
    $H_regexTelephone = "/^(6[2]|6[5-9])([0-9]{7})/";

   
    if(($_SESSION['H_employeConnecte']==='connected'))
    {

        if(isset($Y_idEmployes) && $Y_idEmployes === $_SESSION['H_idEmploye'] )

        {
            if(isset($_POST['Modifier'])) //si le user clique sur le btn enregistrer
            {
                extract($_POST); //extraction du contenu du tableau $_POST
                $H_tableauValeurs = array($H_nomAcheteur, $H_typeDoc, $H_numDoc,  $H_dateNais, $H_telephoneAchteur, $H_adresseAcheteur, $H_site, $H_bloc, $H_superficie, $H_prixMetreCarre, $H_commercial, $H_montantVersement);
                $H_notesAcheteur;
                //var_dump($_POST);
        
                    if(F_exclureChampsVide($H_tableauValeurs) == true) //verifie si tous les champs sont remplis
                    {
                        if(mb_strlen($H_nomAcheteur) >= 2)
                        {
                                if(preg_match($H_regexTelephone, $H_telephoneAchteur)) // mb_strlen($H_telephoneAchteur) >= 9 && mb_strlen($H_telephoneAchteur) <= 18
                                {
                                  if (mb_strlen($H_numDoc) == 9 || mb_strlen($H_numDoc) == 6 || mb_strlen($H_numDoc) == 12)
                                            {
                                               // var_dump($H_tableauValeurs);

                                                    $H_executeGetSupCourrante = F_executeRequeteSql("SELECT superficieCourranteBloc FROM blocs WHERE idBloc =?", [$H_bloc]);
                                                    if($H_superficie <= $H_executeGetSupCourrante->superficieCourranteBloc+$H_getSupercieActuelle->superficieSelection)
                                                    {    $H_executeGetPrixMetreCarre = F_executeRequeteSql($H_getPrixMetreCarre, [$H_bloc]);
                                                         if($H_prixMetreCarre >= $H_executeGetPrixMetreCarre->prix_Vente)
                                                        {
                                                         
                                                            // ---------------------------------------------------- Dans la table acheteur ------------------------------------------

                                                            // var_dump(array($Y_idAcheteur, $H_idEmploye, strtoupper($H_nomAcheteur)." ".strtoupper($H_prenomAcheteur), $H_adresseAcheteur, $H_telephoneAchteur, $H_numDoc, , $H_dateNais, $H_commercial, $H_notesAcheteur));
                                                            // exit;
                                                            $H_updateAcheteur = 'UPDATE acheteur  SET idEmploye = ?, nomAcheteur = ?, adresseAcheteur = ?, telephoneAcheteur = ?, numeroCNI = ?, dateNaisAcheteur = ?, nomCommercial = ?, notesAcheteur = ?, dateCreateAcheteur = NOW() WHERE idAcheteur = ?';
                                                            $H_tableauParametres = [$Y_idEmployes, strtoupper($H_nomAcheteur), $H_adresseAcheteur, $H_telephoneAchteur, $H_numDoc, $H_dateNais, $H_commercial, $H_notesAcheteur, $Y_idAcheteur];
                                                            $H_executeUpdateAcheteur = F_executeRequeteSql($H_updateAcheteur, $H_tableauParametres); //ajoute le nouveau Acheteur pour la descente
                                                            $H_tableauErreurs[] = 'Cet Acheteur a été modifié avec success!!!';
    
                                                            // ---------------------------------------------------- Dans la table selection ------------------------------------------
                                                            
                                                            //recuperation du solde courant pour l'incrémenter
                                                            $H_montantTotalSelection = $H_superficie*$H_prixMetreCarre;
                                                            $H_updateSelection = 'UPDATE selection SET lot = ?, superficieSelection = ?, montantParMetre = ?, montantTotalSelection = ?, idBloc = ?, idEmploye = ?, dateCreateSelection = NOW() WHERE idAcheteur = ?';
                                                            $H_tableauParametres = [$H_site.' '.$H_bloc, $H_superficie, $H_prixMetreCarre, $H_montantTotalSelection, $H_bloc, $Y_idEmployes, $Y_idAcheteur];
                                                            $H_executeInsertSelection = F_executeRequeteSql($H_updateSelection, $H_tableauParametres); //ajoute la nouvelle Selection pour la descente
    
                                                            // ---------------------------------------------------- Dans la table Versements ------------------------------------------
                                                                        
                                                            //recuperation du solde courant pour l'incrémenter
    
                                                            $H_updateVersement = 'UPDATE versements SET montantVersement = ?, dateVersement = NOW(), dateCreateVersement = NOW() WHERE idAcheteur = ?';
                                                            $H_tableauParametres = [$H_montantVersement, $Y_idAcheteur];
                                                            $H_executeInsertVersement = F_executeRequeteSql($H_updateVersement, $H_tableauParametres); //ajoute le nouveau Versement pour la descente
                                                            $H_getIdVersement = F_executeRequeteSql('SELECT idVersement FROM versements WHERE idAcheteur = ?', [$Y_idAcheteur]);
                                                            // ---------------------------------------------------- Dans la table transaction ------------------------------------------
                                                                        
                                                            $H_updateTransaction = 'UPDATE transactions SET montantTransaction = ?, idEmploye = ?, dateTransaction = NOW(), dateCreateTransaction = NOW() WHERE idVersement = ?';
                                                            $H_tableauParametres = [$H_montantVersement, $Y_idEmployes, $H_getIdVersement->idVersement];
                                                            $H_executeInsertTransaction = F_executeRequeteSql($H_updateTransaction, $H_tableauParametres); //ajoute la nouvelle transaction pour la descente
                                                            
                                                            //-------------------------------------- mettre à jour la superficie du bloc et du site-------------------------------------------------------------
                                                            $H_updateBloc = "UPDATE blocs SET superficieCourranteBloc = superficieCourranteBloc+$H_getSupercieActuelle->superficieSelection-? WHERE idBloc = ?";
                                                            $H_executeUpdateBloc = F_executeRequeteSql($H_updateBloc, [$H_superficie, $H_bloc]);
    
                                                            $H_updateSite = "UPDATE blocs, sites SET superficieCourranteSite  = superficieCourranteSite -? WHERE blocs.numeroTitreFoncier = sites.numeroTitreFoncier AND blocs.idBloc = ?";
                                                            $H_executeUpdateSite = F_executeRequeteSql($H_updateSite, [$H_superficie, $H_bloc]);

                                                            // Redirection vers la page de TOUS les acheteur
                                                            header('Location:'.contructUrl('Y_acheteur' , ['H_idEmploye'=>$_SESSION['H_idEmploye'], 'Y_idAcheteur'=>$Y_idAcheteur]));
                                                            exit;
                                                        
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
  
    
    require('views/acheteur/acheteurUpdate.php');
?>