<?php
    session_start();
    if(!isset($_SESSION['id']))
    {
        header("LOCATION:index.php");
        exit();
    }

    require "../connexion.php";
    // pagination
    $reqCount = $bdd->query("SELECT * FROM products");
    $count = $reqCount->rowCount();
    // la limit
    $limit= 5;
    $nbPage = ceil($count/$limit);

    // sur une pagination de 10 
    // 41 éléments
    // il faut 5 pages (41/10= 4.1)

    // connaître la page actuelle
    if(isset($_GET['page']) && is_numeric($_GET['page']))
    {
        $pg = htmlspecialchars($_GET['page']);
    }else{
        // dans le cas où il n'y a pas eu de pagination effective
        $pg = 1;
    }

    // calcule de l'offset
    $offset = ($pg-1)*$limit;
    // (1 - 1) * 10 = 0
    // (2 - 1) * 10 = 10


    // suppression d'un produit
    if(isset($_GET['delete']) && is_numeric($_GET['delete']))
    {
        $id = htmlspecialchars($_GET['delete']);
        $verif = $bdd->prepare("SELECT * FROM products WHERE id=?");
        $verif->execute([$id]);
        $donV = $verif->fetch(PDO::FETCH_ASSOC);
        $verif->closeCursor();
        if(!$donV)
        {
            header("LOCATION:products.php");
            exit();
        }

        // supprimer les images
        unlink('../images/'.$donV['cover']);
        unlink('../images/mini_'.$donV['cover']);

        // vérifier les images associées
        $images = $bdd->prepare("SELECT * FROM images WHERE id_product=?");
        $images->execute([$id]);
        $donImg = $images->fetchAll(PDO::FETCH_ASSOC);
        $images->closeCursor();
        foreach($donImg as $img)
        {
            unlink("../images/".$img['fichier']);
        }
        
        $deleteImg = $bdd->prepare("DELETE FROM images WHERE id_product=?");
        $deleteImg->execute([$id]);

        $delete = $bdd->prepare("DELETE FROM products WHERE id=?");
        $delete->execute([$id]);
        header("LOCATION:products.php?successDel=".$id);
        

    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Administration</title>
</head>
<body>
    <?php
        include("partials/header.php");
    ?>
    <div class="container-fluid">
        <h1>Gestion des produits</h1>
        <a href="addProduct.php" class="btn btn-success my-3">Ajouter un produit</a>
        <?php
            if(isset($_GET['add']))
            {
                if($_GET['add']=="success")
                {
                    echo "<div class='alert alert-success'>Vous avez bien ajouté un nouveau produit à la base de données</div>";
                }
            }

            if(isset($_GET['update']))
            {
               
                echo "<div class='alert alert-warning'>Vous avez bien modifié le produit n°".$_GET['update']."</div>";
               
            }

            if(isset($_GET['successDel']))
            {
               
                echo "<div class='alert alert-danger'>Vous avez bien supprimé le produit n°".$_GET['successDel']."</div>";
               
            }
        ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Marque</th>
                    <th>nom</th>
                    <th>Prix</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $req = $bdd->prepare("SELECT products.id AS pid, products.nom AS pnom, marques.nom AS mnom, products.price AS prix FROM products INNER JOIN marques ON products.marque = marques.id ORDER BY products.id DESC LIMIT :offset,:limit");
                    $req->bindParam(':offset',$offset, PDO::PARAM_INT);
                    $req->bindParam(':limit',$limit, PDO::PARAM_INT);
                    $req->execute();
                    while($don = $req->fetch(PDO::FETCH_ASSOC))
                    {
                        echo "<tr>";
                            echo "<td>".$don['pid']."</td>";
                            echo "<td>".$don['mnom']."</td>";
                            echo "<td>".$don['pnom']."</td>";
                            echo "<td>".$don['prix']."</td>";
                            echo "<td>";
                                echo "<a href='updateProduct.php?id=".$don['pid']."' class='btn btn-warning'>Modifier</a>";
                                echo "<a href='products.php?delete=".$don['pid']."' class='btn btn-danger mx-2'>Supprimer</a>";
                            echo "</td>";
                        echo "</tr>";
                    }
                    $req->closeCursor();
                ?>
            </tbody>
        </table>

        

        <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php
                if($pg>1)
                {
                    echo '<li class="page-item"><a class="page-link" href="products.php?page='.($pg-1).'">Previous</a></li>';
                }
                for($cpt=1;$cpt<=$nbPage;$cpt++)
                {
                    echo '<li class="page-item"><a class="page-link" href="products.php?page='.$cpt.'">'.$cpt.'</a></li>';
                }
                if($pg!=$nbPage)
                {
                     echo '<li class="page-item"><a class="page-link" href="products.php?page='.($pg+1).'">Next</a></li>';
                }
            ?>
        </ul>
        </nav>





    </div>
    <?php
        include("partials/footer.php");
    ?>
</body>
</html>