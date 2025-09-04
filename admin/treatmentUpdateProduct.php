<?php
    session_start();
    if(!isset($_SESSION['id']))
    {
        header("LOCATION:index.php");
        exit();
    }

    require "../connexion.php";
    if(isset($_GET['id']) && is_numeric($_GET['id']))
    {
        $id = htmlspecialchars($_GET['id']);
        $req = $bdd->prepare("SELECT * FROM products WHERE id=?");
        $req->execute([$id]);
        $donProduct = $req->fetch(PDO::FETCH_ASSOC);
        $req->closeCursor();
        if(!$donProduct)
        {
            header("LOCATION:products.php");
            exit();
        }
    }else{
        header("LOCATION:products.php");
        exit();
    }

    // vérifier l'envoie du form
    if(isset($_POST['nom']))
    {
        $err = 0;
        if(empty($_POST['nom']))
        {
            $err = 1;
        }
        else{
            $nom = htmlspecialchars($_POST['nom']);
        }

        if(empty($_POST['marque']) || !is_numeric($_POST['marque']))
        {
            $err = 2;
        }
        else{
            $marque = htmlspecialchars($_POST['marque']);
            // vérifier si la marque existe bien dans la bdd
            $vm = $bdd->prepare("SELECT id FROM marques WHERE id=?");
            $vm->execute([$marque]);
            if(!$don = $vm->fetch())
            {
                $err=3;
            }
            $vm->closeCursor();
        }

        if(empty($_POST['description']))
        {
            $err = 4;
        }
        else{
            $description = htmlspecialchars($_POST['description']);
        }

        
        if(empty($_POST['prix']))
        {
            $err = 5;
        }
        else{
            $prix = htmlspecialchars($_POST['prix']);
        }

        // test de $err
        if($err == 0)
        {
            if($_FILES['image']['error']==0)
            {
                $dossier = '../images/';
                $fichier = basename($_FILES['image']['name']);
                $tailleMaxi = 2000000;
                $taille = filesize($_FILES['image']['tmp_name']);
                $extensions = ['.png', '.jpg', '.jpeg'];
                $extension = strrchr($_FILES['image']['name'], '.');

                if(!in_array($extension,$extensions))
                {
                    $err=5;
                }

                if($taille>$tailleMaxi)
                {
                    $err=6;
                }

                if($err==0)
                {
                    // insertion dans la bdd
                     $fichier = strtr($fichier,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                     $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
                     $fichiercplt = rand().$fichier;
                     
                     if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier.$fichiercplt))
                     {
                        // supprimer les anciennes images
                        unlink("../images/".$donProduct['cover']);
                        unlink("../images/mini_".$donProduct['cover']);
                        // update dans la bdd
                        $update = $bdd->prepare("UPDATE products SET nom=:nom, marque=:marque, description=:descri, cover=:img, price=:prix WHERE id=:id");
                        $update->execute([
                            ":nom" => $nom,
                            ":marque" => $marque,
                            ":descri"=> $description,
                            ":img" => $fichiercplt,
                            ":prix" => $prix,
                            ':id' => $id
                        ]);
                        // gestion des redim
                        if($extension == ".jpg" || $extension == ".jpeg")
                        {
                            header("LOCATION:redim.php?id=".$id."&image=".$fichiercplt);
                            exit();
                        }else{
                            // dans le cas d'un fichier .png
                            header("LOCATION:redimpng.php?id=".$id."&image=".$fichiercplt);
                            exit();
                        }

                     }else{
                        header("LOCATION:updateProduct.php?id=".$id."&errorimg=7");
                        exit();
                     }
     

                }else{
                    header("LOCATION:updateProduct.php?id=".$id."&errorimg=".$err);
                    exit();
                }

            }elseif($_FILES['image']['error']==4)
            {
                 // update dans la bdd
                $update = $bdd->prepare("UPDATE products SET nom=:nom, marque=:marque, description=:descri, price=:prix WHERE id=:id");
                $update->execute([
                    ":nom" => $nom,
                    ":marque" => $marque,
                    ":descri"=> $description,
                    ":prix" => $prix,
                    ':id' => $id
                ]);
                header("LOCATION:products.php?update=".$id);
                exit();
            }
            else{
                header("LOCATION:updateProduct.php?id=".$id."&errorimg=".$_FILES['image']['error']);
                exit();
            }
        }else{
            header("LOCATION:updateProduct.php?id=".$id."&error=".$err);
            exit();
        }



    }else{
        header("LOCATION:products.php");
        exit();
    }