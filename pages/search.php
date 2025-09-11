<div class="container">
    <h1>Recherche</h1>
    <form action="index.php" method="GET">
            <input type="hidden" name="action" value="search">
        <div class="form-group my-3">
            <label for="search">votre recherche </label>
            <input type="text" id="search" name="search" class="form-control">
        </div>
        <div class="form-group">
            <input type="submit" value="Rechercher" class="btn btn-primary">
        </div>
    </form>

    <div class="row justify-content-between">
    <?php
        if(isset($search))
        {
            $mySearch = $bdd->prepare("SELECT products.cover AS image, products.nom AS pnom, products.id AS pid, marques.nom AS mnom FROM products INNER JOIN marques ON marques.id=products.marque WHERE products.nom LIKE :search OR marques.nom LIKE :search");
            $mySearch->execute([
                ":search" => "%".$search."%"
            ]);
            $countSearch = $mySearch->rowCount();
            $donSearch = $mySearch->fetchAll(PDO::FETCH_ASSOC);
            $mySearch->closeCursor();
            if($countSearch > 0)
            {
                foreach($donSearch as $product):
            ?>
                <div class="card col-md-3 p-2">
                    <div class="img">
                        <img src="images/<?= $product['image'] ?>" alt="image de <?= $product['pnom'] ?>" class="img-fluid">
                    </div>
                    <div class="content">
                        <h4><?= $product['mnom'] ?></h4>
                        <h3><?= $product['pnom'] ?></h3>
                    </div>
                    <a href="product-<?= $product['pid'] ?>" class="btn btn-success">En savoir plus</a>
                </div>
            <?php
                endforeach;
            }else{
                echo "<div class='text-center'>Aucun résultat pour votre recherche</div>";
            }
        }
    ?>
    </div>
</div>