<?php
    // bdd
    require "connexion.php";
    $tabMenu = [
        "home" => "home.php",
        "products" => "products.php",
        "product" => "show.php"
    ];

    if(isset($_GET['action']))
    {
        if(array_key_exists($_GET['action'],$tabMenu))
        {
            if($_GET['action']=="product")
            {
                // dans le cas de product, on a besoin de l'id
                if(isset($_GET['id']) && is_numeric($_GET['id']))
                {
                    $id = htmlspecialchars($_GET['id']);
                    // vérifier que l'id correspond bien à un produit
                    $prod = $bdd->prepare("SELECT products.cover AS image, products.nom AS pnom, products.id AS pid, marques.nom AS mnom, products.description AS description, products.price AS prix FROM products INNER JOIN marques ON marques.id=products.marque WHERE products.id=?");
                    $prod->execute([$id]);
                    $donProd = $prod->fetch(PDO::FETCH_ASSOC);
                    $prod->closeCursor();

                    if(!$donProd)
                    {
                        header("LOCATION:404.php");
                        exit();
                    }

                    $menu = $tabMenu[$_GET['action']];


                }else{
                    header("LOCATION:404.php");
                    exit();
                }

            }else{
                $menu = $tabMenu[$_GET['action']];
            }
        }else{
            header("LOCATION:404.php");
            exit();
        }

    }else{
        $menu = $tabMenu['home'];
    }

?>



<!DOCTYPE html>
<html lang="en">
    <?php include("partials/head.php"); ?>
<body>
    <?php include("partials/nav.php"); ?>
    
    <!-- contenu du site -->
     <?php include("pages/".$menu); ?>

    <?php include("partials/footer.php"); ?>
</body>
</html>