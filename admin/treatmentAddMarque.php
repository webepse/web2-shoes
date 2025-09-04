<?php
    session_start();
    if(!isset($_SESSION['id']))
    {
        header("LOCATION:index.php");
        exit();
    }

    // vérif de l'envoie du formulaire
    if(isset($_POST['nom']))
    {
        // vérifier les valeurs
        // init error
        $err = 0;
        if(empty($_POST['nom']))
        {
            $err=1;
        }else{
            $nom = htmlspecialchars($_POST['nom']);
        }

        // vérif l'état de $err
        if($err == 0)
        {
            // insert dans la bdd
            require "../connexion.php";
            $insert=$bdd->prepare("INSERT INTO marques(nom) VALUES(:nom)");
            $insert->execute([
                ":nom" => $nom
            ]);
            header("LOCATION:marques.php?add=success");
            exit();
        }else{
            header("LOCATION:addMarque.php?error=".$err);
             exit();
        }


    }else{
        header("LOCATION:marques.php");
        exit();
    }


?>