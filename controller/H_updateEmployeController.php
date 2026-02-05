<?php
    session_start(); //demarrer la session
    //************* appel du fichier de connexion a la base de donnée***** */
    require_once("models/H_databaseConnection.php");
    $H_dbConnect = F_databaseConnection("localhost", "systeme_land", "root", "");

    // Recuperer l'URL décodée
    $H_idEmployeConnected = $Y_urlDecoder['H_idEmploye']; 
    $H_idEmployeUpdate = $Y_urlDecoder['H_idEmployeUpdate']; 
     // declaration des variables et attributs
    $H_tableauErreurs = [];
    $H_regexTelephone = "/^(6[2]|6[5-9])([0-9]{7})/";

   
    if(($_SESSION['H_employeConnecte']==='connected'))
    {
        if($H_idEmployeConnected === $_SESSION['H_idEmploye'])
        { 
            if(isset($_POST['Sauvegarder'])) //si le user clique sur le btn enregistrer
            {
                if(isset($_POST['H_idEmployeUpdate']) && $H_idEmployeUpdate === $_POST['H_idEmployeUpdate'])
                {   
                    //$idEmployeToUpdate = isset($_POST['H_idEmployeUpdate']) ? $_POST['H_idEmployeUpdate'] : null;
                    $_SESSION['H_idEmployeUpdate'] = $H_idEmployeUpdate; //on stock l'id de l'employé a mettre a jour dans la session
                    extract($_POST); //extraction du contenu du tableau $_POST
                    $H_tableauValeurs = array($H_nomEmploye, $H_pseudoEmploye, $H_dateNaisEmploye, $H_emailEmploye, $H_telephoneEmploye, $H_adresseEmploye, $H_idPosteEmploye);
            
            
                        if(F_exclureChampsVide($H_tableauValeurs) == true) //verifie si tous les champs sont remplis
                        {
                            if(mb_strlen($H_nomEmploye) >= 2)
                            {
                                    if(preg_match($H_regexTelephone, $H_telephoneEmploye)) // mb_strlen($H_telephoneEmploye) >= 9 && mb_strlen($H_telephoneEmploye) <= 18
                                    {
                                    if(filter_var($H_emailEmploye, FILTER_VALIDATE_EMAIL)) //verifie si l'email est valide
                                    {
                                                            
                                                                // ---------------------------------------------------- Dans la table Employe ------------------------------------------

                                                                // var_dump(array($H_idPosteEmploye, $H_idEmploye, strtoupper($H_nomEmploye), $H_adresseEmploye, $H_telephoneEmploye, $H_dateNaisEmploye, $H_emailEmploye, $H_pseudoEmploye, $_SESSION['H_idEmployeUpdate']));
                                                                // exit;
                                                                $H_updateEmploye = 'UPDATE employe  SET idTypeEmploye = ?, nomEmploye = ?, pseudoEmploye = ?, emailEmploye = ?, adresseEmploye = ?, dateNaisEmploye = ?, telephoneEmploye = ? WHERE idEmploye = ?';
                                                                $H_tableauParametres = [$H_idPosteEmploye, strtoupper($H_nomEmploye), $H_pseudoEmploye, $H_emailEmploye, $H_adresseEmploye, $H_dateNaisEmploye, $H_telephoneEmploye, $_SESSION['H_idEmployeUpdate']];
                                                                $H_executeUpdateEmploye = F_executeRequeteSql($H_updateEmploye, $H_tableauParametres); //ajoute le nouveau Employe pour la descente
                                                                $H_tableauErreurs[] = 'Cet Employe a été modifié avec success!!!';
                                                                //echo "L'employe a été modifié avec success!!!";
                                                                // Redirection vers la page de TOUS les Employe
                                                                header('Location:'.contructUrl('H_employes' , ['H_idEmploye'=>$_SESSION['H_idEmploye']]));
                                                                exit;
                                    }
                                    else
                                    {
                                        $H_tableauErreurs[] = 'L\'email est incorrect!';
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
    }
    else
        //var_dump($_SESSION['H_employeConnecte']);
        header('Location:index.php');
?>