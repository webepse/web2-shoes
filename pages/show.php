<div class="container">
    <h3><?= $donProd['mnom'] ?></h3>
    <h1><?= $donProd['pnom'] ?></h1>
    <div class="col-6">
        <img src="images/<?= $donProd['image'] ?>" alt="" class="img-fluid">
    </div>
    <?php
        $statment = "SELECT * FROM images WHERE id_product=?";
        $rowCountGal = $bdd->prepare($statment);
        $rowCountGal->execute([$id]);
        $myCount = $rowCountGal->rowCount();
        if($myCount>0)
        {
    ?>
        <div id="carouselExampleIndicators" class="carousel slide col-6" >
                <div class="carousel-indicators">
                    <?php

                        for($cpt=0; $cpt<$myCount; $cpt++)
                        {
                            if($cpt==0)
                            {
                                echo ' <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="'.$cpt.'" class="active" aria-current="true" aria-label="Slide '.($cpt+1).'"></button>';
                            }else{
                                echo '<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="'.$cpt.'" aria-label="Slide '.($cpt+1).'"></button>';
                            }
                        }
                    ?>
                </div>
                <div class="carousel-inner">
                    <?php
                        $gallery = $bdd->prepare($statment);
                        $gallery->execute([$id]);
                        $donGal = $gallery->fetchAll(PDO::FETCH_OBJ);
                        $gallery->closeCursor();
                        $countGal = 0;
                        foreach($donGal as $gal)
                        {
                            if($countGal==0)
                            {
                                echo '<div class="carousel-item active">';
                                    echo '<img src="images/'.$gal->fichier.'" class="d-block w-100" alt="'.$donProd['pnom'].'" class="img-fluid">';
                                echo '</div>';
                            }else{
                                echo '<div class="carousel-item">';
                                    echo '<img src="images/'.$gal->fichier.'" class="d-block w-100" alt="'.$donProd['pnom'].'">';
                                echo '</div>';
                            }
                            $countGal++;
                        }
                    ?>
                
                        
                    
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
    <?php   
        }
        else{
            echo "<p>Il n'y a pas d'image</p>";
        }
    ?>


   
</div>