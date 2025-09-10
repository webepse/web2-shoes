<?php
    /**
     * @var PDO $bdd
     * connexion.php
     */

    // bdd
    require "connexion.php";
    $tabMenu = [
        "home" => "home.php",
        "products" => "products.php",
        "product" => "show.php"
    ];

    if(isset($_GET['action']) && !empty($_GET['action']))
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
                    /**
                     * PDO $bdd
                     */
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

            }elseif($_GET['action']=="products")
            {
                // pagination
                $reqCount = $bdd->query("SELECT * FROM products");
                $count = $reqCount->rowCount();
                // la limit
                $limit= 5;
                $nbPage = ceil($count/$limit);

                // connaître la page actuelle
                if(isset($_GET['page']) && is_numeric($_GET['page']))
                {
                    $pg = htmlspecialchars($_GET['page']);
                    // gestion d'un numéro de page trop élevé dans l'URL
                    if($pg>$nbPage)
                    {
                        $pg = $nbPage;
                    }
                }else{
                    // dans le cas où il n'y a pas eu de pagination effective
                    $pg = 1;
                }

                // calcule de l'offset
                $offset = ($pg-1)*$limit;

                $menu = $tabMenu['products'];
            }
            else{
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