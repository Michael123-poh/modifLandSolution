<?php
    //fonction permettant la configuration à la base de données
    function F_databaseConnection(string $servername, string $dbname, string $username, string $password)
    {
        try 
        {
            $conn = new PDO("mysql:host=".$servername."; dbname=".$dbname, $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $conn->setAttribute(PDo::MYSQL_ATTR_INIT_COMMAND, "set NAMES utf8");
             return $conn;
        }
        catch (PDOException $e)
        {
            die ("Echec de la connexion:" .$e->getMessage());
        }

    }

?>