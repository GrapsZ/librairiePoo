<?php
include "./templates/header.php";
include "./templates/menu.php";

if (Users::isConnected()) {
    header('Location: account.php');
    exit();
}

if (isset($_POST['submit'])) {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $name = Kernel::getInstance()->clean($_POST['username']);
        $pass = Kernel::getInstance()->clean($_POST['password']);
        $remember = false;

        if (isset($_POST['remember'])) {
            $remember = true;
        }
        if (Users::connectUser($name, $pass, $remember)) {
            Kernel::getInstance()->addAlert("success",
                "Félicitations, vous êtes connecté. Redirection dans quelques secondes...");
            header("Refresh: 2;url=account.php");
        } else {
            Kernel::getInstance()->addAlert("danger",
                "Les identifiants saisis sont incorrects ou votre compte est inactif.");
        }
    } else {
        Kernel::getInstance()->addAlert("warning",
            "Veuillez remplir tous les champs.");
    }
}
?>

    <main role="main">
        <div class="album py-5 bg-light">

            <div class="container">
                <div class="row">
                    <div class="form_connection">
                        <h2 class="mb-5 text-center">Connexion à votre compte</h2>

                        <?php include './templates/Alerts.php'?>

                        <form action="" method="POST">
                            <div class="form-group">
                                <span class="input-group-addon"><i class="fas fa-user"></i></span>
                                <label for="username">Nom de compte ou email</label>
                                <input type="text" name="username" placeholder="Saisissez votre nom de compte ou votre email" value="" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <span class="input-group-addon"><i class="fas fa-lock"></i></span>
                                <label for="password">Mot de passe</label>
                                <input type="password" name="password" placeholder="Saisissez votre mot de passe" value="" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="remember" value="1"/> Se souvenir de moi
                                </label>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Se connecter</button>
                        </form>
                        <a href="forgot_password.php" title="mot de passe oublié">Mot de passe perdu ?</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php
include "./templates/footer.php";
?>