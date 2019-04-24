<?php
include "./templates/header.php";
include "./templates/menu.php";

?>
<main role="main">

    <section class="jumbotron text-center">
        <div class="container">
            <h1 class="jumbotron-heading">Les meilleures ventes</h1>

        </div>
    </section>

    <div class="album py-5 bg-light">
        <div class="container">
            <div class="row">

                <?php
                    $livresList = Books::getBestSellers();
                    foreach ($livresList as $row => $value) {
                ?>
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <?php if ($value->getJaquette() != null): ?>
                            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><image x="0" y="0" width="100%" height="225" xlink:href="<?php echo $value->getJaquette(); ?>"></svg>
                        <?php else: ?>
                        <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em"><?php echo $value->getTitle(); ?></text></svg>
                        <?php endif; ?>
                        <div class="card-body">
                            <p class="card-text book_description">
                                <b>Résumé :</b> <?php echo substr($value->getDescription(), 0, 300)."[...]"; ?>
                            </p>
                            <a class="btn btn-primary mb-3" href=<?php echo "product.php?id=".$value->getId(); ?>>Lire plus &raquo;</a><br>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a href="<?php echo "product.php?id=".$value->getId(); ?>"><button type="button" class="btn btn-sm btn-outline-primary p-1 m-1">Consulter</button></a>
                                    <?php if (Users::isConnected())  {
                                        $user = Users::getUserById(intval($_SESSION['user_id']));
                                        ?>
                                    <a href="<?= "panier_actions.php?action=update_add&book_id=".$value->getId()."&user_id=".$user->getId()?>"><button type="button" class="btn btn-sm btn-outline-primary p-1 m-1">Acheter</button></a>
                                    <?php  } else { ?>
                                        <button type="button" class="btn btn-sm btn-outline-secondary p-1 m-1" disabled>Acheter</button>
                                    <?php } ?>
                                </div>
                                <small class="text-muted"><b>Prix :</b> <?php echo "".$value->getPrice()." €<br>"; ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                    <?php
                        }
                    ?>
            </div>
        </div>
    </div>
</main>
<?php
include "./templates/footer.php";
?>