


<div class="container">
    <h1 class="text-center">Les chaussures</h1>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php
            if($pg>1)
            {
                echo '<li class="page-item"><a class="page-link" href="index.php?action=products&page='.($pg-1).'">Previous</a></li>';
            }
            for($cpt=1;$cpt<=$nbPage;$cpt++)
            {
                echo '<li class="page-item"><a class="page-link" href="index.php?action=products&page='.$cpt.'">'.$cpt.'</a></li>';
            }
            if($pg!=$nbPage)
            {
                echo '<li class="page-item"><a class="page-link" href="index.php?action=products&page='.($pg+1).'">Next</a></li>';
            }
            ?>
        </ul>
    </nav>


    <div class="row justify-content-between">
            <?php
                $req = $bdd->prepare("SELECT products.cover AS image, products.nom AS pnom, products.id AS pid, marques.nom AS mnom FROM products INNER JOIN marques ON marques.id=products.marque ORDER BY products.id DESC LIMIT :offset,:limit");
                $req->bindParam(':offset',$offset, PDO::PARAM_INT);
                $req->bindParam(':limit',$limit, PDO::PARAM_INT);
                $req->execute();
                $donReq= $req->fetchAll(PDO::FETCH_ASSOC);
                $req->closeCursor();
                foreach($donReq as $product):
            ?>        
            <div class="card col-md-4 p-2">
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