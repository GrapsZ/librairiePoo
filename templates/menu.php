<header>
    <div class="collapse bg-primary" id="navbarHeader">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-md-7 py-4">
                    <h4 class="text-white">Notre concept</h4>
                    <p class="text-muted">Texte descriptif du site et des fonctionnalités disponibles ici. Lorem ipsum etc et osef pour le suite lol.</p>
                </div>
                    <?php if (!Users::isConnected()) { ?>
                        <div class="col-sm-4 offset-md-1 py-4">
                            <h4 class="text-white">Mon espace</h4>
                            <ul class="list-unstyled">
                                <li><a href="register.php" class="text-white">Inscription</a></li>
                                <li><a href="connect.php" class="text-white">Connexion</a></li>
                                <li><a href="products.php" class="text-white">Tous nos livres</a></li>
                                <li><a href="contact.php" class="text-white">Nous contacter</a></li>
                            </ul>
                        </div>
                    <?php } else { ?>
                    <div class="col-sm-4 offset-md-1 py-4">
                        <h4 class="text-white">Bienvenue, <?php echo $_SESSION['pseudo'] ?></h4>
                        <ul class="list-unstyled">
                            <li><a href="account.php" class="text-white">Mon compte</a></li>
                            <li><a href="products.php" class="text-white">Tous nos livres</a></li>
                            <li><a href="contact.php" class="text-white">Nous contacter</a></li>
                            <li><a href="logout.php" class="text-white">Déconnexion</a></li>
                        </ul>
                    </div>
                   <?php } ?>
            </div>
        </div>
    </div>
    <div class="navbar navbar-dark bg-primary shadow-sm">
        <div class="container d-flex justify-content-between">
            <a href="./" class="navbar-brand d-flex align-items-center">
                <i class="fas fa-book-open"></i>&nbsp;
                <strong>Librairie</strong>
            </a>
            <div class="d-flex">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="cart_icon">
                    <a href="mycart.php"><i class="cart_icon_element fas fa-cart-arrow-down"></i></a>
                </div>
            </div>
        </div>
    </div>
</header>