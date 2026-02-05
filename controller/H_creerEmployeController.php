<?php

    session_start();
    $currentPage = basename($_SERVER['PHP_SELF']);
    // 1. Connexion à la BD

    require_once('models/H_databaseConnection.php');

    $H_dbConnect = F_databaseConnection("localhost", "systeme_land", "root", "");
    // Appel du fichier des fonctions du modèle
    // require("models/H_functionsModels.php");

    
    $H_idEmploye = $Y_urlDecoder['H_idEmploye'];
    // var_dump($H_idEmploye." et ". $_SESSION['H_idEmploye']);
    // exit;
    $H_empAsPrivilege = 'SELECT * FROM Employesprivileges wHERE idEmploye =? and idPrivilege=?';
    $H_execute_req= F_executeRequeteSql($H_empAsPrivilege,[$H_idEmploye,"PRI00004"]);

    // declaration des variables et attributs
   
    $H_tableauErreurs = [];
    $H_regexTelephone = "/^(6[2]|6[5-9])([0-9]{7})/";
    //  test
    // $_SESSION['H_EmployeConnecte'] = 'connected';

    if(($_SESSION['H_employeConnecte']==='connected'))
    {
        if(isset($H_idEmploye) && $H_idEmploye === $_SESSION['H_idEmploye'] )
        {
            if(isset($_POST['Enregistrer'])) //si le user clique sur le btn enregistrer
            {
                extract($_POST); //extraction du contenu du tableau $_POST
                $H_tableauValeurs = array($H_nomEmploye, $H_prenomEmploye, $pseudoEmploye,  $H_dateNaisEmploye, $H_telephoneEmploye, $H_adresseEmploye, $H_emailEmploye, $H_posteEmploye);
                //var_dump($_POST);
        
                    if(F_exclureChampsVide($H_tableauValeurs) == true) //verifie si tous les champs sont remplis
                    {
                        if(mb_strlen($H_nomEmploye) >= 2)
                        {
                            if(mb_strlen($H_prenomEmploye) >= 2)
                            {
                                if(preg_match($H_regexTelephone, $H_telephoneEmploye)) // mb_strlen($H_telephoneEmploye) >= 9 && mb_strlen($H_telephoneEmploye) <= 18
                                {
                                  if (mb_strlen($pseudoEmploye) >= 5)
                                  {
                                    if(filter_var($H_emailEmploye, FILTER_VALIDATE_EMAIL))
                                    {
                                            // On verifie si l'email n'est pas deja utilisé
                                            $H_emailExist = F_executeRequeteSql("SELECT * FROM Employe WHERE emailEmploye = ?", [$H_emailEmploye]);
                                            if(empty($H_emailExist))
                                            {
                                                            // ---------------------------------------------------- Dans la table Employe ------------------------------------------
                                                            //on recupere le dernier Employe enregistré dans la bd
                                                            $H_resultatLastEmploye = F_executeRequeteSql('SELECT * FROM employe ORDER BY idEmploye DESC LIMIT 1');
                                                
                                                            foreach($H_resultatLastEmploye as $H_Employe)
                                                            {
                                                                $H_idEmploye = $H_Employe->idEmploye;
                                                               
                                                            }
                                                        
                                                            //s'il n'y'a aucun Employe on initiale le 1er Employe Si oui on ajoute un nouveau Employe
                                                                $H_newIdEmploye = F_genereMatricule($H_idEmploye, 'EMP00001'); //sinon on incremente le nième Employe
                                                            
                                                            //recuperation de l'id de l'Employe qui enregistre l' Employe
                                                            $H_idEmploye = $_SESSION['H_idEmploye'];

                                                            //generer dynamiquement le mot de passe
                                                            $H_mdpEmploye = F_generatePassword();
                                                            $H_insertEmploye = 'INSERT INTO employe (idEmploye, idTypeEmploye, nomEmploye, pseudoEmploye, emailEmploye, adresseEmploye, telephoneEmploye, dateNaisEmploye, mdpEmploye, dateCreateEmploye) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())';
                                                            $H_tableauParametres = [$H_newIdEmploye, $H_posteEmploye, strtoupper($H_nomEmploye)." ".strtoupper($H_prenomEmploye), $pseudoEmploye, $H_emailEmploye, $H_adresseEmploye, $H_telephoneEmploye,  $H_dateNaisEmploye, password_hash($H_mdpEmploye, PASSWORD_DEFAULT)];
                                                            $H_executeInsertEmploye = F_executeRequeteSql($H_insertEmploye, $H_tableauParametres); //ajoute le nouveau Employe pour la descente
                                                            $H_tableauErreurs[] = 'Nouvel Employe enregistré avec success!!!';

                                                            header('Location:'.contructUrl('H_employes' , ['H_idEmploye'=>$_SESSION['H_idEmploye']]));
                                                           // header('Location:/land_solution/'.encodeUrl(['page'=>'H_employes' , 'H_idEmploye'=>$H_idEmploye]));
                                                        
                                                    }
                                                    else
                                                    {
                                                        $H_tableauErreurs[] = 'L\'adresse email est deja utilisee!';
                                                    }   
                                                    }
                                                    else
                                                    {
                                                        $H_tableauErreurs[] = 'L\'adresse email est invalide!';
                                                    }
                                            
                                            }
                                            else
                                            {
                                                $H_tableauErreurs[] = 'Le pseudo doit comporter au moins 5 carateres!';
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
        //var_dump($_SESSION['H_EmployeConnecte']);
        header('Location:index.php');
  

    // 4. Inclusion de la vue

    require('views/Employes/EmployesView.php');
?>