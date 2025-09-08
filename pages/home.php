<div class="container">
    <h1 class="text-center">Accueil</h1>
    <div class="row justify-content-between">
            <?php
                $req = $bdd->query("SELECT products.cover AS image, products.nom AS pnom, products.id AS pid, marques.nom AS mnom FROM products INNER JOIN marques ON marques.id=products.marque ORDER BY products.id DESC LIMIT 0,3");
                $donReq= $req->fetchAll(PDO::FETCH_ASSOC);
                $req->closeCursor();
                foreach($donReq as $product):
            ?>        
            <div class="card col-md-3">
                <div class="img">
                    <img src="images/<?= $product['image'] ?>" alt="image de <?= $product['pnom'] ?>" class="img-fluid">
                </div>
                <div class="content">
                    <h4><?= $product['mnom'] ?></h4>
                    <h3><?= $product['pnom'] ?></h3>
                </div>
                <a href="index.php?action=product&id=<?= $product['pid'] ?>" class="btn btn-success">En savoir plus</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>