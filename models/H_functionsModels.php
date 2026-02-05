<?php
    // require_once('../models/H_databaseConnection.php'); //require_once est utilisé lorqu'on fera appelle une seule fois à la fonction
    // $H_dbConnect = F_databaseConnection('localhost', 'BDCBWONDER', 'root', '');

   	//-1---------------------------------------Fonction qui permet d'exécuter une requete: soit query, soit prepare-------------------------------------------
        if(!function_exists('F_executeRequeteSql'))
        {
            function F_executeRequeteSql (string $H_requete, array $H_tableauParams=NULL)
            {
                global $H_dbConnect;
                    if ($H_tableauParams == NULL)
                    {        
                        $H_requete=$H_dbConnect->query($H_requete);
                        $resultat=$H_requete->fetchall();
                        // var_dump($resultat);
                    }
                    else
                    {
                        $H_requete=$H_dbConnect->prepare($H_requete);
                        $H_requete->execute($H_tableauParams);
                        $nbre=$H_requete->rowCount();
                        if ($nbre == 1)
                        {
                                $resultat=$H_requete->fetch();
                                // var_dump($resultat);
                        }
                        else
                        {
                                $resultat=$H_requete->fetchall();
                                // var_dump($resultat);
                        }
                        
                    }
                return $resultat;
            }
        }
	//----------------------------------------fin Fonction qui permet d'exécuter une requete: soit query, soit prepare-------------------------------------------
    

    //-2----------------------------Verifier si un champ es vide-------------------------------
        if(!function_exists('F_estChaineVide'))
        {
            function F_estChaineVide(string $chaine)
                {

                    if(!empty(trim($chaine, ' ')))
                    {
                        //echo 'Vous avez entre: '.$chaine;
                        return 1;
                        
                    }
                    else
                    {
                        ///echo 'Attention, vous avez rempli un champs vide!!!';
                        return 0;			
                    }

                }
        }
	//-----------------------------fin fonction qui Verifier si un champ es vide-------------------------------



	//-3---------------------------------Verifie si la taille du champ est superieur a taille max-----------------------------------
        if(!function_exists('F_tailleChaineSupChaineMax'))
        {
            function F_tailleChaineSupChaineMax(int $long, string $chaine)
            {
                if (F_estChaineVide($chaine) == 1)
                { 
                    if ($long > 0)
                    {
                        $poids = mb_strlen($chaine);
                        if($poids > $long)
                        {
                            //echo 'La chaine de caractere '.$chaine. ' depasse le format de '.$long. ' fournit!';
                            return 1;
                        } 
                        else
                        {
                            //echo 'La chaine '.$chaine.' a '.$poids.' caracteres!';
                            return 0;
                        }
                    }
                    else
                    {
                        //echo 'La taille maximale fixee est negative';
                        return 2;
                    }
                    
                }
                else
                {
                    //echo 'Le champ dont vous voulez verifier la taille est vide';
                    return -1;

                }   
                
            }
        }
	//----------------------------------fin fonction Verifie si la taille du champ est superieur a taille max-----------------------------------



	//-4------------------------------Verifie si le nom entre en parametre existe deja dans le tableau------------------------------
		if(!function_exists('F_valeurExisteDansTableau'))
        {
            function F_valeurExisteDansTableau(array $H_tableauReference, string $occurence)
            {
                if (count($H_tableauReference) != 0 )
                {
                    if (F_estChaineVide($occurence) == 1)
                    {

                        if  (in_array($occurence, $H_tableauReference))
                        {
                            return 1;
                            //	echo 'Cet H_tableauReference existe deja dans le tableau!';
                        }
                        else
                        {
                            return 0;	
                            //	echo 'Cet H_tableauReference n\'existe pas dans le tableau ';
                        }
                            print_r($H_tableauReference);
                    }
                    else
                    {
                        return 2;
                        //echo 'Le champ contenant le nom de l\'etudiant que vous souhaitez verifier est vide';
                    }
                }
                else
                {
                    return -1;
                    //echo 'Le tableau est vide!';
                }
                
            }
        }
	//-------------------------------fin fonction qui Verifie si le nom entre en parametre existe deja dans le tableau------------------------------


	//-5--------------------------Fonction permettant de verifier si les elements d'un tableau appartiennent à un autre------------------------------
        if(!function_exists('F_tableauElement'))
        {
            function F_tableauElement( $reference, array $tableau)
            {
                $comp = array_diff($tableau, $reference);
                if (count($reference) != 0)
                {
                    if (count($tableau) != 0)
                    {
                        if (count($comp) == 0)
                        {
                            //echo 'Oui, tous les elements sont dans le tableau de reference!';
                            return 1;
                        }
                        else
                        {
                           // echo 'Les elements suivant n\'existent pas dans le tableau de reference:';
                            //print_r($comp);
                            return 0;
                        }
                    }
                    else
                    {
                        //echo 'Le tableau d\'elements a verifier est vide!';
                        return 2;
                    }
                }
                else
                {
                    //echo 'Le tableau de reference est vide!';
                    return -1;
                }   
            }
        }
    //---------------------------fin Fonction permettant de verifier si les elements d'un tableau appartiennent à un autre------------------------------


    //-6-----------------------------------------Fonction permettant de verifier que les champs du tableau pris en parametre sont tous remplis et possèdent une valeur-------------------------------------------------
		if(!function_exists('F_exclureChampsVide'))
        {
            function F_exclureChampsVide(array $tableau)
            {
                $c = true ;
                if (count($tableau) != 0)
                {
                    foreach ($tableau as $key ) 
                    {
                        if (F_estChaineVide($key)==0) 
                        {
                            // $erreurs[] = $key."-> pas bon";
                            $c = false;
                        }
                            
                    }
                }
                
                return $c ;
            }
        }
	//------------------------------------------fin Fonction permettant de verifier que les champs du tableau pris en parametre sont tous remplis et possèdent une valeur-------------------------------------------------

    
    //-7--------------------------------Fonction permattant d'incrémenter de 1 un matricule fournit en paramètre-------------------------------------
		// if(!function_exists('F_genereMatricule'))
        // {
        //     function F_genereMatriculel(string $user)
        //     {
        //         $formatMatricule = substr ($user, 0, strlen($user)-5);
        //         $partieEntiere = substr($user, -5);
        //         $partieEntiere = (int)$partieEntiere+1;
        //         $nbreConverti = sprintf('%05d', $partieEntiere);
        //         $newMatricule = $formatMatricule.$nbreConverti;
                
        //         return $newMatricule;
                
        //     }
        // }
	//---------------------------------fin fonction permattant d'incrémenter de 1 un matricule fournit en paramètre-------------------------------------

  
    //-8-------------------------------------Fonction permettant de générer un menu actif en php----------------------------------------------------------
        if(!function_exists('F_menuActif'))
        {
            function F_menuActif($href)
            {
                $separateur = '/';
                $script_name = $_SERVER['SCRIPT_NAME'];
                $tab = explode($separateur,$script_name );
                $current = array_pop($tab);
            
                if ($current == $href.'.php')
                {	
                    echo ' active';
                }
               
            }	
        }
	//--------------------------------------fin Fonction permettant de générer un menu actif en php---------------------------------------------------------- 


    //-9----------------------------------Fonction permettant de changer le titre d'une page courante-------------------------------------------------------------------
	if (!function_exists('F_changerTitrePage'))
	{
		function F_changerTitrePage($H_nomStructure, $H_titre)
		{
			$H_current = array_pop(explode('/', $_SERVER['SCRIPT_NAME']));
			if ($H_current == $H_titre.'.php' && $H_titre == 'index')
			{
				return $H_nomStructure;
			}
			else
			{
				return $H_nomStructure.$H_titre;
			}

		}
	}
	//-----------------------------------fin Fonction permettant de changer le titre d'une page courante-------------------------------------------------------------------
    

    //-10-----------------------fonction permettant de verifier qu'un utilisateur est connecté et d'activer le bouton------------------------
    if (!function_exists('F_boutonActif'))
    {
        function F_boutonActif($tableau_session, $message)
        {
            if (isset($tableau_session) && isset($message) && $tableau_session == $message)
            {
                return 'enabled';
            }
            else
            {
                return 'disabled';
            }
        }
    }
    //------------------------fonction permettant de verifier qu'un utilisateur est connecté et d'activer le bouton------------------------


    //-11-----------------------------fonction permettant d'afficher les erreurs d'un formulaire en rouge------------------------------------
    if (!function_exists('F_flashErrors'))
    {
        function F_flashErrors(array $tableau_erreur)
        {
            if (!empty($tableau_erreur))
            {
                foreach ($tableau_erreur as $err)
                {
                    return $err;
                }
            }
        }
    }
    //------------------------------fonction permettant d'afficher les erreurs d'un formulaire en rouge------------------------------------

	//*****************fonction qui incremente id_client ***********/ 
    if(!function_exists('F_genereMatricule'))
    {
        function F_genereMatricule($id,$ida) 
        { 
            if(!empty($id))
            {
                $num=substr($id,-5);
                $nom=substr($id,0,-5);
                $numero=intval($num) ;
                for($i=$num;$i<=$num+1;$i++)                                                                                                                         
                {
                    $nu= sprintf("%05d",$i);
                    $n="$nom$nu";
                }
                return $n;
            }
            else
            {
            return  $ida;
            
            }
        }
    }
	//***************** fonction pour garder les cvhecbox checker lorqu'ils sont deja coché */ 
    if(!function_exists('F_calerCheked'))
    {
        function F_calerCheked( $id,$ida) 
        { 
            if(empty($id->idAcheteur))
            {
                foreach($id as $keyId)
                {
                if($keyId->idAcheteur==$ida)
                {
                return  true;
                } 
                else
                {
                return  false;
                }
                }
            }
            else
            {
                if($id->idAcheteur==$ida)
                {
                return  true;
                } 
                else
                {
                return  false;
                }
            }
            
        }
    }    
	// ---------------------------- F_etat ------------------------
    if(!function_exists('F_etat'))
    {
        function F_etat( $variable)
        {
            if($variable != null)
            {
                return "text-success";
            }
            else
            {
                return "text-danger";
            }
        }
    }
	// ---------------------------- F_Signer ------------------------
    if(!function_exists('F_Signer'))
    {
        function F_Signer( $var)
        {
            if ($var == "text-success")
            {
                    return "signer";
            }
            else
            {
                    return "non signer"; 
            }
        }
	}
	// ---------------------------- F_Soldé ------------------------
    if(!function_exists('F_Soldé'))
    {
        function F_Soldé( $var)
        {
            if ($var == "text-success")
            {
                return "soldé";
            }
            else
            {
                return "non soldé"; 
            }
        }
    }

      //------------------------ desactiveboutton---------------------
    //   function F_desactiveBoutton( $requet, $valeur)
    //   {
    //       if (empty($requet->idClient))
    //       {
    //           foreach ($requet as $key) {
    //              if($key->idClient==$valeur)
    //              {
    //              echo "disabled";
    //              }else
    //              {
    //               echo "";
    //              }
    //           }
             
    //       }
    //       else
    //       {
    //           if($requet->idClient==$valeur)
    //           {
    //           echo "disabled";
    //           }else
    //           {
    //            echo "";
    //           } 
    //       }
    //   }

      if(!function_exists('F_gestionPrivilege'))
    {
        function F_gestionPrivilege($H_idEmploye, $H_idPrivilege)
        {
            $H_requete = "SELECT * FROM employesprivileges WHERE idEmploye = ? AND idPrivilege = ?";
            $H_resultat = F_executeRequeteSql($H_requete, [$H_idEmploye, $H_idPrivilege]);
            if(empty($H_resultat))
                return false;
            else
                return true;
        
        }

    }

    // ---------------------------- F_calculerAge ------------------------
    if(!function_exists('F_calculerAge'))
    {

        function F_calculerAge($dateNaissance) {
            $dateNaissance = new DateTime($dateNaissance);
            $dateActuelle = new DateTime();
            $age = $dateNaissance->diff($dateActuelle)->y;
            return $age;
        }
    }

        // ---------------------------- F_encoder_L'URL ------------------------
    if(!function_exists('encoder_URL'))
    {
        function encodeUrl($data) {
            return rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
        }
    }

    // ---------------------------- F_decoder_L'URL ------------------------
    if(!function_exists('decoder_URL'))
    {
        function decodeUrl($string) {
            return json_decode(base64_decode(strtr($string, '-_', '+/')), true);
        }
    }

    // ---------------------------F_ContruireUrl -----------------------------
    if(!function_exists('contructUrl')){
        function contructUrl($page, $params = []) {
            $encodedParams = '';
            if (!empty($params)) {
                $encodedParams = encodeUrl($params);
            }
            return "/land_solution/$page/$encodedParams";
        }
    }


    // ---------------------------- F_generatePassword ------------------------
    if(!function_exists('F_generatePassword'))
    {
        function F_generatePassword($length = 10) 
        {
            // Définir les caractères a utiliser pour générer le mot de passe
            $charsAllowed = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
            
            $password = '';
            
            // Générez une chaîne de caractères aléatoire
            for ($i = 0; $i < $length; $i++) {
                // Piquez un caractère aléatoire dans la chaîne
                $password .= $charsAllowed[rand(0, strlen($charsAllowed) - 1)];
            }
            return $password;
        }
    }

?>
 